# Task: Refactoring Actions/BootstrapAddons + Monolog Scoped Logging

- [x] `app/Framework/Contracts/AddonContract.php` — Interfaz `addonSlug()` / `addonPath()`
- [x] `app/Framework/Logging/ScopedMonologHandler.php` — Handler Monolog filtrado por namespace/path
- [x] `app/Framework/BaseAddonServiceProvider.php` — Clase abstracta base para todos los addons
- [x] `app/Framework/AddonResourceServiceProvider.php` — SP de recursos compartidos (vistas, Blade UI)
- [x] `app/Framework/AddonBootstrapper.php` — Registro de addons + exposición de paths/slugs
- [x] `app/Framework/AddonExceptionLogger.php` — Log al directorio del addon o al core (scope estricto)
- [x] `app/Providers/FrameworkServiceProvider.php` — Delega recursos a AddonResourceServiceProvider (SRP)
- [x] `config/logging.php` — Canal `framework` default con ScopedMonologHandler
- [x] `app/Framework/template/addon/app/Providers/AddonServiceProvider.php.stub` — Extiende BaseAddonServiceProvider
- [x] `app/Console/Commands/MakeAddonCommand.php` — `writeAddonServiceProvider()` genera provider minimalista
- [x] Verificación: `php -l` sin errores en todos los archivos
