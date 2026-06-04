# Capa de especificación (`spec/`)

Planes detallados **spec-as-source**: la fuente de verdad para qué construir, en qué orden y con qué criterios de aceptación.

## Convención de nombres

| Patrón | Uso |
|--------|-----|
| `000-*.md` | Marco transversal / visión del proyecto |
| `NNN-<slug>.md` | Plan de una solicitud concreta (`001-login.md`, etc.) |

## Estructura obligatoria de cada plan

1. **Objetivo**
2. **Alcance** (incluye explícitamente qué queda fuera)
3. **Pasos de implementación** (etapas pequeñas y verificables)
4. **Riesgos y supuestos**
5. **Capa de testing** (unitarias, funcionales, criterios por etapa)
6. **Capa de seguridad** (checklist y mitigaciones)
7. **Norte del proyecto** (alineación con visión global)

## Flujo del agente

1. Leer `spec/000-orquestacion-spec-as-source.md` y el plan de la solicitud.
2. Leer la skill asociada en `skill/<slug>/SKILL.md`.
3. **No programar** hasta tener plan + skill revisados.
4. Ejecutar **por etapas**; validar testing y seguridad antes de pasar a la siguiente.

## Relación con la aplicación

- `spec/` y `skill/` **no** importan código de Laravel ni viven dentro del autoload de PHP.
- La implementación ocurre en la **capa aplicación** (código Laravel en raíz del repo: `routes/`, `resources/`, el directorio `app/` de Laravel, etc.).
- Ver `spec/000-orquestacion-spec-as-source.md` para el mapa de capas.
