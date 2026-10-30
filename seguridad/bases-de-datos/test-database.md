# Registro oficial de la base de datos TEST

Este es el único registro autorizado del proyecto. No contiene credenciales. Los agentes no pueden cambiarlo. El arquitecto solo puede actualizarlo después de recibir el nombre del usuario, verificar la conexión real y obtener la confirmación de los metadatos.

## Estado

- Estado actual: `UNCONFIGURED`.
- Escrituras autorizadas: ninguna.
- Confirmación del usuario: ausente.
- Identidad de servidor verificada: no realizada.

## Datos estructurados

<!-- TEST_DATABASE_REGISTRY:BEGIN -->
```json
{
  "schema_version": 1,
  "status": "UNCONFIGURED",
  "authorized_test_database": {
    "engine": null,
    "database_name": null,
    "connection_name": null,
    "host": null,
    "port": null,
    "schema": null,
    "server_identifier": null,
    "canonical_application_environment": "TEST"
  },
  "confirmed_by_user": false
}
```
<!-- TEST_DATABASE_REGISTRY:END -->

## Procedimiento de configuración

1. El usuario proporciona el nombre exacto de la base TEST.
2. El arquitecto identifica motor y conexión sin inventar valores.
3. Inspecciona parámetros no sensibles sin exponer secretos.
4. Establece una conexión de consulta mediante un adaptador probado.
5. Consulta la identidad real del destino y todas las conexiones afectadas.
6. Presenta los metadatos al usuario.
7. El usuario confirma explícitamente que son la base TEST autorizada.
8. Se registran los metadatos verificados y `confirmed_by_user: true`.
9. Se cambia `status` a `CONFIGURED`.

Mientras cualquier paso falte, el estado vuelve o permanece bloqueado. Un cambio futuro de base repite todo el procedimiento y mantiene las operaciones detenidas hasta terminar.

## Validación

El lector `mecanismos/src/TestDatabaseRegistry.php` exige un único bloque JSON válido y coherente. La matriz rechaza registros incompletos, no confirmados o incompatibles.

## Limitaciones

El registro no prueba por sí mismo la identidad del servidor. No almacena contraseñas ni concede permisos. La validación debe repetirse en el contexto de cada operación.
