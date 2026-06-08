# [003] Carrito de compras

## Objetivo

Permitir agregar varios productos a un carrito, revisar cantidades y enviar el pedido completo por WhatsApp, registrando las líneas en `orders`.

## Alcance

### Incluido

- Carrito en `localStorage` (cliente)
- Botón «Añadir al carrito» en tarjetas y detalle de producto
- Página `/carrito` con listado, cantidades y total
- Icono con contador en la barra de navegación
- Checkout por WhatsApp + endpoint `POST /carrito/checkout` para registrar pedidos
- Vistas Blade + fallback PHP en `cafeesquina/views/`
- Tests feature del carrito

### Excluido

- Pasarela de pago en línea
- Persistencia del carrito en base de datos
- Cupones / promociones automáticas en el carrito

## Pasos de implementación

### Etapa 1 — Backend y rutas

- [x] `CartController`, rutas `carrito` y `carrito/checkout`
- [x] Helper `whatsapp_cart_url()` y registro batch en `Order`
- **Criterio de salida:** rutas responden; checkout valida CSRF y productos

### Etapa 2 — Frontend y UI

- [x] Módulo `CE.cart` en `app.js`, estilos y vistas
- [x] Navbar, product-card y product show actualizados
- **Criterio de salida:** flujo añadir → ver carrito → WhatsApp funcional

## Capa de testing

| Flujo | Archivo | Casos mínimos |
|-------|---------|---------------|
| Página carrito | `tests/Feature/CafeesquinaCartTest.php` | 200, contenido clave |
| Checkout API | `tests/Feature/CafeesquinaCartTest.php` | CSRF, items válidos |

## Capa de seguridad

| Ítem | Verificación | Estado |
|------|--------------|--------|
| CSRF checkout | `ce_csrf_verify_request` | ☑ |
| Rate limiting | `ce_rate_limit` en checkout | ☑ |
| Validación productos | IDs enteros, producto disponible | ☑ |
| XSS | escape en vistas | ☑ |

## Skill asociada

- `skill/carrito-compras/SKILL.md`

## Registro de ejecución

| Etapa | Fecha | Resultado testing | Resultado seguridad | Notas |
|-------|-------|-------------------|---------------------|-------|
| 1–2 | 2026-06-08 | pendiente | pendiente | Implementación unificada |
