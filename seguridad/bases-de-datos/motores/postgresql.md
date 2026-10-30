# Adaptador PostgreSQL

### Identificación

- Categoría: motor de base de datos.
- Tecnología: servicio PostgreSQL 17 detectado; PDO PostgreSQL disponible; `psql` no está en PATH.
- Versión: especificación inicial.
- Alcance: identificación de conexiones PostgreSQL.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: `BLOQUEADO_PENDIENTE_DE_ADAPTADOR_Y_PRUEBA_REAL`.

### Reglas generales

El servicio detectado no se considera una base del proyecto. No se intentará conectar sin configuración y autorización. Toda identidad debe obtenerse desde la sesión real.

### Comandos permitidos

No hay comandos habilitados. Como especificación futura:

- `current_database()`.
- `inet_server_addr()` y `inet_server_port()`.
- `current_schema()` para el schema efectivo.
- Identificador del servidor cuando pueda obtenerse de forma suficiente y documentada.

### Comandos prohibidos

Escrituras, DDL, funciones con efectos, bloqueos, copias, importaciones, exportaciones y SQL arbitrario. Alternativa: ejecución manual por el usuario.

### Excepciones

Ninguna mientras el adaptador y la identidad real no estén probados.

### Verificación

Comprobar base, dirección, puerto, schema y servidor contra el registro. Probar también search_path, conexiones secundarias y servidor diferente en un entorno TEST autorizado.

### Ampliación futura

Implementar el adaptador PDO y pruebas de identidad, sin instalar `psql` ni cambiar privilegios del motor.

### Limitaciones

Hay un servicio PostgreSQL activo, pero no se conocen bases, conexiones ni credenciales del proyecto. No se ha realizado ninguna conexión.
