---
name: catalogo-whatsapp
description: >-
  Ejecutar spec/001-catalogo-whatsapp.md: app MVC PHP en product_catalog/
  con catálogo, WhatsApp, auth y admin CRUD. Por etapas; validar seguridad
  y pruebas manuales entre etapas.
---

# Skill — Catálogo WhatsApp

Plan: `spec/001-catalogo-whatsapp.md`

## Etapa 1 — Base

- [ ] `database/schema.sql`
- [ ] `config/database.php`, `config/app.php`
- [ ] `models/User.php`, `models/Product.php`
- [ ] `index.php` router + `.htaccess`

## Etapa 2 — Auth

- [ ] `controllers/AuthController.php`
- [ ] `views/login.php`, `views/register.php`
- [ ] Sesiones + middleware rol en router

## Etapa 3 — Público

- [ ] `controllers/ProductController.php`
- [ ] `views/home.php`, `views/layout.php`
- [ ] WhatsApp `wa.me` con mensaje codificado

## Etapa 4 — Admin

- [ ] `controllers/AdminController.php`
- [ ] `views/admin/dashboard.php`, `views/admin/products.php`
- [ ] Modales CRUD, upload imágenes

## Etapa 5 — Cierre

- [ ] `assets/css/palette.css`
- [ ] `INSTALL.md`
- [ ] Checklist S1-S8 del spec

**No modificar** archivos en `spec/` ni `skill/` desde PHP.
