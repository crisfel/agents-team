# Política global de seguridad

## Identificación

- Categoría: política transversal de ejecución.
- Tecnología: OpenCode V2, shell, herramientas y agentes.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: `arquitecto`, `backend`, `frontend`, `revisor`, `qa` y futuros agentes.
- Propietario: arquitecto, solo con autorización del usuario.
- Estado de validación: evidencia parcial en `pruebas/resultados-validacion.md`; QA final no ejecutada y prueba real pendiente.

## Reglas generales

1. Todo comando, herramienta u operación está prohibido por defecto.
2. Solo se permite lo incluido simultáneamente en el catálogo y en los permisos efectivos.
3. Si un comando no está clasificado, sus efectos no son conocidos o su permiso no puede verificarse, está bloqueado.
4. `ASK` no convierte comandos desconocidos en permitidos. Las aprobaciones no se usan para ampliar shell.
5. Una autorización genérica de desarrollo no autoriza instalaciones, configuración sensible, Git de escritura, bases protegidas, despliegues, servicios o infraestructura.
6. Ningún agente puede cambiar sus permisos, catálogos, denegaciones, registro TEST o mecanismos para eludir esta política.
7. El arquitecto tampoco modifica seguridad unilateralmente; necesita autorización concreta del usuario.
8. La política de bases de datos prevalece sobre lenguajes, frameworks y demás reglas generales en su dominio.

## Comandos permitidos

Los comandos permitidos se enumeran en los módulos de `comandos/`, `lenguajes/` y, cuando exista una aplicación auditada, `frameworks/`. La lista operativa de OpenCode es la autoridad técnica; cualquier discrepancia se resuelve bloqueando hasta corregirla con autorización.

## Comandos prohibidos

Quedan prohibidos todos los no catalogados, incluidos los que produzcan efectos equivalentes mediante alias, scripts, intérpretes, sustitución, encadenamiento, redirección, MCP, Code Mode, plugins o herramientas personalizadas.

Cuando se necesite una operación bloqueada, el agente entregará al arquitecto:

- Comando exacto.
- Propósito.
- Efectos previstos.
- Riesgos.
- Alternativas seguras.

El arquitecto presentará la solicitud al usuario para que este decida si la ejecuta manualmente. No se ejecutará mediante una aprobación genérica de OpenCode.

## Excepciones

- Lectura y búsqueda dentro del proyecto únicamente por las herramientas permitidas.
- Edición `ask` solo en rutas expresamente catalogadas y tras el protocolo de cambios.
- Shell únicamente para comandos exactos registrados.
- Escrituras de base de datos solo cuando toda la política TEST esté implementada y verificada. En esta implementación no hay ninguna habilitada.

## Verificación

- Inspeccionar permisos y políticas efectivos de los cinco agentes.
- Comparar catálogos y configuración.
- Intentar operaciones bloqueadas sin exponer datos reales.
- Probar compuestos, redirecciones y vías indirectas.
- Ejecutar la matriz unitaria sin bases de datos.
- Registrar resultados reales y pendientes.

## Ampliación futura

Toda ampliación exige identificar la operación, justificarla, analizar efectos y argumentos, añadirla a catálogo y permisos mediante autorización y demostrar los bloqueos correspondientes. Los agentes futuros heredan primero la denegación global.

## Limitaciones

OpenCode no es un sandbox del sistema operativo. Las políticas del proyecto pueden ser superadas por autoridades superiores. Los plugins y procesos externos requieren una evaluación separada. Ninguna afirmación de protección se considera válida sin evidencia de ejecución.
