# Adaptador MySQL

### Identificación

- Categoría: motor de base de datos.
- Tecnología: MySQL y PDO MySQL disponible.
- Versión: especificación inicial.
- Alcance: identificación de conexiones MySQL.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: `BLOQUEADO_PENDIENTE_DE_ADAPTADOR_Y_PRUEBA_REAL`.

### Reglas generales

No se permite el cliente interactivo ni SQL arbitrario. La identidad deberá obtenerse mediante PDO con una conexión de consulta en el mismo contexto de la operación.

### Comandos permitidos

No hay comandos de motor habilitados. Como especificación futura, el adaptador deberá consultar de forma segura:

- `SELECT DATABASE()` para la base actual.
- `@@hostname` y `@@port` para el servidor.
- Un identificador de servidor disponible y suficientemente estable, cuyo significado se documentará tras inspeccionar el servidor real.

### Comandos prohibidos

Escrituras, DDL, procedimientos, bloqueos, `INTO OUTFILE`, importaciones y cualquier SQL no validado. Alternativa: ejecución manual por el usuario.

### Excepciones

Ninguna mientras no exista adaptador probado, base registrada y confirmación.

### Verificación

En un proyecto autorizado se comprobarán valores reales contra el registro, servidor distinto, puerto distinto, nombre distinto y conexiones adicionales. No se usará una base de desarrollo o producción para la prueba.

### Ampliación futura

Implementar el adaptador PDO, límites de consulta, manejo de errores sin secretos y pruebas de mismo proceso. No exigir nuevos usuarios ni privilegios.

### Limitaciones

No hay conexión MySQL del proyecto ni credenciales inspeccionadas. La disponibilidad de PDO no demuestra que exista un servidor utilizable. Los privilegios del motor permanecen fuera de este mecanismo.
