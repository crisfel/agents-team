# Catálogo Git de solo lectura

### Identificación

- Categoría: control de versiones.
- Tecnología: Git `2.34.1.windows.1`.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes y vías indirectas disponibles.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: sin repositorio en la ubicación actual; denegaciones de escritura probadas, comportamiento positivo pendiente.

### Reglas generales

Solo se permiten comandos exactos de consulta. No se permiten comodines de argumentos, escritura de índice o historial, cambios de referencias, trabajo con worktrees ni ejecución indirecta mediante Code Mode. `--no-pager` evita paginadores y `--no-optional-locks` reduce bloqueos y actualizaciones opcionales.

### Comandos permitidos

```text
git --no-pager --no-optional-locks status
git --no-pager --no-optional-locks status --short
git --no-pager --no-optional-locks status --short --branch
git --no-pager --no-optional-locks diff
git --no-pager --no-optional-locks diff HEAD
git --no-pager --no-optional-locks diff --cached
git --no-pager --no-optional-locks diff --check
git --no-pager --no-optional-locks log -1 --oneline
git --no-pager --no-optional-locks show --stat --oneline HEAD
git --no-pager --no-optional-locks branch --list
git --no-pager --no-optional-locks tag --list
git --no-pager --no-optional-locks rev-parse HEAD
git --no-pager --no-optional-locks rev-parse --show-toplevel
git --no-pager --no-optional-locks ls-files
git --no-pager --no-optional-locks ls-files --others --exclude-standard
```

Propósito y efectos: inspeccionar estado, diferencias, historial limitado, referencias y archivos. Condición previa: no añadir argumentos ni modificar la sintaxis. Justificación: formas concretas sin opciones de salida a archivos.

### Comandos prohibidos

- Historial/referencias: `commit`, `push`, `pull`, `fetch`, `reset`, `revert`, `merge`, `rebase`, `cherry-pick`, `am`, `apply`.
- Índice/directorio: `add`, `restore`, `checkout`, `switch`, `rm`, `mv`, `clean`, `stash`.
- Administración: ramas o etiquetas de escritura, remotos, configuración, submódulos, mantenimiento, worktrees y cualquier cambio en `.git` o el directorio.
- Alias, abreviaturas, scripts, hooks invocados deliberadamente y equivalentes por otras herramientas.
- Opciones de salida, paginadores o controladores externos añadidos por el agente.

**Alternativa manual:** el agente presenta el comando exacto, su propósito y riesgos para ejecución por el usuario.

### Excepciones

No existen excepciones de escritura Git. Las operaciones TEST de bases de datos no modifican esta prohibición.

### Verificación

Intentar cada categoría de escritura en un contexto sin repositorio que pueda resultar afectado. Comprobar también compuestos y vías indirectas. Los comandos permitidos deben verificarse por coincidencia exacta. Una prueba histórica completa requiere un repositorio real o fixture autorizado.

### Ampliación futura

Cada consulta nueva exige revisar todos sus argumentos, salida, configuración y posibles procesos auxiliares. No se habilitan patrones generales.

### Limitaciones

La configuración de Git puede incluir controladores externos y comportamientos dependientes del repositorio. Sin un fixture con configuración hostil no se afirma cobertura absoluta contra todas las vías indirectas. La política no controla comandos Git ejecutados fuera de OpenCode.
