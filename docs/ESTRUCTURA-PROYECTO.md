# Estructura unificada — Sitio-Web / CAFEESQUINA

## Entrada única

| URL recomendada | Descripción |
|-----------------|-------------|
| **http://localhost/Sitio-Web/** | Sitio completo (Laravel + CAFEESQUINA) |
| http://localhost/Sitio-Web/public/ | Equivalente vía carpeta `public/` |
| ~~/Sitio-Web/cafeesquina/~~ | Redirige a la raíz (301) |

Archivos: `index.php` y `.htaccess` en la **raíz** del repo envían todo a Laravel (`public/index.php`).

## Mapa de carpetas (todas con rol)

```
Sitio-Web/
├── spec/              → Planes spec-as-source
├── skill/             → Skills para agente IA
├── docs/              → Documentación de arquitectura
├── cafeesquina/       → Motor MVC (controladores, modelos, vistas, assets)
├── app/               → Laravel: Bridge + Providers
├── routes/web.php     → Rutas: assets, uploads, delegación a cafeesquina
├── config/cafeesquina.php → Config negocio (WhatsApp, textos)
├── database/migrations/   → Tablas BD + seeders
├── database/seeders/CafeesquinaSeeder.php
├── public/            → Front controller Laravel
├── resources/         → Assets Laravel (Vite)
├── tests/Feature/     → Pruebas del sitio
├── storage/           → Logs, cache, sesiones Laravel
└── vendor/            → Dependencias Composer
```

## Flujo de una petición

1. Apache → `Sitio-Web/index.php` → `public/index.php` (Laravel).
2. `routes/web.php` sirve `/assets/*` y `/uploads/*` desde `cafeesquina/`.
3. Cualquier otra ruta → `CafeesquinaBridgeController` → `cafeesquina/router.php`.

## Base de datos

Un solo `.env`: `DB_DATABASE=cafeesquina`

```bash
php artisan migrate
php artisan db:seed
```

## Desarrollo

```bash
composer install
php artisan migrate --seed
php artisan test --filter=Cafeesquina
# Opcional assets:
npm run dev
```
