# Protocolo operativo de coordinación

Este documento describe cómo aplicar el flujo normativo del `AGENTS.md`. No sustituye sus reglas ni añade excepciones.

## Paquete de delegación

El arquitecto entrega a cada especialista:

- Identificador de tarea o requisito.
- Objetivo y alcance aprobado.
- Entradas y resultados esperados.
- Contratos relacionados.
- Archivos o áreas de los que es responsable.
- Dependencias y trabajo que puede ejecutarse en paralelo.
- Criterios de aceptación.
- Pruebas requeridas.
- Restricciones de modificación y autorizaciones ya concedidas.

Los subagentes comienzan con contexto nuevo; por eso el prompt debe contener todo lo necesario y no depender de que conozcan la conversación principal.

## Contratos entre backend y frontend

Antes de trabajar en paralelo se acuerdan únicamente los elementos derivados de requisitos reales:

- Rutas, métodos, parámetros y respuestas.
- Tipos, interfaces y formatos.
- Validación y errores.
- Autenticación y autorización.
- Paginación, filtros y ordenamiento.
- Estados de carga, éxito y error.
- Criterios de aceptación compartidos.

Una decisión pendiente se marca expresamente. Cada agente puede avanzar en la parte independiente, pero no debe inventar el contrato faltante.

## Propiedad de archivos

El arquitecto mantiene una asignación explícita:

| Archivo o área | Único editor | Colaboradores | Contrato o restricción |
| --- | --- | --- | --- |
| Por definir por tarea | Agente asignado | Agentes consultores | Decisión aprobada |

Si dos especialistas necesitan el mismo archivo, uno edita y el otro entrega análisis o propuestas. Un conflicto detiene solo la parte afectada hasta que se resuelva.

## Consulta técnica mediante el arquitecto

1. El subagente termina su intervención actual con una consulta estructurada o un resultado parcial.
2. El arquitecto conserva el `sessionID` devuelto por la herramienta.
3. Remite la consulta completa al agente competente.
4. Recibe la respuesta y, si es útil, añade un resumen sin eliminar el original.
5. Continúa la sesión del consultante con la respuesta.
6. El consultante confirma la interpretación y reanuda el trabajo autorizado.

No se inventan identificadores, canales o mensajes. La API de sesiones no se considera automáticamente un canal peer-to-peer permitido.

## Formato de comunicación

- Tarea o requisito:
- Emisor:
- Destinatario:
- Pregunta o problema:
- Contexto técnico:
- Evidencia:
- Decisión solicitada:
- Supuestos pendientes:
- Impacto:
- Estado: pendiente, resuelto o bloqueado.

## Solicitud de modificación de líneas existentes

El especialista se detiene y envía los doce elementos exigidos por el `AGENTS.md`. El arquitecto presenta al usuario el cambio anterior y propuesto, no solo una descripción general. La autorización se vincula a ese cambio. Una eliminación se solicita por separado.

Tras una respuesta:

- **Aprobada:** releer el contexto, aplicar solo lo aprobado, probar y revisar el diff.
- **Rechazada:** no cambiar el contenido, estudiar extensión alternativa y repetir autorización si afecta a otras líneas.
- **Contexto desactualizado:** no aplicar; elaborar una nueva propuesta.

## Revisión, QA y ciclos

Cada hallazgo conserva un identificador estable. Un ciclo comprende identificación, comunicación, corrección, verificación y registro. Después del tercer ciclo fallido se analiza la causa, se documentan alternativas y se escala al usuario. Renombrar un hallazgo no reinicia el contador.

El revisor no corrige implementación. QA no cambia el sistema que evalúa. Las correcciones de QA que alteran comportamiento o líneas revisadas vuelven también al revisor.

## Comunicación final

El arquitecto consolida:

- Implementación y requisitos cumplidos.
- Comandos y pruebas realmente ejecutados.
- Resultados de revisión y QA.
- Autorizaciones y ciclos utilizados.
- Defectos persistentes y pruebas no ejecutadas.
- Estado completo, parcial o bloqueado, con justificación.
