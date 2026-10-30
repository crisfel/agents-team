# Protección de configuración y archivos de seguridad

## Identificación

- Categoría: integridad del mecanismo.
- Tecnología: permisos y políticas OpenCode V2.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: política, catálogos, agentes, configuración y registro TEST.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: denegaciones de raíz y rutas anidadas comprobadas; evidencia en `pruebas/resultados-validacion.md`.

## Qué protege

- `opencode.jsonc` y equivalentes `opencode.json` en cualquier profundidad.
- `AGENTS.md` en la raíz y en directorios anidados.
- `.opencode/**` en la raíz y en cualquier profundidad.
- `seguridad/**`, incluidos ejecutores y `test-database.md`.
- `composer.json` y `composer.lock` en la raíz y en directorios anidados.
- Archivos de entorno y secretos definidos por patrones, incluidas claves SSH dentro de subdirectorios, tanto para lectura como para edición. `.env.example` se puede leer, pero no editar en esta fase.

## Reglas técnicas

- La base global deniega todas las acciones.
- Subagentes no pueden editar seguridad, agentes ni configuración, tampoco mediante rutas anidadas.
- Revisor y QA no pueden editar ningún archivo.
- El arquitecto tiene `ask` en rutas de administración, no `allow`.
- Shell desconocido y Code Mode se bloquean también mediante políticas duras.
- Las reglas de protección anidadas se evalúan después de las excepciones ordinarias y prevalecen como última coincidencia.
- Las aprobaciones guardadas no pueden superar un `deny` configurado.

## Registro TEST

Los subagentes no pueden modificarlo. Para el arquitecto, `ask` solo es un punto de control técnico: no demuestra que se cumplan las condiciones. La modificación exige nombre proporcionado por el usuario, identidad real verificada y confirmación explícita.

## Cambios de seguridad

Toda modificación sigue el protocolo de líneas existentes: fragmento actual, sustitución, riesgos, alternativas y pruebas. El arquitecto no puede aprobar sus propios cambios. No se reemplazan archivos completos cuando una extensión localizada sea suficiente.

## Verificación

- Intentar edición de cada subagente en seguridad y configuración.
- Probar configuración, instrucciones, agentes y manifiestos tanto en la raíz como en rutas anidadas.
- Intentar leer y editar rutas ficticias de claves SSH y certificados situadas en subdirectorios.
- Comprobar `edit: deny` de revisor y QA.
- Revisar las reglas efectivas del arquitecto.
- Intentar alterar el registro TEST sin confirmación.
- Revisar aprobaciones guardadas y precedencia.

## Ampliación futura

Nuevos archivos de seguridad deben quedar dentro de `seguridad/**` o recibir reglas equivalentes. Nuevos agentes heredan la denegación global y no obtienen excepciones de administración.

## Limitaciones

Los permisos por ruta no comprenden el contenido ni distinguen creación, modificación y eliminación. El arquitecto puede solicitar cambios, pero la autorización y las condiciones del registro dependen del protocolo y de las pruebas. Una autoridad de configuración superior o un usuario puede modificar la política fuera de este flujo.
