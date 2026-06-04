---
name: security-layer
description: >-
  Capa de seguridad obligatoria del proyecto Sitio-Web. Usar al cerrar cada
  etapa: checklist de validación, detección de vulnerabilidades comunes en
  Laravel antes de avanzar.
---

# Capa de seguridad — Sitio-Web

## Objetivo

Detectar y corregir vulnerabilidades **antes** de cerrar cada etapa o solicitud.

## Cuándo ejecutar

- Al final de **cada etapa** de implementación.
- Antes de considerar una solicitud lista para revisión humana o despliegue.

## Checklist base (Laravel / web)

Marcar en el plan `spec/NNN-*.md` (sección Capa de seguridad):

| ID | Control | Verificación |
|----|---------|--------------|
| S1 | Secretos | Ninguna clave/API en código commiteado; `.env` fuera de Git |
| S2 | Entrada | Form Requests o `$request->validate()` en toda entrada de usuario |
| S3 | Autorización | Policies / `Gate` / middleware `can:` en acciones protegidas |
| S4 | Autenticación | Rutas sensibles detrás de `auth` / Sanctum según diseño |
| S5 | CSRF | Formularios POST en web con token CSRF |
| S6 | SQL | Sin concatenación de SQL crudo con input; usar Eloquent/ bindings |
| S7 | XSS | Blade `{{ }}` para datos no confiables; evitar `{!! !!}` sin sanitizar |
| S8 | Mass assignment | `$fillable` / `$guarded` en modelos expuestos a request |
| S9 | Subida de archivos | Validar mime/tamaño; almacenamiento fuera de `public` si es sensible |
| S10 | Rate limit | `throttle` en login, API públicas o endpoints abusables |
| S11 | Errores en prod | `APP_DEBUG=false`; sin stack traces al usuario final |
| S12 | Dependencias | `composer audit` si hay cambios en `composer.json` |

## Procedimiento por etapa

1. Revisar **solo** archivos tocados en la etapa (capa aplicación).
2. Recorrer checklist S1–S12; ampliar con ítems del plan si existen.
3. Si hay hallazgo **bloqueante**, corregir antes de marcar etapa como cerrada.
4. Documentar en el spec: ítems verificados y mitigaciones aplicadas.

## Comandos útiles

```bash
composer audit
php artisan route:list
```

Revisión manual de rutas nuevas: método HTTP, middleware, nombre.

## Severidad

| Nivel | Acción |
|-------|--------|
| Bloqueante | No avanzar de etapa (ej. credencial en repo, SQL injection obvio) |
| Alto | Corregir en la misma etapa |
| Medio | Registrar en plan; etapa dedicada si no cabe en alcance actual |

## Antipatrones

- Confiar solo en validación frontend.
- `{!! $userInput !!}` sin política clara de sanitización.
- Endpoints de administración sin autenticación/autorización.

## Referencia

Checklist ampliado por solicitud en cada `spec/NNN-*.md`.
