# [NNN] Título de la solicitud

> Copiar este archivo a `spec/NNN-<slug>.md` por cada nueva solicitud.

## Objetivo

<!-- Qué problema resuelve y por qué importa para el norte del proyecto -->

## Alcance

### Incluido

-

### Excluido

-

## Norte del proyecto

<!-- Cómo esta solicitud se alinea con la visión global -->

## Pasos de implementación

### Etapa 1 — [nombre]

- [ ] Tarea verificable
- **Criterio de salida:**

### Etapa 2 — [nombre]

- [ ] Tarea verificable
- **Criterio de salida:**

## Capa de testing

### Unitarias

| Componente | Archivo de prueba | Casos mínimos |
|------------|-------------------|---------------|
| | `tests/Unit/...` | |

### Funcionales / integración

| Flujo | Archivo de prueba | Casos mínimos |
|-------|-------------------|---------------|
| | `tests/Feature/...` | |

### Validación por etapa

- Tras Etapa 1: `php artisan test --filter=...`
- Tras Etapa N: suite completa relevante

## Capa de seguridad

| Ítem | Verificación | Estado |
|------|--------------|--------|
| Validación de entrada | Form Requests / reglas explícitas | ☐ |
| Autenticación / autorización | Policies, middleware | ☐ |
| CSRF (formularios web) | `@csrf` / VerifyCsrfToken | ☐ |
| Inyección SQL | Eloquent / bindings parametrizados | ☐ |
| XSS | escape en Blade `{{ }}` | ☐ |
| Secretos | sin credenciales en repo; `.env` ignorado | ☐ |
| Rate limiting (si aplica) | throttle en rutas sensibles | ☐ |

## Riesgos y supuestos

| Riesgo | Impacto | Mitigación |
|--------|---------|------------|
| | | |

**Supuestos:**

-

## Skill asociada

- `skill/<slug>/SKILL.md`

## Registro de ejecución

| Etapa | Fecha | Resultado testing | Resultado seguridad | Notas |
|-------|-------|-------------------|---------------------|-------|
| | | | | |
