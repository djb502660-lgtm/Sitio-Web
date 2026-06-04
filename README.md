# Sitio-Web

Aplicación web con [Laravel 12](https://laravel.com/docs/12.x) y PHP 8.2+.

## Requisitos

- PHP >= 8.2
- Composer
- MySQL (Laragon)
- Node.js (opcional, para assets con Vite)

## Configuración

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Crear la base de datos `sitio_web` en MySQL y configurar `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sitio_web
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate
php artisan serve
```

## Licencia

MIT
