# Catálogo de herramientas externas y MCP

### Identificación

- Categoría: MCP, Code Mode, plugins, navegador y APIs.
- Tecnología: OpenCode V2 `2.1.1`.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes y futuras integraciones.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: no hay MCP configurados; bloqueo estático pendiente de validación local.

### Reglas generales

Toda herramienta no catalogada está denegada. Una restricción de shell no puede eludirse mediante MCP, `execute`, navegador, plugins, worktrees o clientes externos.

### Comandos permitidos

- `opencode mcp list` para inspección de configuración.
- `webfetch` únicamente para documentación oficial de OpenCode.
- Skill `opencode` para consultar su comportamiento.

No se permiten herramientas MCP de ejecución ni otras skills en esta fase.

### Comandos prohibidos

- `execute` y Code Mode.
- Worktrees y utilidades de sesión accesibles mediante ese catálogo.
- Navegador y acciones web con efectos.
- MCP de bases de datos, scripts, despliegue, archivos, servicios o APIs.
- `websearch`, transferencias y herramientas personalizadas no revisadas.
- Skill `report` y cualquier capacidad que publique o modifique servicios externos.

**Alternativa manual:** el usuario utiliza la integración fuera del agente tras recibir el alcance exacto.

### Excepciones

No hay excepciones MCP. Añadir una requerirá configuración aprobada, catálogo de herramientas, análisis de efectos y permisos individuales.

### Verificación

- Confirmar ausencia de servidores MCP.
- Revisar denegación de `execute`, navegador y acciones desconocidas.
- Comprobar la política `integration.use: mcp:*` compatible con la instalación.
- No instalar un servidor para probar el bloqueo.

### Ampliación futura

Cada servidor exige inventario de herramientas, transporte, procesos iniciados, red, datos y permisos. Se preferirán herramientas directas con reglas individuales; Code Mode permanece bloqueado hasta una revisión específica.

### Limitaciones

Los plugins internos no son un sandbox y la política no controla clientes externos. No hay una prueba MCP en vivo porque no existe ningún servidor configurado.
