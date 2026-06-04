# Instrucciones para agentes de IA — Sitio-Web

Este repositorio usa el enfoque **spec-as-source**. Lee esto antes de cualquier implementación.

## Orden obligatorio

1. `spec/000-orquestacion-spec-as-source.md` — norte, capas y convenciones.
2. `skill/spec-as-source/SKILL.md` — flujo planificación → ejecución por etapas.
3. El plan de la solicitud actual: `spec/NNN-<slug>.md` (crear si no existe).
4. La skill de la solicitud: `skill/<slug>/SKILL.md`.
5. Al cerrar cada etapa: `skill/testing-layer/SKILL.md` y `skill/security-layer/SKILL.md`.

## Regla de oro

**No escribir código en la capa aplicación** hasta tener plan (`spec/`) y skill (`skill/`) para esa solicitud.

## Capas (no mezclar)

| Capa | Ruta | Rol |
|------|------|-----|
| Orquestación — planes | `spec/` | Qué construir |
| Orquestación — ejecución IA | `skill/` | Cómo el agente trabaja |
| **Aplicación activa** | `cafeesquina/` (PHP MVC + PDO) | Todo el código del negocio va aquí |
| Laravel (raíz) | `app/`, `routes/`, `resources/`, etc. | Plantilla separada; no mezclar con `cafeesquina/` sin plan explícito |

`spec/` y `skill/` **no** son PHP ejecutable. Ver [`docs/ESTRUCTURA-PROYECTO.md`](docs/ESTRUCTURA-PROYECTO.md).

## Desarrollo CAFEESQUINA

```bash
php cafeesquina/scripts/migrate.php
# http://localhost/Sitio-Web/cafeesquina/
```

## Laravel (opcional, dormido)

```bash
composer install && php artisan serve
```

Solo si se reactiva el stack Laravel; no es la app de cafetería actual.

## Nuevas features

`spec/NNN-*.md` + `skill/<slug>/SKILL.md` → implementar en `cafeesquina/` por etapas.
