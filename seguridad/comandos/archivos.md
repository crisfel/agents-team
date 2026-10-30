# Catálogo de operaciones sobre archivos

### Identificación

- Categoría: sistema de archivos.
- Tecnología: herramientas OpenCode y shell.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: pruebas de edición, secretos y redirecciones parciales; eliminación, renombrado y cobertura completa pendientes.

### Reglas generales

La inspección se limita al proyecto y a las herramientas permitidas. La edición ordinaria requiere `ask` y rutas expresamente contempladas. Seguridad y configuración solo pueden cambiarse por el arquitecto con autorización del usuario.

### Comandos permitidos

| Operación | Sintaxis/recurso | Propósito | Efectos | Condiciones | Justificación |
| --- | --- | --- | --- | --- | --- |
| Lectura | `read` dentro del proyecto | Inspección | Sin escritura | Excluir secretos | Necesaria para análisis |
| Enumeración | `glob` | Localizar archivos | Sin contenido ni escritura | Dentro del proyecto | No ejecuta archivos |
| Edición acotada | `app/**`, `src/**`, `tests/**`, `docs/**` | Código y documentación | Escritura | `ask` y protocolo de cambios | Rutas explícitas |
| Administración | seguridad y configuración | Cambios de política | Escritura sensible | Solo arquitecto, `ask` y aprobación | Protección del mecanismo |

### Comandos prohibidos

- Eliminación, renombrado, movimiento, copia o sobrescritura mediante shell.
- Escritura fuera del proyecto o mediante herramientas no autorizadas.
- Modificación de seguridad por subagentes.
- Lectura de `.env`, `.env.*` salvo `.env.example`, claves y secretos.
- Edición de `.env`, `.env.example`, manifiestos de dependencias y configuración no catalogada.
- Redirecciones de comandos permitidos hacia archivos.

**Alternativa manual:** el usuario realiza la operación tras recibir ruta, contenido y riesgos.

### Excepciones

`.env.example` puede leerse si no contiene datos reales, pero su edición queda bloqueada en esta fase como decisión conservadora. Las rutas de seguridad pueden editarse por el arquitecto solo después de una autorización específica. `test-database.md` tiene además las condiciones de su propia política.

### Verificación

Intentar escrituras de subagentes en seguridad, configuración y registro TEST. Probar redirección shell y acceso externo. Comparar el contenido antes y después cuando se autorice un fixture.

### Ampliación futura

Nuevas rutas de código se añadirán por proyecto, nunca mediante una autorización genérica a todo el sistema de archivos. Las reglas de secretos se ampliarán según los archivos reales detectados.

### Limitaciones

`edit` no distingue crear, modificar o eliminar líneas. Los patrones de secretos no son exhaustivos. `grep` permanece bloqueado para evitar una vía de lectura por contenido sin control de ruta.
