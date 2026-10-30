# Política global de bases de datos

## Identificación

- Categoría: persistencia y motores de datos.
- Tecnología: todos los motores; adaptadores iniciales documentados por motor.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes, comandos, pruebas y herramientas.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: matriz lógica implementable; conexión real pendiente.

## Reglas generales

Esta política prevalece sobre PHP, Laravel y demás módulos. Los entornos canónicos son `TEST`, `DEVELOPMENT`, `STAGING`, `PRODUCTION` y `UNKNOWN`.

| Entorno | Consultas | Escrituras | Estructura | Eliminaciones |
| --- | --- | --- | --- | --- |
| TEST verificado | Catalogadas | Solo mecanismo controlado | Solo mecanismo controlado | Solo mecanismo controlado |
| DEVELOPMENT | Catalogadas y seguras | Prohibidas | Prohibidos | Prohibidas |
| STAGING | Catalogadas y seguras | Prohibidas | Prohibidos | Prohibidas |
| PRODUCTION | Catalogadas y seguras | Prohibidas | Prohibidos | Prohibidas |
| UNKNOWN | Solo verificaciones seguras | Prohibidas | Prohibidos | Prohibidas |

En esta implementación no hay consultas SQL directas ni escrituras habilitadas.

## Matriz obligatoria

| Aplicación | Base real | Resultado |
| --- | --- | --- |
| TEST | TEST registrada | Solo elegible para mecanismo controlado |
| TEST | DEVELOPMENT/STAGING/PRODUCTION | Bloqueado |
| DEVELOPMENT/STAGING/PRODUCTION | TEST registrada | Bloqueado |
| DEVELOPMENT | DEVELOPMENT | Bloqueado para escrituras |
| PRODUCTION | PRODUCTION | Bloqueado para escrituras |
| UNKNOWN | Cualquiera | Bloqueado |

Para una operación de escritura deben cumplirse simultáneamente:

1. Entorno efectivo de aplicación TEST.
2. Base real idéntica al único registro autorizado.
3. Registro `CONFIGURED` y confirmación del usuario.
4. Identidad obtenida desde el servidor o archivo real, no solo desde configuración.
5. Todas las conexiones afectadas identificadas y coincidentes.
6. Validación y ejecución vinculadas al mismo contexto.
7. Comando y argumentos registrados en el ejecutor controlado.

`APP_ENV=testing`, un sufijo `_test`, `.env`, `--env=testing` o el nombre de una conexión no bastan por sí solos.

## Comandos permitidos

Solo la matriz lógica y las verificaciones documentadas como seguras. No se permite SQL arbitrario ni clientes de base de datos. Cualquier futura consulta debe demostrar que no escribe, no bloquea, no ejecuta funciones modificadoras ni produce archivos.

## Comandos prohibidos

En entornos protegidos y mientras no exista una excepción TEST válida:

- INSERT, UPDATE, DELETE, TRUNCATE, DROP, CREATE y ALTER.
- Índices, restricciones, migraciones, rollbacks, seeders y factories con escritura.
- Procedimientos, mantenimiento, restauraciones, importaciones y exportaciones con efectos.
- Scripts, clientes interactivos y cualquier equivalente.

**Alternativa manual:** el usuario ejecuta la operación tras recibir comando, entorno, destino y riesgos.

## Excepciones

La única excepción potencial es una operación registrada ejecutada sobre la única base TEST confirmada y en el mismo contexto validado. Actualmente no existe ninguna operación habilitada.

## Verificación

- Ejecutar la matriz lógica sin conexiones.
- Comprobar que registro ausente, identidad desconocida, servidor distinto y conexiones secundarias bloquean.
- Verificar que los comandos directos no tienen permisos.
- La prueba positiva real queda pendiente de una base TEST registrada y confirmada.

## Ampliación futura

Cada motor necesita un adaptador probado que identifique conexión y entorno, trate conexiones múltiples y demuestre el mismo contexto. Cada comando se habilita individualmente tras autorización.

## Limitaciones

Las credenciales actuales pueden conservar permisos de escritura en el motor. Esta solución no equivale a aislamiento por privilegios del servidor. Sin aplicación y conexión reales no se afirma verificación de identidad ni protección contra condiciones de carrera.
