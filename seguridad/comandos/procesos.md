# Catálogo de procesos y servicios

### Identificación

- Categoría: procesos, servicios y tareas.
- Tecnología: sistema operativo y herramientas de ejecución.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: todos los agentes.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: bloqueado por defecto.

### Reglas generales

No se permite iniciar, detener, reiniciar, supervisar o modificar procesos, servicios, contenedores o tareas. Las versiones y consultas catalogadas no deben iniciar servicios deliberadamente.

### Comandos permitidos

No hay comandos de gestión de procesos permitidos. Las consultas exactas de otros catálogos solo se permiten para la información descrita en ellos.

### Comandos prohibidos

- `Start-Process`, `Stop-Process`, `Start-Service`, `Stop-Service`, `Restart-Service`.
- Servidores, workers, colas, contenedores, despliegues y tareas programadas.
- `docker`, `docker-compose`, `systemctl`, `service`, `sc`, `taskkill` y equivalentes.
- Ejecución en segundo plano de comandos no catalogados.

Consecuencias posibles: indisponibilidad, ejecución persistente, consumo de recursos y cambios de infraestructura. Alternativa: ejecución manual por el usuario.

### Excepciones

No hay excepciones. Los mecanismos controlados futuros no podrán iniciar procesos persistentes salvo autorización independiente.

### Verificación

Comprobar que las acciones no catalogadas se deniegan antes de crear procesos. No se ejecutan pruebas que puedan dejar procesos activos.

### Ampliación futura

Una operación de proceso requiere catálogo propio, límites de tiempo, identificación del ejecutable, análisis de efectos y autorización específica.

### Limitaciones

Las herramientas de OpenCode pueden mantener procesos propios fuera del control de esta política. No se afirma aislamiento de esos procesos internos.
