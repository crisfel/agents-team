# Adaptador MariaDB

### Identificación

- Categoría: motor de base de datos.
- Tecnología: cliente distribución MariaDB `10.4.32` y PDO MySQL.
- Versión: especificación inicial.
- Alcance: identificación de conexiones MariaDB.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: `BLOQUEADO_PENDIENTE_DE_ADAPTADOR_Y_PRUEBA_REAL`.

### Reglas generales

MariaDB no se declara compatible automáticamente con un adaptador MySQL. Debe verificarse el servidor real y las diferencias de versión antes de reutilizar consultas de identidad.

### Comandos permitidos

No hay comandos habilitados. Un adaptador futuro deberá obtener, mediante consultas de solo lectura:

- Base actual con `SELECT DATABASE()`.
- Hostname y puerto del servidor.
- Identificador de servidor disponible y estable.

### Comandos prohibidos

Cliente interactivo, escrituras, DDL, bloqueos, procedimientos, archivos, importaciones y SQL arbitrario. Alternativa: ejecución manual por el usuario.

### Excepciones

Ninguna sin adaptador MariaDB probado y base TEST confirmada.

### Verificación

Probar identidad real, compatibilidad de consultas y discrepancias de motor, nombre, host, puerto y servidor. La prueba positiva está pendiente.

### Ampliación futura

Crear un adaptador específico o validar formalmente uno compartido con MySQL, sin instalar dependencias ni modificar privilegios.

### Limitaciones

El cliente existe en el sistema, pero no se ha verificado un servidor MariaDB del proyecto ni se ha intentado conectar.
