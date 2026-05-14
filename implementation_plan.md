# Refactoring: Actions/BootstrapAddons + Monolog Scoped Logging

## Objetivo

Mejorar la modularidad, herencia de componentes, carga de estilos por scope y el ecosistema de logging del framework `framework-acorn` (Acorn 6 / Laravel 13), aplicando principios SOLID, sin perder funcionalidad existente.

---

## Open Questions

> [!IMPORTANT]
> **¿Existe ya algún Addon externo activo** (que use `AddonBootstrapper::register()`) que deba ser compatible sin cambiar su `plugin.php`? Según la exploración, los únicos plugins son terceros (elementor, woocommerce, etc.) — ningún addon propio del framework está activo. ¿Confirmas?

> [!IMPORTANT]
> **Sobre el log de Addons:** El spec dice que cada addon debe escribir en su propia raíz (`wp-content/plugins/mi-addon/mi-addon.log`). El `AddonExceptionLogger` actual escribe en `storage/logs/{plugin}.log`. ¿Quieres migrar el destino al directorio del plugin (fuera del storage del core)?

> [!WARNING]
> La clase `AddonResourceServiceProvider` está listada como **archivo activo abierto en el editor** pero **no existe todavía** en el filesystem. La creamos como nueva clase.

---

## Proposed Changes

### 1. Framework Core — Nuevos contratos y clases base

#### [NEW] `app/Framework/Contracts/AddonContract.php`
Interfaz que define el contrato mínimo de todo Addon registrable en el framework.

```php
interface AddonContract
{
    public function addonSlug(): string;
    public function addonPath(): string;
}
```

#### [NEW] `app/Framework/BaseAddonServiceProvider.php`
Clase abstracta que implementa `AddonContract` + hereda `ServiceProvider`. Centraliza:
- Detección automática del slug y path del addon desde `__FILE__` del proveedor hijo.
- Registro de assets con scope dedicado (CSS/JS) vía Vite manifest.
- Registro de vistas, rutas, traducciones y migraciones del addon.
- Logging localizado al directorio raíz del addon.

Reemplaza el stub `AddonServiceProvider.php.stub` que hoy genera código inline.

#### [NEW] `app/Framework/AddonResourceServiceProvider.php`
*(El archivo actualmente abierto en el editor, que no existe en disco)*

Responsabilidad: encapsula únicamente el registro de recursos compartidos del framework (vistas UI, layouts globales, Blade components) en un `ServiceProvider` separado del `FrameworkServiceProvider`, que hoy los mezcla con routing y comandos.

**Por qué:** `FrameworkServiceProvider` viola SRP al mezclar comandos, assets, shortcodes, y recursos de vista. Separar en `AddonResourceServiceProvider` permite que los addons hereden solo recursos sin acoplar la clase entera.

---

### 2. Framework Core — Logging aislado (Monolog)

#### [MODIFY] `config/logging.php`
Agregar un canal `framework` de tipo `monolog` con un `FilteringHandler` personalizado que:
- Solo procesa registros cuyo `extra.namespace` o `context.file` contenga `/wp-content/plugins/framework-acorn/` o los namespaces de addons registrados.
- Descarta silenciosamente (sin burbujear a WordPress) todo lo externo.

Cambiar `default` a `framework` en lugar de `stack`.

#### [MODIFY] `app/Framework/AddonExceptionLogger.php`
- Cambiar la ruta de log de addons de `storage_path("logs/{$plugin}.log")` a la raíz del plugin addon (`WP_PLUGIN_DIR . "/{$plugin}/{$plugin}.log"`).
- Agregar verificación de namespace: si el archivo origen del throwable **no** pertenece a `/wp-content/plugins/` o **no** es un addon registrado ni el core, retornar `false` inmediatamente (no loguear, dejar que WordPress lo maneje).

#### [NEW] `app/Framework/Logging/ScopedMonologHandler.php`
Handler Monolog personalizado que filtra por paths de namespace. Solo deja pasar registros originados en:
- `framework-acorn/` (core)
- Cualquier plugin con el header `Framework Addon: true` (addons registrados)

---

### 3. Framework Core — AddonBootstrapper mejorado

#### [MODIFY] `app/Framework/AddonBootstrapper.php`
- Agregar `registerInContainer(string $providerClass): void` — registra el addon en el contenedor IoC para soporte de type-hinting en constructores.
- Mantener `register()` como punto de entrada único (backward compatible).
- Agregar `registeredAddons(): array` — retorna los providers registrados (útil para el ScopedMonologHandler).

---

### 4. Framework Core — FrameworkServiceProvider refactorizado

#### [MODIFY] `app/Providers/FrameworkServiceProvider.php`
- Delegar el registro de vistas/Blade components a `AddonResourceServiceProvider`.
- Mantener: comandos, shortcodes, controllers, assets de framework.
- Registrar `AddonResourceServiceProvider` explícitamente como dependency.

---

### 5. Stubs — Addon generado hereda BaseAddonServiceProvider

#### [MODIFY] `app/Framework/template/addon/app/Providers/AddonServiceProvider.php.stub`
- Cambiar `extends ServiceProvider` por `extends \App\Framework\BaseAddonServiceProvider`.
- El stub queda limpio: solo implementa lo específico del addon.

#### [MODIFY] `app/Console/Commands/MakeAddonCommand.php` — `writeAddonServiceProvider()`
- El método ya no genera el cuerpo completo del provider inline.
- Genera un provider minimalista que extiende `BaseAddonServiceProvider`.
- Los assets, vistas y rutas se registran desde la clase base automáticamente.

---

## Verification Plan

### Automated
- `composer dump-autoload` — verifica que todas las clases nuevas son resolvibles.
- `php -l` sobre cada archivo nuevo — verifica sintaxis PHP 8.3.

### Manual
- Activar el plugin framework en WordPress → sin errores fatales.
- Verificar que `storage/logs/framework.log` recibe solo logs del core.
- Verificar que el `error_log` / `debug.log` de WordPress no se llena con errores del framework.
- Generar un addon de prueba con `wp acorn make:addon` y verificar que extiende `BaseAddonServiceProvider` correctamente.
