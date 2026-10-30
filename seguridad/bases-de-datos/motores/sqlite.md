# Adaptador SQLite

### Identificación

- Categoría: motor de base de datos.
- Tecnología: SQLite CLI `3.36.0` y PDO SQLite disponible.
- Versión: especificación inicial.
- Alcance: identificación de archivos SQLite.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: `BLOQUEADO_PENDIENTE_DE_ADAPTADOR_Y_PRUEBA_REAL`.

### Reglas generales

La identidad se basa en la ruta real del archivo, no en el nombre lógico de una conexión. El registro utilizará `database_name` como ruta canónica absoluta cuando se configure.

### Comandos permitidos

No hay comandos habilitados. Un adaptador futuro deberá consultar `PRAGMA database_list` y resolver la ruta real del archivo principal y de cualquier base adjunta.

### Comandos prohibidos

Escrituras, adjuntos no identificados, creación o eliminación de archivos, importaciones, exportaciones y SQL arbitrario. Alternativa: ejecución manual por el usuario.

### Excepciones

Ninguna sin archivo TEST registrado, identidad real y confirmación del usuario.

### Verificación

Resolver rutas reales, detectar archivos distintos, conexiones adjuntas y rutas no verificables. No se creará una base de prueba durante FASE2-SEG-BLOCK-01 porque el registro está sin configurar.

### Ampliación futura

Implementar el adaptador PDO SQLite y pruebas en un archivo expresamente registrado por el usuario. No se asumirá que `:memory:` es una base autorizada.

### Limitaciones

No existe un archivo SQLite del proyecto. La disponibilidad de CLI y PDO no demuestra una base utilizable ni segura.
