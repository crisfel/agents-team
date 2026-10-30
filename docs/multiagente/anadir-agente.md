# Guía para añadir un agente

La extensión debe conservar la fuente única de reglas y evitar cambios innecesarios en los especialistas existentes.

## 1. Definir la responsabilidad

Antes de crear archivos, especificar:

- ID y propósito.
- Modo: normalmente `subagent`.
- Alcance y límites.
- Entradas y salidas.
- Agentes con los que colabora.
- Herramientas necesarias y riesgos.
- Criterios que permiten verificar su trabajo.

No crear un agente si una responsabilidad existente puede ampliarse de forma más sencilla y coherente.

## 2. Crear la definición

Copiar `docs/multiagente/plantillas/agente.md` a:

```text
.opencode/agents/<id>.md
```

El nombre del archivo determina el ID. Una subcarpeta forma parte del ID; por ejemplo, `team/security.md` produce `team/security`. No guardar la plantilla dentro de `.opencode/agents/`.

Completar las 18 secciones sin copiar las reglas globales. El frontmatter debe usar sintaxis V2:

- `description` es obligatorio para que el coordinador pueda seleccionar el especialista.
- `mode` debe ser explícito.
- `permissions` se añade solo cuando necesita reglas específicas.
- `model`, `steps` y `color` son opcionales y requieren justificación.

No usar campos V1 ni inventar opciones.

## 3. Aplicar privilegio mínimo

Las reglas globales deniegan todas las acciones por defecto. Solo existen excepciones explícitas para inspección, edición acotada y comandos catalogados; delegación y preguntas directas permanecen denegadas salvo el rol del arquitecto. Añadir reglas específicas únicamente para:

- Denegar edición a agentes de solo lectura.
- Permitir una capacidad imprescindible del rol.
- Restringir recursos adicionales según el riesgo.

Recordar que la última regla coincidente prevalece y que un subagente usa sus propios permisos. Comprobar que una regla amplia no anula excepciones sensibles posteriores o anteriores.

## 4. Incorporarlo a la delegación

La configuración deniega `subagent` por defecto. Si el arquitecto debe utilizar el nuevo agente, añadir en `.opencode/agents/arquitecto.md` una regla específica:

```yaml
- action: subagent
  resource: <id>
  effect: allow
```

Este es el punto de integración deliberado. Como modifica un archivo existente, requiere el procedimiento y la autorización correspondientes. No cambiar la lista por una autorización genérica a todos los agentes: eso permitiría también agentes desconocidos o integrados no previstos.

No modificar backend, frontend, revisor, QA ni las reglas globales solo para registrar al nuevo agente.

## 5. Validar

Ejecutar:

```powershell
$location = [uri]::EscapeDataString((Get-Location).Path)
opencode debug agents
opencode api get "/api/agent/<id>?location%5Bdirectory%5D=$location"
```

Comprobar ID, modo, descripción y permisos efectivos. Después realizar una delegación de solo lectura desde `arquitecto` y verificar:

- El arquitecto puede lanzar exactamente ese agente.
- El agente recibe las reglas globales y su contexto de tarea.
- No puede delegar ni preguntar directamente al usuario salvo diseño explícito.
- Entrega el informe obligatorio.
- Sus permisos impiden o solicitan las operaciones previstas.

Documentar cualquier comprobación no ejecutada. La incorporación no se considera verificada solo porque el archivo Markdown existe.
