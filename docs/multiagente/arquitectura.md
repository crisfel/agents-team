# Arquitectura del equipo multiagente

Este documento explica la estructura operativa. Las obligaciones normativas se mantienen en el `AGENTS.md` raíz; este documento no crea una segunda fuente de reglas.

## Componentes reconocidos por OpenCode

- `opencode.jsonc`: configuración del proyecto, agente predeterminado y políticas de permisos compartidas.
- `AGENTS.md`: reglas globales que OpenCode incorpora al contexto de los agentes.
- `.opencode/agents/arquitecto.md`: agente principal coordinador.
- `.opencode/agents/backend.md`: especialista backend.
- `.opencode/agents/frontend.md`: especialista frontend.
- `.opencode/agents/revisor.md`: revisor independiente sin permiso de edición.
- `.opencode/agents/qa.md`: verificador funcional y de seguridad sin permiso de edición.

El resto de archivos de `docs/multiagente/` son documentación de apoyo y no se cargan automáticamente como agentes o instrucciones.

## Identidad y selección

`arquitecto` usa `mode: primary` y se establece como `default_agent`. Los cuatro especialistas usan `mode: subagent`. La configuración desactiva `build` y `plan` en este proyecto para que el arquitecto sea el único agente principal seleccionable del equipo. Los agentes internos de mantenimiento de OpenCode no forman parte del equipo.

La selección predeterminada afecta a sesiones nuevas; una sesión existente conserva el agente que tenga almacenado.

## Flujo de control

1. El arquitecto analiza y formula un plan verificable.
2. El usuario aprueba el alcance.
3. El arquitecto define contratos y propietarios de archivos.
4. Backend y frontend trabajan en paralelo cuando no existen dependencias que lo impidan.
5. El revisor examina el resultado sin modificarlo.
6. Los responsables corrigen y el revisor repite su comprobación.
7. QA ejecuta los escenarios pertinentes.
8. Las correcciones posteriores se revalidan y, cuando cambian líneas revisadas, vuelven a revisión.
9. El arquitecto consolida el cierre y las limitaciones.

## Comunicación

La herramienta nativa `subagent` permite al arquitecto iniciar sesiones hijas y continuarlas mediante su `sessionID`. No se presupone mensajería directa entre subagentes. Las consultas se devuelven al arquitecto, que las reenvía y continúa la sesión correspondiente sin perder contexto técnico.

## Permisos

Las reglas compartidas establecen denegación por defecto:

- Solo se permiten acciones y comandos incluidos en la allowlist efectiva.
- Los comandos desconocidos se deniegan; `ask` no los convierte en permitidos.
- La edición ordinaria se limita a rutas catalogadas y `ask`; seguridad y configuración solo las administra el arquitecto con autorización del usuario.
- Acceso externo, Code Mode, navegador, MCP y herramientas desconocidas quedan denegados.
- Delegación denegada salvo las cuatro excepciones del arquitecto.
- Preguntas directas al usuario reservadas al arquitecto.
- Edición denegada para revisor y QA.
- Git se limita a comandos exactos de consulta.

Los permisos nativos no distinguen añadir líneas de modificarlas o eliminarlas. El protocolo del `AGENTS.md`, las propuestas concretas, la revisión del diff y las aprobaciones puntuales complementan esa limitación. Las políticas duras impiden ampliar shell mediante aprobaciones. No debe usarse `--auto` para eludir estos controles.

## Extensibilidad

Un agente nuevo se incorpora desde `plantillas/agente.md`, con su propio archivo en `.opencode/agents/`. Las reglas globales se heredan desde `AGENTS.md`. Si el arquitecto debe delegar en él, se añade una excepción específica a la lista de delegación mediante el procedimiento de autorización aplicable.

No es necesario modificar backend, frontend, revisor o QA para incorporar un especialista nuevo.

## Límites deliberados

- No hay persistencia histórica de QA.
- No se instala MCP Playwright ni ninguna dependencia como parte de esta infraestructura.
- No se inicializa Git ni se crea un historial artificial.
- No se configura un stack de aplicación, modelo o proveedor concreto.
- La existencia de instrucciones no se presenta como garantía técnica de comportamiento.
