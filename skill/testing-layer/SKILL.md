---
name: testing-layer
description: >-
  Capa de testing obligatoria del proyecto Sitio-Web. Usar al cerrar cada
  etapa de un plan en spec/: definir y ejecutar pruebas unitarias y
  funcionales antes de avanzar.
---

# Capa de testing — Sitio-Web

## Objetivo

Validar cada componente y flujo **antes** de pasar a la siguiente etapa del plan en `spec/`.

## Cuándo ejecutar

- Al final de **cada etapa** descrita en `spec/NNN-*.md`.
- Tras corregir un bug relacionado con una etapa ya cerrada (re-ejecutar tests de esa etapa como mínimo).

## Tipos de prueba

### Unitarias (`tests/Unit/`)

- Lógica de dominio aislada (servicios, helpers, reglas puras).
- Sin HTTP ni base de datos salvo que el plan lo exija con mocks.

### Funcionales (`tests/Feature/`)

- Rutas HTTP, respuestas, redirecciones, autenticación.
- Integración con base de datos de testing (`.env` / `phpunit.xml`).

## Procedimiento por etapa

1. Abrir el plan `spec/NNN-<slug>.md` → sección **Capa de testing**.
2. Si faltan filas en las tablas de pruebas, **añadirlas al spec antes de codificar** la etapa.
3. Escribir o actualizar tests en la capa aplicación (`tests/`).
4. Ejecutar:

```bash
cd <raíz-del-repo>
php artisan test
```

O filtrado según el plan:

```bash
php artisan test --filter=NombreDelTest
```

5. **Criterio de avance:** salida exitosa (exit code 0) de los tests listados para la etapa.

## Definición de «hecho» para una etapa

- [ ] Tests nuevos o actualizados existen y pasan.
- [ ] No se rompieron tests de etapas anteriores (suite acordada en el plan).
- [ ] El spec documenta el comando ejecutado en «Registro de ejecución».

## Plantilla para añadir al plan

```markdown
### Etapa N — Testing

| Tipo | Archivo | Caso |
|------|---------|------|
| Unit | tests/Unit/XTest.php | describe comportamiento |
| Feature | tests/Feature/YTest.php | GET /ruta → 200 |
```

## Antipatrones

- Avanzar de etapa con tests fallando «para arreglar después».
- Tests que solo assert `true === true` sin comportamiento real.
- Omitir Feature tests en flujos con autenticación o persistencia.
