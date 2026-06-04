# [000] Orquestación spec-as-source — Sitio-Web

## Objetivo

Establecer una **capa de orquestación basada en IA** (`spec/`, `skill/`) separada de la aplicación Laravel, de modo que todo desarrollo futuro:

- Parta de planes escritos (spec-as-source).
- Se ejecute por etapas con validación de testing y seguridad.
- Mantenga el **norte del proyecto** y evite implementaciones ad hoc.

## Alcance

### Incluido

- Estructura de carpetas `spec/` y `skill/` en la raíz del repositorio.
- Plan maestro (este documento) y plantilla reutilizable de planes.
- Skill raíz `spec-as-source` y skills transversales de **testing** y **seguridad**.
- Documentación de fronteras entre orquestación y aplicación (`AGENTS.md`, actualización de `README.md`).
- Convenciones para solicitudes futuras (numeración, etapas, registro de ejecución).

### Excluido

- Refactor o reubicación del código Laravel existente.
- Acoplar `spec/` o `skill/` al autoload, rutas o providers de Laravel.
- Implementación de funcionalidades de negocio (login, CMS, etc.) en esta solicitud.
- Automatización CI/CD (se definirá por solicitud cuando aplique).

## Norte del proyecto

**Sitio-Web** es una aplicación web Laravel 12 orientada a evolucionar de forma **controlada y auditable**. La capa de orquestación existe para que el agente de IA:

1. Comprenda el propósito antes de escribir código.
2. Ejecute alineado a planes versionados en Git.
3. No sustituya el juicio humano en alcance crítico (seguridad, datos sensibles).

La aplicación productiva del negocio es **`cafeesquina/`** (PHP MVC). Laravel en la raíz quedó como instalación inicial y no debe mezclarse sin un plan de migración.

## Mapa de capas (actualizado)

```
Sitio-Web/
├── spec/          ← Planes (qué y cómo, por solicitud)
├── skill/         ← Instrucciones para el agente IA
├── cafeesquina/   ← APLICACIÓN ACTIVA (CAFEESQUINA)
├── app/           ← Laravel (Http, Models…) — no usado por cafeesquina hoy
├── routes/        ┐
├── resources/     ├─ Stack Laravel (opcional / futuro)
├── public/        ┘
└── docs/          ← ESTRUCTURA-PROYECTO.md
```

Ver `docs/ESTRUCTURA-PROYECTO.md` para el detalle completo.

**Regla de desacoplamiento:** ningún archivo en `spec/` o `skill/` debe ser `require`/`import` desde PHP, Vite o Node de la app.

## Pasos de implementación

### Etapa 1 — Fundamentos de orquestación ✅

- [x] Crear `spec/README.md`, plantilla y plan `000`.
- [x] Crear `skill/README.md` y skills: `spec-as-source`, `testing-layer`, `security-layer`.
- [x] Añadir `AGENTS.md` en raíz como punto de entrada para agentes.

**Criterio de salida:** estructura visible en repo; agente puede seguir flujo sin tocar Laravel.

### Etapa 2 — Primera solicitud funcional (pendiente)

- [ ] El usuario define feature (ej. autenticación, landing, API).
- [ ] Generar `spec/001-<slug>.md` desde plantilla.
- [ ] Generar `skill/<slug>/SKILL.md` vinculada al plan.
- [ ] Ejecutar etapas del plan con validación entre cada una.

**Criterio de salida:** plan `001` aprobado implícitamente por el usuario al solicitar la feature.

### Etapa 3 — Hábito de validación continua (pendiente)

- [ ] Tras cada etapa de código: ejecutar pruebas definidas en el plan.
- [ ] Completar checklist de seguridad del plan.
- [ ] Actualizar tabla «Registro de ejecución» en el spec.

## Capa de testing

### Unitarias (marco del proyecto)

- Ubicación: `tests/Unit/`
- Herramienta: PHPUnit vía `php artisan test`
- **Política:** cada componente de dominio nuevo debe tener al menos un test unitario antes de cerrar la etapa que lo introduce.

### Funcionales

- Ubicación: `tests/Feature/`
- Cubrir rutas HTTP, autenticación y flujos críticos definidos en el plan de la solicitud.

### Validación obligatoria entre etapas

```bash
php artisan test
# o filtrado según el plan:
php artisan test --filter=NombreDelTest
```

No avanzar de etapa si los tests definidos para la etapa actual fallan.

## Capa de seguridad

Checklist base (extender por solicitud en cada plan):

| # | Control | Referencia Laravel / práctica |
|---|---------|-------------------------------|
| 1 | Variables sensibles solo en `.env` | `.env` en `.gitignore` |
| 2 | Validación server-side | Form Requests |
| 3 | Autorización explícita | Policies, `authorize()` |
| 4 | CSRF en formularios web | middleware web por defecto |
| 5 | Consultas parametrizadas | Eloquent / Query Builder |
| 6 | Salida escapada en vistas | Blade `{{ $var }}` |
| 7 | Cabeceras y HTTPS en producción | configuración servidor / `APP_URL` |
| 8 | Dependencias sin vulnerabilidades conocidas | `composer audit` cuando aplique |

Skill detallada: `skill/security-layer/SKILL.md`.

## Riesgos y supuestos

| Riesgo | Impacto | Mitigación |
|--------|---------|------------|
| Confusión entre `app/` (Laravel) y «capa aplicación» | Agente modifica archivos equivocados | Documentar mapa de capas; skills repiten la regla |
| Agente salta planificación | Código inconsistente | Skill obliga: spec → skill → etapas |
| `composer run dev` con Pail en Windows | Entorno dev caído | Usar `php artisan serve` + `npm run dev` (documentado en README) |
| Planes desactualizados respecto al código | Deriva | Actualizar spec al cerrar cada etapa |

**Supuestos:**

- PHP 8.2+, Composer, MySQL y Node disponibles en desarrollo.
- El agente lee `AGENTS.md` y las skills del proyecto antes de implementar.
- Las solicitudes del usuario se numeran secuencialmente (`001`, `002`, …).

## Skill asociada

- `skill/spec-as-source/SKILL.md` (orquestación general)
- `skill/testing-layer/SKILL.md`
- `skill/security-layer/SKILL.md`

## Registro de ejecución

| Etapa | Fecha | Testing | Seguridad | Notas |
|-------|-------|---------|-----------|-------|
| 1 — Fundamentos | 2026-06-03 | N/A (sin cambios en app) | N/A | Solo capa orquestación |
