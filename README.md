# Sitio-Web

Aplicación web con [Laravel 12](https://laravel.com/docs/12.x) y PHP 8.2+.

## Arquitectura unificada

| Capa | Carpeta | Rol |
|------|---------|-----|
| Planificación | `spec/` | Planes spec-as-source |
| Orquestación IA | `skill/` | Skills Cursor |
| Motor del sitio | `cafeesquina/` | MVC, vistas, assets |
| Framework | `app/`, `routes/`, `config/`, `database/`, `public/`, `tests/` | Laravel envuelve y enruta todo |
| Documentación | `docs/` | Arquitectura |

**URL principal:** http://localhost/Sitio-Web/

```bash
composer install
php artisan migrate --seed
php artisan test --filter=Cafeesquina
```

Detalle: [`docs/ESTRUCTURA-PROYECTO.md`](docs/ESTRUCTURA-PROYECTO.md) · Instalación: [`cafeesquina/INSTALL.md`](cafeesquina/INSTALL.md) · Producción: [`docs/DESPLIEGUE.md`](docs/DESPLIEGUE.md)

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
