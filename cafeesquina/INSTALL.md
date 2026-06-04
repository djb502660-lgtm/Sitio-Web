# CAFEESQUINA — Instalación (proyecto unificado)

## URL

**http://localhost/Sitio-Web/**

(Las URLs `/Sitio-Web/cafeesquina/...` redirigen automáticamente a la raíz.)

## Requisitos

PHP 8.2+, MySQL, Apache con `mod_rewrite`, Composer.

## Instalación

```bash
cd C:\xampp\htdocs\Sitio-Web
composer install
cp .env.example .env   # si no existe
php artisan key:generate
php artisan migrate --seed
```

## Credenciales admin

| Email | Contraseña |
|-------|------------|
| admin@cafeesquina.local | Admin123! |

## Configuración

- `.env` → `APP_URL=http://localhost/Sitio-Web`, `DB_DATABASE=cafeesquina`
- `config/cafeesquina.php` → WhatsApp, textos del negocio

## Script legacy (opcional)

```bash
php cafeesquina/scripts/migrate.php
```

Preferir siempre `php artisan migrate --seed`.

## Pruebas

```bash
php artisan test --filter=Cafeesquina
```
