# Catálogo Laravel y Artisan

### Identificación

- Categoría: framework y comandos de aplicación.
- Tecnología: Laravel; no hay aplicación detectada en esta ubicación.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes y pruebas que arranquen la aplicación.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: `BLOQUEADO_PENDIENTE_DE_PROYECTO_REAL`.

### Reglas generales

Todo Artisan está bloqueado inicialmente. Un nombre informativo no demuestra seguridad: providers, eventos, comandos personalizados y arranque pueden escribir datos, generar archivos o contactar servicios.

### Comandos permitidos

No hay comandos Artisan permitidos. Para evaluar una aplicación real se revisarán individualmente, sin autorizarlos automáticamente:

- `php artisan --version`, `list`, `help` y `about`.
- `php artisan route:list`.
- `php artisan migrate:status`.

La aprobación requerirá evidencia de arranque, efectos y destino de conexiones.

### Comandos prohibidos

- `migrate`, `migrate:fresh`, `migrate:refresh`, `migrate:reset`, `migrate:rollback`.
- `db:wipe`, `db:seed` y cualquier operación de base de datos directa.
- `tinker` y consolas o ejecución PHP/SQL equivalente.
- Cachés, optimización, enlaces, publicación, generadores, colas y schedules.
- `key:generate`, `config:*`, `route:*`, `view:*`, `event:*`, `optimize*`, `storage:link`, `vendor:publish`, `make:*`, `queue:*` y comandos personalizados no auditados.
- PHPUnit, Pest y `php artisan test` cuando puedan producir efectos sin mecanismo controlado.

**Alternativa manual:** el usuario ejecuta el comando después de revisar entorno, conexión y riesgos.

### Excepciones

No hay excepciones. Las futuras migraciones o pruebas TEST exigirán ejecutor controlado, entorno TEST y base registrada. `--env=testing` nunca será suficiente.

### Verificación

Probar que las formas directas destructivas y `tinker` se deniegan antes de ejecutar. Como no existe `artisan`, una denegación fallida no llegaría a ejecutar una aplicación, pero aun así se detendría la prueba y se aplicaría el análisis de fallo.

### Ampliación futura

Auditar comandos reales, providers, eventos, conexiones y procesos. Implementar validación y comando en el mismo proceso antes de habilitar operaciones de prueba.

### Limitaciones

No hay `artisan`, `composer.json` ni aplicación que inspeccionar. No se afirma que un comando Laravel sea informativo o seguro en ausencia del proyecto.
