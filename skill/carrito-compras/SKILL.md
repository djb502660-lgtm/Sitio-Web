---
name: carrito-compras
description: Implementar carrito de compras CAFEESQUINA con localStorage y checkout WhatsApp.
---

# Skill — Carrito de compras

## Etapa 1 — Backend

- `cafeesquina/controllers/CartController.php`
- Rutas en `cafeesquina/router.php`
- `whatsapp_cart_url()` y `Order::logLines()` en helpers/modelo

## Etapa 2 — Frontend

- `CE.cart` en `cafeesquina/assets/js/app.js` (+ copia `assets/js/`)
- Estilos en `cafeesquina/assets/css/cafeesquina.css`
- Vistas `resources/views/cafeesquina/cart/` y `cafeesquina/views/cart/`
- Actualizar navbar y product-card (Blade + PHP)

## Tests

```bash
php artisan test --filter=CafeesquinaCart
```
