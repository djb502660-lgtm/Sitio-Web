# Capa de skills (`skill/`)

Instrucciones ejecutables para el **agente de IA**. No forman parte del runtime de Laravel.

## Skills del proyecto

| Skill | Cuándo usarla |
|-------|----------------|
| [spec-as-source](spec-as-source/SKILL.md) | **Siempre** al inicio de una solicitud o antes de escribir código |
| [testing-layer](testing-layer/SKILL.md) | Al cerrar cada etapa de implementación |
| [security-layer](security-layer/SKILL.md) | Antes de dar por terminada una etapa o solicitud |

## Convención

- Una carpeta por feature o solicitud: `skill/<slug>/SKILL.md`
- Cada skill de feature referencia su plan: `spec/NNN-<slug>.md`
- Tareas pequeñas, numeradas, con criterio de salida explícito

## Integración con Cursor

El agente debe **leer explícitamente** los archivos en `skill/` de este repositorio (no sustituyen las skills globales de Cursor; las complementan para este proyecto).

Punto de entrada del repositorio: `AGENTS.md` en la raíz.
