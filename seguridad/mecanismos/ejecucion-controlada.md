# Mecanismo de ejecución controlada

## Identificación

- Categoría: validación y ejecución de operaciones sensibles.
- Tecnología: PHP 8.2 sin dependencias; adaptadores futuros por framework y motor.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: registro TEST y decisión de autorización.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: núcleo lógico ejecutable; ejecución destructiva deshabilitada.

## Qué protege

Evita que una operación se ejecute por una etiqueta de entorno, una configuración aparente o una conexión no verificada. Ningún subagente puede registrar comandos ni cambiar la política.

## Componentes actuales

- `src/TestDatabaseRegistry.php`: lectura determinista del bloque JSON oficial.
- `src/AuthorizationMatrix.php`: decisión fail-closed sobre entorno, registro, identidades, conexiones y contexto.
- `tests/run.php`: pruebas fijas sin escritura ni conexiones.

## Estado del ejecutor

- Operaciones destructivas registradas: ninguna.
- Adaptadores reales habilitados: ninguno.
- Comandos Artisan habilitados: ninguno.
- Pruebas de aplicación habilitadas: ninguna.
- Ejecución directa de SQL o shell de bases de datos: bloqueada.

No existe un passthrough de comandos. La matriz no ejecuta operaciones; solo decide si una hipotética operación sería elegible cuando todos los datos sean reales.

## Contrato para una futura operación

1. Comando incluido en una lista cerrada.
2. Argumentos validados sin overrides de entorno o conexión.
3. Registro `CONFIGURED` y confirmación del usuario.
4. Entorno efectivo TEST.
5. Identidad real obtenida en cada conexión.
6. Coincidencia exacta con el registro.
7. Ausencia de conexiones desconocidas.
8. Validación y ejecución en el mismo contexto.
9. Resultado registrado sin secretos.

Los procesos secundarios deben repetir la validación antes de escribir. Si no puede demostrarse, deben permanecer bloqueados.

## Verificación

El runner demuestra decisiones lógicas, registros inválidos y bloqueos por identidad o contexto. No demuestra conexión real ni ejecución segura de migraciones. La prueba positiva real está pendiente.

## Ampliación futura

Implementar adaptadores y operaciones solo en un proyecto real. Añadir pruebas de arranque, conexiones múltiples, carrera de configuración y destino antes de permitir una operación concreta.

## Limitaciones

La señal `same_execution_context` es un contrato, no una prueba de aislamiento. Las credenciales del motor pueden escribir fuera del mecanismo. No se afirma equivalencia con privilegios de solo lectura ni con un sandbox del servidor.
