# [001] Catálogo de Productos con Compra por WhatsApp

## Objetivo

Sistema web MVC en PHP 8 + MySQL + Tailwind CSS 3 donde visitantes ven un catálogo visual y compran vía WhatsApp; usuarios autenticados y administradores gestionan productos con CRUD completo.

## Alcance

### Incluido

- Aplicación en `product_catalog/` (estructura MVC solicitada).
- Módulo público, auth (registro/login/logout), panel admin, integración `wa.me`.
- SQL de esquema, `.htaccess`, router `index.php`, guía `INSTALL.md`.
- Paleta CSS personalizada, diseño responsive mobile-first.

### Excluido

- Integración con Laravel en raíz del repo.
- API REST separada, pasarela de pago, inventario multi-tienda.
- Tailwind build pipeline (se usa CDN Tailwind 3 en vistas).

## Norte del proyecto

Primera aplicación productiva del repositorio bajo capa aplicación dedicada (`product_catalog/`), independiente de Laravel.

## Pasos de implementación

### Etapa 1 — Base de datos y núcleo MVC

- SQL `users` + `products`, seed admin.
- `config/`, modelos `User`, `Product`, bootstrap y router.

### Etapa 2 — Autenticación

- Registro, login, logout, roles, protección de rutas admin.

### Etapa 3 — Catálogo público + WhatsApp

- Home, tarjetas, navbar, enlaces `wa.me` con mensaje dinámico.

### Etapa 4 — Panel administrativo

- Dashboard estadísticas, CRUD productos (modales, confirmación delete, uploads).

### Etapa 5 — UI, documentación y validación

- `palette.css`, vistas Tailwind, `INSTALL.md`, checklist seguridad.

## Capa de testing

| Tipo | Verificación |
|------|----------------|
| Manual | Registro/login, acceso admin denegado a user |
| Manual | CRUD producto completo |
| Manual | Enlace WhatsApp con nombre y precio |
| Manual | XSS: intento `<script>` en formulario → escapado en salida |

## Capa de seguridad

| ID | Control |
|----|---------|
| S1 | PDO prepared statements |
| S2 | `htmlspecialchars` en vistas |
| S3 | Validación server-side formularios |
| S4 | `password_hash` / `password_verify` |
| S5 | Rutas admin solo `role=admin` |
| S6 | CSRF tokens en POST |
| S7 | Sesión regenerada al login |
| S8 | Upload: tipo/tamaño validados |

## Riesgos y supuestos

| Riesgo | Mitigación |
|--------|------------|
| Base path en XAMPP variable | `config/app.php` con `BASE_URL` configurable |
| CDN Tailwind offline | Documentar en INSTALL |

**Supuestos:** XAMPP Apache+PHP 8.2+, MySQL, `mod_rewrite` habilitado.

## Skill asociada

`skill/catalogo-whatsapp/SKILL.md`

## Registro de ejecución

| Etapa | Fecha | Testing | Seguridad |
|-------|-------|---------|-----------|
| 1-5 | 2026-06-03 | Manual checklist | S1-S8 |
