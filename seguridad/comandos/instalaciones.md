# Catálogo de instalaciones y dependencias

### Identificación

- Categoría: instalación, actualización y eliminación de herramientas.
- Tecnología: gestores de paquetes y sistema.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: bloqueado por defecto.

### Reglas generales

No se instala, actualiza, desinstala ni modifica software o dependencias mediante agentes. Solo se permite `composer --version` como consulta.

### Comandos permitidos

- `composer --version`: identificar la versión; no cambia archivos ni dependencias y no ejecuta instalaciones.

### Comandos prohibidos

- `composer install`, `update`, `require`, `remove`, `dump-autoload`, `clear-cache`, `run-script` y scripts de Composer.
- Modificación de `composer.json` o `composer.lock` mediante comandos.
- npm, pip, apt, Chocolatey, Winget, instaladores, actualizadores y equivalentes.
- Instalación de MCP, plugins, Playwright o herramientas de análisis.

Consecuencias: ejecución de scripts, cambios de entorno, escritura de archivos y descarga de código. Alternativa: el agente propone dependencia, motivo, impacto y alternativas; el usuario decide e instala manualmente.

### Excepciones

No hay excepciones de instalación en esta fase. La aprobación para desarrollar una funcionalidad no es una excepción.

### Verificación

Comprobar que los comandos de instalación no coinciden con ninguna excepción y se deniegan antes de ejecutar. No se instala nada para probar el bloqueo.

### Ampliación futura

Una excepción requeriría una política separada, revisión de scripts, lockfiles, red y efectos, además de aprobación explícita. No se contempla en FASE2-SEG-BLOCK-01.

### Limitaciones

Las operaciones manuales del usuario y los procesos de OpenCode pueden cambiar el entorno fuera del alcance del agente.
