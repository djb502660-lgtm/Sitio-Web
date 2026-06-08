# Despliegue de CAFEESQUINA (Sitio-Web)

Guía para publicar la aplicación completa en un servidor con **PHP 8.2+** y **MySQL**.

## Qué hace cada entorno

| Entorno | URL ejemplo | Qué funciona |
|---------|-------------|--------------|
| **Laragon / XAMPP (local)** | `http://localhost/Sitio-Web/` | App completa ✅ |
| **Hosting PHP (producción)** | `https://tudominio.com/` | App completa ✅ |
| **Netlify** | `https://*.netlify.app/` | Solo assets de Vite (`/build/*`) ⚠️ |

Netlify no ejecuta PHP. El archivo `netlify/index.html` muestra un aviso en la raíz; no sustituye al hosting real.

---

## Requisitos del servidor

- PHP >= 8.2 (extensiones: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`)
- MySQL 8+ o MariaDB 10.3+
- Composer 2.x
- Node.js 18+ (solo para compilar assets con Vite)
- Apache con `mod_rewrite` **o** Nginx configurado para Laravel

---

## 1. Desarrollo local (Laragon)

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configura `.env`:

```env
APP_URL=http://localhost/Sitio-Web
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sitio_web
DB_USERNAME=root
DB_PASSWORD=
```

Crea la base de datos `sitio_web` en MySQL y ejecuta:

```bash
php artisan migrate --seed
npm install && npm run build
php artisan test --filter=Cafeesquina
```

**URL:** http://localhost/Sitio-Web/

**Admin:** `admin@cafeesquina.local` / `Admin123!`

---

## 2. Hosting compartido (cPanel / similar)

### Subir el proyecto

1. Sube el repositorio completo por FTP, Git o el administrador de archivos.
2. El **document root** del dominio debe apuntar a la carpeta **`public/`** del proyecto.

   Si no puedes cambiar el document root, mueve el contenido de `public/` a `public_html/` y ajusta las rutas en `index.php` (no recomendado; mejor apuntar el dominio a `public/`).

### Configuración en el servidor

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Edita `.env` en producción:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=tu_base
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Base de datos y assets

```bash
php artisan migrate --force
php artisan db:seed --class=CafeesquinaSeeder --force
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Permisos

Las carpetas deben ser escribibles por el usuario del servidor web:

```bash
chmod -R 775 storage bootstrap/cache
```

En cPanel suele bastar con permisos `755` en `storage/` y `bootstrap/cache/` (o usar el asistente de permisos).

### Apache

Asegúrate de que `public/.htaccess` esté activo (`AllowOverride All`).

---

## 3. VPS (Nginx + PHP-FPM)

Ejemplo de bloque `server` (simplificado):

```nginx
server {
    listen 80;
    server_name tudominio.com;
    root /var/www/Sitio-Web/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Despliegue típico:

```bash
cd /var/www/Sitio-Web
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload php8.2-fpm
```

**Laravel Forge** automatiza estos pasos si usas un VPS en DigitalOcean, Hetzner, etc.

---

## 4. Netlify (solo vista previa de assets)

Configuración en `netlify.toml`:

```toml
[build]
  command = "npm run build:netlify"
  publish = "public"
```

El script `build:netlify` compila Vite y copia `netlify/index.html` → `public/index.html` para evitar el 404 en la raíz.

**No uses Netlify como hosting principal** de CAFEESQUINA. No hay PHP ni MySQL.

---

## 5. Checklist antes de producción

- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` generada (`php artisan key:generate`)
- [ ] Base de datos creada y migrada (`php artisan migrate --force`)
- [ ] Assets compilados (`npm run build`)
- [ ] `storage/` y `bootstrap/cache/` con permisos de escritura
- [ ] Document root = `public/`
- [ ] HTTPS activo (certificado SSL)
- [ ] Cambiar contraseña del admin por defecto
- [ ] Probar: inicio, menú, login, panel admin

---

## 6. Solución de problemas

| Síntoma | Causa probable | Solución |
|---------|----------------|----------|
| CSS sin estilos | Assets no compilados | `npm run build` |
| 404 en todas las rutas | `mod_rewrite` off o docroot incorrecto | Apuntar a `public/`, revisar `.htaccess` |
| Error 500 | Permisos o `.env` | Revisar `storage/logs/laravel.log` |
| Imágenes rotas | Rutas de uploads | Verificar `cafeesquina/uploads/` y reglas en `.htaccess` |
| Sesión no persiste | Cookie path | Revisar `APP_URL` y `ce_app_base_path()` |

---

## Referencias

- Instalación local: [`cafeesquina/INSTALL.md`](../cafeesquina/INSTALL.md)
- Arquitectura: [`docs/ESTRUCTURA-PROYECTO.md`](ESTRUCTURA-PROYECTO.md)
- Laravel deployment: https://laravel.com/docs/12.x/deployment
