# Framework Core for WordPress

Framework modular basado en Acorn 6 para el desarrollo de ecosistemas de plugins en WordPress. Proporciona una arquitectura robusta utilizando Laravel 13, Livewire 4 y un sistema de compilación moderno mediante Vite y Tailwind CSS v4, permitiendo la creación de extensiones aisladas, seguras y altamente escalables.

## Introducción

Este framework ha sido diseñado para transformar el desarrollo tradicional de WordPress en una experiencia moderna y profesional similar a Laravel. Al integrar Acorn como núcleo, permite utilizar el contenedor de servicios, inyección de dependencias, sistema de plantillas Blade y herramientas de línea de comandos (CLI) dentro de un entorno de plugin de WordPress.

La arquitectura se centra en la modularidad a través de un sistema de "Addons", donde cada extensión hereda las capacidades del Core pero mantiene su propio contexto y ciclo de vida.

## Características Principales

### Motor de Aplicación Moderno
*   **Laravel 13 Integration**: Utiliza la última versión estable del ecosistema Laravel a través de Acorn 6.
*   **PHP 8.3 Ready**: Aprovecha las últimas funcionalidades del lenguaje para un código más limpio y eficiente.
*   **Livewire 4**: Soporte nativo para componentes reactivos del lado del servidor, eliminando la necesidad de escribir JavaScript complejo para interfaces dinámicas.

### Sistema de Construcción y Diseño
*   **Vite**: Compilación de activos ultrarrápida y reemplazo de módulos en caliente (HMR).
*   **Tailwind CSS v4**: Utiliza el motor de diseño más moderno con capacidades de personalización avanzadas y optimización automática.
*   **UI Component Library**: Incluye un sistema de diseño propio basado en Blade components listos para usar (Accordion, Modals, Autocomplete, etc.).

### Arquitectura de Addons
El framework implementa un patrón de arranque modular que permite:
*   Registro automático de Service Providers para cada addon.
*   Aislamiento de namespaces para evitar conflictos entre extensiones.
*   Localización automática de vistas y componentes de Livewire específicos de cada addon.

## Requisitos del Sistema

*   PHP >= 8.3
*   Composer >= 2.0
*   Node.js >= 20.0
*   WordPress >= 6.4
*   DDEV (Recomendado para entorno de desarrollo local)

## Instalación

1. Clone el repositorio dentro de la carpeta `wp-content/plugins/` de su instalación de WordPress:
   ```bash
   git clone https://github.com/andexer/framework-acorn.git
   ```

2. Instale las dependencias de PHP:
   ```bash
   composer install
   ```

3. Instale las dependencias de Node.js y compile los activos iniciales:
   ```bash
   npm install
   npm run build
   ```

4. Active el plugin desde el panel de administración de WordPress o mediante WP-CLI:
   ```bash
   wp plugin activate framework-acorn
   ```

## Desarrollo de Addons

El framework incluye comandos de CLI para acelerar el desarrollo. Para crear un nuevo addon, ejecute:

```bash
wp acorn make:addon NombreDelAddon
```

Esto generará una estructura de directorios estandarizada:
*   `app/`: Lógica de negocio y controladores.
*   `resources/`: Vistas Blade y activos frontend.
*   `routes/`: Definiciones de rutas personalizadas.

### Registro de Addons
Cada addon debe registrarse utilizando el `AddonBootstrapper` en su archivo principal:

```php
use App\Framework\AddonBootstrapper;

AddonBootstrapper::register(MyAddonServiceProvider::class);
```

## Comandos Disponibles

El framework extiende las capacidades de `wp acorn` para facilitar la gestión del proyecto:

*   `wp acorn list`: Muestra todos los comandos disponibles.
*   `wp acorn make:component`: Genera nuevos componentes de UI.
*   `wp acorn livewire:make`: Crea componentes reactivos de Livewire.
*   `wp acorn optimize`: Limpia y regenera las cachés de configuración y servicios.

## Estándares de Código

Se utiliza `Laravel Pint` para mantener la consistencia del código. Se recomienda ejecutar el formateador antes de cada commit:

```bash
./vendor/bin/pint
```

## Licencia

Este proyecto está bajo la licencia GPLv2.
