# Manual de Usuario — SIPSI
## Sistema Integral de Psiquiatría Hospitalaria
**Hospital Dr. César Aguilar — San Juan**
**Versión 1.0 — Mayo 2026**

---

## 1. Introducción

SIPSI es un sistema web para la gestión de oficios judiciales del Servicio de Psiquiatría del Hospital Dr. César Aguilar. Permite registrar, hacer seguimiento y cerrar los pedidos que llegan desde los juzgados, coordinando turnos, profesionales e informes periciales.

---

## 2. Acceso al sistema

### URL de acceso
```
http://sistema-oficios.test
```

### Iniciar sesión
1. Ingresá tu **correo electrónico** y **contraseña**
2. Hacé clic en **Iniciar sesión**
3. Si no tenés cuenta, contactá al administrador del sistema

### Cerrar sesión
En el sidebar izquierdo, abajo del todo, hacé clic en **Cerrar sesión**.

---

## 3. Navegación

El sistema tiene un **sidebar** (barra lateral izquierda) con las siguientes secciones:

| Sección | Descripción |
|---------|-------------|
| **Asistente** | Chatbot para consultas rápidas |
| **Oficios** | Gestión de oficios judiciales |
| **Pacientes** | Registro de pacientes |
| **Juzgados** | Registro de juzgados |
| **Est. Juzgados** | Estadísticas por juzgado |
| **Profesionales** | Registro de profesionales |
| **Estadísticas** | Dashboard general |

En la parte superior del sidebar hay un botón para cambiar entre **modo claro y oscuro**.

---

## 4. Dashboard (Estadísticas)

Al ingresar al sistema podés ver el resumen general:

- **Tarjetas KPI**: Total de oficios, pendientes, en curso, cerrados, pacientes y profesionales
- **Gráfico de barras**: Oficios recibidos por mes (últimos 6 meses)
- **Gráfico de torta**: Distribución de estados
- **Próximos turnos**: Los 5 turnos pendientes más cercanos
- **Oficios recientes**: Los últimos 5 oficios registrados
- **Informes sin enviar**: Informes que aún no fueron enviados al juzgado

---

## 5. Gestión de Oficios

### 5.1 Ver listado de oficios
- Andá a **Oficios** en el sidebar
- Podés **buscar** por número de oficio, nombre del paciente, DNI o juzgado
- Podés **filtrar** por estado: Todos / Pendiente / En curso / Cerrado

### 5.2 Registrar un nuevo oficio
1. Hacé clic en **+ Nuevo oficio**
2. Completá los campos:
   - **Número de oficio** (único, ej: OF-2024-001)
   - **Juzgado** (seleccioná de la lista)
   - **Paciente** (seleccioná de la lista)
   - **Fecha de recepción**
   - **Medio de recepción**: Papel / Email / WhatsApp
   - **Tipo de pedido** (ej: Informe pericial, Evaluación psicológica)
   - **Fecha de vencimiento** (opcional)
   - **Observaciones** (opcional)
3. Hacé clic en **Guardar**

### 5.3 Ver detalle de un oficio
Hacé clic en **Ver** en el listado. Vas a ver:
- Datos del oficio y del paciente
- Turno asignado (si tiene)
- Informe cargado (si tiene)
- Botones de acción según el estado

### 5.4 Importar oficios desde Excel
1. En el listado de oficios, hacé clic en **Importar Excel**
2. Descargá la **plantilla de ejemplo** si es la primera vez
3. Completá el archivo con las columnas: `numero_oficio`, `juzgado`, `dni_paciente`, `nombre_paciente`, `apellido_paciente`, `fecha_recepcion`, `medio_recepcion`, `tipo_pedido`
4. Subí el archivo y hacé clic en **Importar**

### 5.5 Estados de un oficio

| Estado | Significado |
|--------|-------------|
| **Pendiente** | Recibido, sin turno asignado |
| **En curso** | Tiene turno asignado |
| **Cerrado** | Tiene informe cargado |

---

## 6. Gestión de Turnos

### 6.1 Asignar un turno
1. Entrá al detalle del oficio (estado: Pendiente)
2. En la card **Turno**, hacé clic en **+ Asignar turno**
3. Seleccioná el **profesional**, la **fecha** y la **hora**
4. Hacé clic en **Guardar** — el oficio pasa a estado **En curso**

### 6.2 Registrar asistencia
1. Entrá al detalle del oficio
2. En la card del turno, registrá si el paciente **asistió** o **no asistió**
3. Si no asistió, podés ingresar el motivo

### 6.3 Editar o eliminar un turno
- **Editar**: Cambiá fecha, hora, profesional o estado
- **Eliminar**: El oficio vuelve a estado **Pendiente**

---

## 7. Gestión de Informes

### 7.1 Cargar un informe clínico
1. El oficio debe tener turno asignado
2. En la card **Informe**, hacé clic en **+ Cargar informe**
3. Completá: profesional, fecha, contenido del informe
4. Hacé clic en **Guardar** — el oficio pasa a estado **Cerrado**

### 7.2 Informe por inasistencia
Si el paciente no asistió al turno, el sistema te redirige automáticamente a cargar un informe de inasistencia.

### 7.3 Descargar informe en PDF
En la card del informe, hacé clic en el botón verde **PDF**. Se descarga el archivo con todos los datos del oficio, paciente y contenido del informe.

### 7.4 Marcar como enviado al juzgado
1. En la card del informe, hacé clic en **Marcar como enviado**
2. Confirmá la acción — se registra la fecha de envío automáticamente

### 7.5 Marcar como enviado a Dirección
Similar al punto anterior, para registrar el envío interno a la Dirección del hospital.

---

## 8. Gestión de Pacientes

### 8.1 Registrar un paciente
1. Andá a **Pacientes** → **+ Nuevo paciente**
2. Completá: nombre, apellido, DNI, fecha de nacimiento, teléfono, dirección
3. El **DNI** es único — no se pueden duplicar

### 8.2 Ver historial de un paciente
Hacé clic en **Ver** en el listado para ver todos los oficios asociados a ese paciente.

### 8.3 Importar pacientes desde Excel
1. En el listado de pacientes, hacé clic en **Importar Excel**
2. Usá las columnas: `nombre`, `apellido`, `dni`, `fecha_nacimiento`, `telefono`, `direccion`
3. Los DNI duplicados se omiten automáticamente

---

## 9. Gestión de Juzgados

- Registrá los juzgados con nombre, ciudad y contacto
- Desde **Est. Juzgados** podés ver estadísticas de oficios por juzgado, filtradas por año y mes
- Podés exportar las estadísticas en **Excel**, **PDF** o **Word**

---

## 10. Gestión de Profesionales

- Registrá los profesionales con nombre, apellido, especialidad y rol
- Roles disponibles: **Profesional**, **Admin**, **Dirección**

---

## 11. Asistente SIPSI (Chatbot)

El asistente está disponible en todas las páginas (botón flotante morado) y también como página completa en **Asistente** del sidebar.

### Consultas disponibles

| Pregunta | Respuesta |
|----------|-----------|
| `resumen general` | KPIs del sistema |
| `alertas` | Oficios vencidos, por vencer, turnos hoy |
| `oficios pendientes` | Cantidad de oficios sin turno |
| `turnos de hoy` | Lista de turnos del día |
| `turnos del 30/04` | Turnos asignados + disponibilidad para esa fecha |
| `próximos turnos` | Los 5 turnos pendientes más cercanos |
| `informes sin enviar` | Informes pendientes de envío |
| `cuántos pacientes hay` | Total de pacientes |
| `ayuda` | Lista completa de comandos |

### Micrófono
Hacé clic en el ícono del micrófono para dictar tu consulta por voz (requiere Chrome).

---

## 12. Modo oscuro

Hacé clic en el ícono de luna/sol en la parte superior del sidebar para cambiar entre modo claro y oscuro. La preferencia se guarda automáticamente.

---

## 13. Preguntas frecuentes

**¿Qué pasa si elimino un turno?**
El oficio vuelve a estado Pendiente. El informe (si existe) no se elimina.

**¿Qué pasa si elimino un informe?**
El oficio vuelve a estado En curso (si tiene turno) o Pendiente (si no tiene turno).

**¿Puedo tener dos oficios con el mismo número?**
No. El número de oficio es único en el sistema.

**¿Qué significa "Pendiente de envío" en el PDF?**
Que el informe fue generado pero todavía no se marcó como enviado al juzgado. Usá el botón "Marcar como enviado" en el detalle del oficio.
