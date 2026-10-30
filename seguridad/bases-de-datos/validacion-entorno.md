# Validación de entorno y conexión antes de operaciones

## Identificación

- Categoría: verificación previa y mismo contexto.
- Tecnología: aplicaciones y conexiones de bases de datos.
- Versión: FASE2-SEG-BLOCK-01.
- Alcance: toda operación con posibles efectos de escritura.
- Propietario: arquitecto con aprobación del usuario.
- Estado de validación: especificación y matriz lógica; adaptadores reales pendientes.

## Reglas generales

Cada operación requiere una validación nueva vinculada al proceso y conexión que ejecutarán el comando. No se reutiliza una validación antigua ni una comprobación separada que pueda quedar obsoleta.

## Secuencia obligatoria

1. Cargar la configuración efectiva del proceso.
2. Determinar el entorno real de la aplicación.
3. Resolver todas las conexiones que la operación puede utilizar.
4. Establecer cada conexión mediante un adaptador de solo lectura.
5. Consultar la identidad desde el propio servidor o archivo.
6. Comparar todos los campos obligatorios con el registro.
7. Rechazar conexiones dinámicas, secundarias o destinos desconocidos.
8. Ejecutar solo una operación registrada en ese mismo contexto.
9. Registrar resultado sin secretos.

## Entorno de aplicación

- Solo el valor canónico `TEST` puede habilitar la evaluación positiva.
- Un valor como `testing` necesita una equivalencia previamente verificada y registrada en el proyecto; la matriz no la acepta directamente.
- `local`, `development` y nombres similares nunca se interpretan como TEST.
- En Laravel deben revisarse `APP_ENV`, `app.env`, caché, arranque, proceso de pruebas y overrides. Cualquier contradicción bloquea.

## Identidad de conexión

Debe comprobarse motor, conexión, base, host, puerto, schema cuando aplique e identificador del servidor cuando sea necesario. La identidad debe proceder del destino real, no únicamente de archivos de configuración.

## Conexiones múltiples

Migraciones, seeders, factories, modelos, eventos, servicios, tenants y procesos secundarios pueden abrir conexiones adicionales. Todas deben conocerse y verificarse. Una conexión desconocida o que apunte a un entorno protegido bloquea toda la operación.

## Mismo contexto

Para comandos de aplicación se prefiere validar y ejecutar dentro del mismo proceso arrancado. Para procesos secundarios, cada proceso debe repetir la validación antes de cualquier escritura. Si no puede demostrarse esta propiedad, el mecanismo permanece deshabilitado.

## Verificación actual

La clase `AuthorizationMatrix` exige `identity_verified` en cada conexión y una señal de mismo contexto. Las pruebas usan datos sintéticos para demostrar el bloqueo lógico, no para afirmar que esas señales proceden de una conexión real.

## Ampliación futura

Implementar adaptadores por framework y motor, pruebas de cambio de configuración y pruebas de carrera. Cada adaptador debe probarse antes de registrarse como disponible.

## Limitaciones

No existe una aplicación actual en la que cargar entorno o conexiones. No se ha establecido ninguna conexión. La señal de mismo contexto es un contrato para futuros adaptadores, no evidencia de ejecución real.
