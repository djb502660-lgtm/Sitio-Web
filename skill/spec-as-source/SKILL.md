---
name: spec-as-source
description: >-
  Orquestación obligatoria del proyecto Sitio-Web. Usar al recibir cualquier
  solicitud de desarrollo: crear o leer spec/, generar skill/, no programar
  hasta planificar, ejecutar por etapas con testing y seguridad.
---

# Spec-as-source — Sitio-Web

## Rol

Actúas como desarrollador senior en **diseño de sistemas asistidos por IA**. La fuente de verdad es `spec/`; la ejecución guiada es `skill/`; el código productivo vive en la **capa aplicación** (Laravel en la raíz del repo, **excluyendo** `spec/` y `skill/`).

## Reglas innegociables

1. **No programar de inmediato** ante una solicitud nueva.
2. **Analizar primero** el plan en `spec/` (o crearlo si no existe).
3. **Generar o actualizar** la skill en `skill/<slug>/` alineada al plan.
4. **Ejecutar por etapas**; una etapa = un conjunto acotado de cambios + validación.
5. **No acoplar** `spec/` ni `skill/` a Laravel (sin imports, rutas ni providers).

## Flujo por solicitud

### Fase A — Planificación (obligatoria)

1. Leer `spec/000-orquestacion-spec-as-source.md` (norte y capas).
2. Determinar número de solicitud siguiente (`001`, `002`, …).
3. Crear `spec/NNN-<slug>.md` usando `spec/templates/plan-template.md`.
4. Completar: Objetivo, Alcance, Pasos, Testing, Seguridad, Riesgos.
5. Crear `skill/<slug>/SKILL.md` con tareas atómicas mapeadas 1:1 a las etapas del plan.
6. **Detenerse** y confirmar con el usuario el alcance si hay ambigüedad crítica.

### Fase B — Ejecución (progresiva)

Por cada **etapa** del plan:

1. Anunciar qué etapa se ejecuta y qué archivos de la capa aplicación se tocarán.
2. Implementar **solo** lo definido en esa etapa.
3. Ejecutar pruebas indicadas en el plan → aplicar skill `testing-layer`.
4. Completar checklist de seguridad de la etapa → aplicar skill `security-layer`.
5. Actualizar la tabla «Registro de ejecución» en el spec.
6. **No iniciar la siguiente etapa** si testing o seguridad fallan.

### Fase C — Cierre

- Resumir qué se entregó vs. alcance.
- Indicar deuda técnica o etapas diferidas explícitamente en el plan.

## Tareas atómicas (plantilla para skills de feature)

```markdown
## Etapa N — [nombre]

**Precondición:** Etapa N-1 cerrada (tests + seguridad OK).

- [ ] T1: …
- [ ] T2: …

**Criterio de salida:** …

**Tests:** `php artisan test --filter=…`

**Seguridad:** ítems # de la tabla del plan
```

## Mapa rápido de capas

| Carpeta | Propósito |
|---------|-----------|
| `spec/` | Planes detallados |
| `skill/` | Instrucciones para el agente |
| `app/` (Laravel) | Models, Controllers, Providers |
| `routes/`, `resources/`, `database/`, `tests/` | Resto de la aplicación |

## Antipatrones (prohibidos)

- Implementar features sin spec numerado.
- Monolito en un solo commit/turno sin validación intermedia.
- Copiar lógica de negocio dentro de `spec/` o `skill/`.
- Ignorar exclusiones declaradas en «Alcance».

## Referencias

- Plan marco: `spec/000-orquestacion-spec-as-source.md`
- Plantilla de plan: `spec/templates/plan-template.md`
- Testing: `skill/testing-layer/SKILL.md`
- Seguridad: `skill/security-layer/SKILL.md`
