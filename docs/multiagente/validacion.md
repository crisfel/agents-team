# Procedimiento de validación de la infraestructura

Este documento define comprobaciones; no contiene resultados. Cada ejecución debe reportar la versión, los comandos realmente lanzados, los resultados observados y las comprobaciones no realizadas.

## 1. Línea base

Ejecutar desde la raíz del proyecto:

```powershell
opencode --version
opencode debug config
opencode mcp list
git status --short --branch
```

La ausencia de Git debe registrarse como limitación, no como error que deba ocultarse. Comprobar también que ningún archivo inesperado será sobrescrito.

## 2. Revisión estática

- Confirmar que `opencode.jsonc` incluye el esquema oficial y campos V2.
- Confirmar un único `default_agent` llamado `arquitecto`.
- Revisar frontmatter, descripciones, modos y orden de permisos.
- Comprobar que los cinco agentes y la plantilla contienen las 18 secciones en el orden establecido.
- Comprobar que `AGENTS.md` es la única fuente normativa compartida.
- Verificar que no existen bases de datos, hojas de cálculo ni almacenes históricos de QA.

## 3. Reconocimiento por OpenCode

```powershell
$location = [uri]::EscapeDataString((Get-Location).Path)
opencode debug agents
opencode api get "/api/agent?location%5Bdirectory%5D=$location"
opencode api get "/api/agent/arquitecto?location%5Bdirectory%5D=$location"
opencode api get "/api/agent/backend?location%5Bdirectory%5D=$location"
opencode api get "/api/agent/frontend?location%5Bdirectory%5D=$location"
opencode api get "/api/agent/revisor?location%5Bdirectory%5D=$location"
opencode api get "/api/agent/qa?location%5Bdirectory%5D=$location"
```

Comprobar que `response.location.directory` corresponde al proyecto. Si se omite `location`, el servicio puede consultar su ubicación predeterminada en lugar del proyecto.

Comprobar:

- `arquitecto` tiene modo `primary`.
- Los otros cuatro tienen modo `subagent`.
- Las descripciones son específicas.
- Revisor y QA tienen `edit: deny` efectivo.
- El arquitecto solo puede delegar en los cuatro especialistas.
- La plantilla no aparece como agente.
- No aparecen campos V1 ni advertencias de configuración.

Abrir una sesión nueva sin seleccionar otro agente y comprobar mediante sus metadatos que utiliza `arquitecto`. No usar una sesión anterior para esta comprobación.

## 4. Delegación y comunicación

Usar tareas de solo lectura, sin shell ni edición:

1. Delegar una inspección independiente a backend y frontend en paralelo.
2. Confirmar que ambos devuelven el informe requerido y no crean archivos.
3. Continuar una sesión mediante su `sessionID` real con una consulta técnica.
4. Comprobar que la respuesta conserva el contexto anterior.
5. Delegar una revisión y un ejercicio de cobertura a revisor y QA.
6. Verificar que ningún subagente puede iniciar otro ni preguntar directamente al usuario.

La salida del modelo es evidencia de la prueba realizada, pero no demuestra que todas las tareas futuras se comportarán igual.

## 5. Permisos y autorización

Las pruebas de escritura requieren un fixture y un alcance explícitamente aprobados. No utilizar archivos del usuario.

- Solicitar una modificación concreta y verificar que el informe contiene los doce elementos.
- Rechazarla y comprobar que el contenido y su hash no cambian.
- Aprobar otra sustitución concreta y comprobar que el diff solo contiene esa sustitución.
- Solicitar por separado cualquier eliminación.
- Intentar una edición con revisor y QA y comprobar que OpenCode la deniega.
- Verificar que los comandos desconocidos se deniegan, no que producen una solicitud de aprobación.
- Probar compuestos, redirecciones, sustitución y vías indirectas según `seguridad/pruebas/matriz-de-seguridad.md`.
- Diferenciar permisos efectivos, decisiones lógicas sintéticas y conexiones reales verificadas.

No usar `opencode --auto`. Las aprobaciones `once` solo se utilizan en ediciones expresamente contempladas; nunca para habilitar comandos desconocidos. Revisar cualquier aprobación persistente que pueda ampliar el alcance.

## 6. Revisión Git

En la ubicación sin Git, comprobar que el revisor declara la imposibilidad de comparación histórica.

La prueba histórica completa requiere un repositorio real o autorización específica para crear un fixture y sus commits. En ese contexto:

```powershell
git status --short
git rev-parse HEAD
git diff HEAD
git diff --cached
git diff --check
git ls-files --others --exclude-standard
```

Registrar primero los cambios preexistentes. Después comprobar líneas nuevas, modificadas y eliminadas, archivos no versionados y estabilidad del `HEAD`. No crear commits ni alterar historial durante la revisión salvo que el fixture y esas operaciones hubieran sido autorizados expresamente para preparar la prueba.

## 7. Revisión, QA y ciclos

- Ejercitar un hallazgo con identificador estable durante tres ciclos simulados o reales.
- Comprobar que cada ciclo contiene corrección y nueva verificación.
- Verificar que un tercer fallo se escala y no se declara resuelto.
- Confirmar que una prueba no ejecutada se reporta como no verificada.
- Confirmar que no se modifican tests para ocultar defectos.

Sin aplicación ejecutable, estas pruebas validan el protocolo, no la calidad funcional de una aplicación. Las pruebas backend, frontend, E2E y seguridad real deben ejecutarse en el primer proyecto que las admita.

## 8. Cierre

Contrastar la evidencia con los criterios de aceptación aplicables y con `seguridad/pruebas/matriz-de-seguridad.md`. Clasificar cada uno como verificado, definido pero no demostrable técnicamente, limitado o pendiente. No emitir `COMPLETADO_VERIFICADO` si falta una comprobación esencial.

No crear un historial persistente de QA como consecuencia de esta validación.
