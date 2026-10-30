# Seguridad de ejecución del equipo multiagente

Esta carpeta contiene la primera implementación de la fase de seguridad. Su objetivo es impedir operaciones no catalogadas y mantener bloqueadas las acciones destructivas salvo una excepción TEST verificada.

## Jerarquía

- `AGENTS.md`: reglas generales del equipo.
- `politica-global.md`: entrada normativa de seguridad.
- `comandos/`: catálogos por categoría.
- `bases-de-datos/`: política, registro y verificación de identidad.
- `lenguajes/` y `frameworks/`: restricciones específicas.
- `mecanismos/`: implementación y protección del mecanismo.
- `pruebas/`: matriz y resultados de la validación actual.

La configuración operativa está en `opencode.jsonc`. Los Markdown no sustituyen permisos ni pruebas.

## Estado inicial

- Shell: allowlist cerrada; todo lo demás denegado.
- Git: solo comandos exactos de consulta.
- PHP: consultas de entorno y ejecutor unitario de seguridad.
- Laravel y Artisan: bloqueados hasta auditar una aplicación real.
- Bases de datos: escrituras bloqueadas; registro TEST `UNCONFIGURED`.
- MCP, Code Mode y navegador: bloqueados.
- Prueba positiva con base TEST real: pendiente.

## Convenciones

- `PERMITIDO`: existe una excepción nativa y su validación se reporta aparte.
- `BLOQUEADO`: no existe una vía de ejecución autorizada.
- `PENDIENTE`: falta una comprobación o un proyecto real.
- Ningún estado documental se interpreta como evidencia técnica por sí solo.

## Administración

Solo el arquitecto puede proponer cambios de seguridad. Toda modificación requiere autorización explícita del usuario y las pruebas aplicables. Los subagentes no pueden editar estos archivos durante tareas ordinarias.
