# Catálogo de sistema operativo y shell

### Identificación

- Categoría: sistema operativo y shell.
- Tecnología: Windows, PowerShell 5.1; otros shells quedan pendientes.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: denegaciones principales de PowerShell probadas; cobertura no exhaustiva y otros shells pendientes.

### Reglas generales

Se deniega todo shell salvo comandos exactos. Las herramientas `read` y `glob` se usan para inspección ordinaria. `grep` está inicialmente denegado porque su permiso no limita la ruta del contenido buscado.

### Comandos permitidos

| Nombre | Sintaxis exacta | Propósito | Efectos | Condiciones previas | Justificación |
| --- | --- | --- | --- | --- | --- |
| OpenCode | `opencode --version` | Versión | Salida por consola | OpenCode instalado | Consulta informativa |
| OpenCode | `opencode debug agents` | Agentes efectivos | Salida por consola | Configuración cargada | Inspección local |
| OpenCode | `opencode debug config` | Fuentes de configuración | Salida por consola | Sin secretos en configuración | Auditoría; detener si expone datos sensibles |
| OpenCode | `opencode mcp list` | Estado MCP | Salida por consola | MCP gestionado por OpenCode | No instala ni añade servidores |

No se permiten comandos PowerShell directos en esta versión inicial.

### Comandos prohibidos

- Escritura, copia, movimiento, borrado o renombrado de archivos.
- Cambios de permisos, propietario, servicios, procesos, variables o infraestructura.
- `Remove-Item`, `Set-Content`, `Add-Content`, `Out-File`, `Copy-Item`, `Move-Item`, `Rename-Item`, `New-Item`.
- `Start-Process`, `Stop-Process`, `Invoke-Expression`, `Invoke-WebRequest`, `Start-BitsTransfer`.
- Alias, funciones, scripts y equivalentes de las operaciones anteriores.
- Redirecciones, sustitución de comandos, comandos compuestos y ejecución dinámica.
- Cualquier comando no registrado.

**Alternativa manual:** el agente entrega el comando exacto y su análisis; el usuario decide si lo ejecuta fuera del agente.

### Excepciones

No hay excepciones para operaciones de escritura o modificación del sistema. Las excepciones de otros catálogos no autorizan PowerShell ni cambios de intérprete.

### Verificación

Probar un comando desconocido, escritura PowerShell, redirección, sustitución y composición. El resultado esperado es denegación antes de ejecutar. Si una prueba escribe o inicia una operación, se detiene la validación y se aplica el fallback de denegación total de shell.

### Ampliación futura

Cada shell y comando requiere una entrada separada, análisis de alias y argumentos, prueba de efectos y aprobación. No se reutilizan reglas de PowerShell para Bash, Zsh o POSIX sh sin validación específica.

### Limitaciones

El escáner de OpenCode no constituye un sandbox. La inferencia de rutas es de mejor esfuerzo. Los procesos iniciados fuera del agente no quedan cubiertos.
