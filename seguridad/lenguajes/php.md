# Catálogo PHP y Composer

### Identificación

- Categoría: lenguaje y gestor de dependencias.
- Tecnología: PHP `8.2.12`, Composer `2.5.5`.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: consultas informativas auditadas, runner 42/42 y bloqueos de ejecución dinámica comprobados; cobertura no exhaustiva.

### Reglas generales

No se permite ejecutar PHP arbitrario. Las excepciones son comandos informativos exactos y el ejecutor unitario de seguridad protegido.

### Comandos permitidos

| Nombre | Sintaxis exacta | Propósito | Efectos | Condiciones | Justificación |
| --- | --- | --- | --- | --- | --- |
| PHP | `php --version` | Versión | Salida informativa | PHP en PATH | No ejecuta proyecto |
| PHP | `php -m` | Módulos | Salida informativa | PHP en PATH | Inspección de extensiones |
| PHP | `php --ini` | Configuración INI | Rutas y valores de carga | Revisar que no exponga secretos | Diagnóstico de entorno |
| Composer | `composer --version` | Versión | Salida informativa | Composer en PATH | No instala ni ejecuta scripts de proyecto |
| Seguridad | `php seguridad/mecanismos/tests/run.php` | Pruebas fijas | Lee código y escribe resultados en consola | Sin argumentos; archivo protegido | Ejecutor cerrado sin BD |

### Comandos prohibidos

- `php -r`, `php -a`, `php archivo.php` y ejecución dinámica.
- `php -l *` inicialmente: no se usa un comodín porque la ruta y argumentos no pueden restringirse semánticamente mediante permisos.
- Composer install/update/require/remove/dump-autoload/clear-cache/run-script.
- Scripts de Composer, instalaciones y modificación de manifiestos.
- Cualquier ejecutor alternativo no registrado.

**Alternativa manual:** el usuario ejecuta el comando concreto tras revisar archivo, propósito y riesgos. Una comprobación `php -l ruta/archivo.php` podrá proponerse como excepción exacta por archivo.

### Excepciones

El ejecutor `run.php` es la única secuencia PHP de proyecto permitida. No acepta argumentos, no usa PDO, no escribe archivos y carga únicamente dependencias fijas.

### Verificación

Probar ejecución dinámica, script desconocido, argumentos añadidos al ejecutor y Composer de escritura. Ejecutar el runner exacto y comprobar que todos sus resultados son reales.

### Ampliación futura

Cada comando necesita análisis de argumentos, archivos, proceso, red y efectos. Un ejecutor controlado debe protegerse contra edición de subagentes y validarse antes de permitirlo.

### Limitaciones

La información INI puede revelar rutas sensibles. Los comandos informativos no prueban seguridad del código PHP. El runner valida la lógica de política, no una aplicación PHP ni una conexión real.
