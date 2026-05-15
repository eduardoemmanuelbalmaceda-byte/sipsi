# Manual de Procesos — SIPSI
## Sistema Integral de Psiquiatría Hospitalaria
**Hospital Dr. César Aguilar — San Juan**
**Versión 1.1 — Mayo 2026**

---

## 1. Descripción del sistema

SIPSI gestiona el flujo completo de oficios judiciales recibidos por el Servicio de Psiquiatría del Hospital Dr. César Aguilar. El sistema digitaliza y centraliza el proceso que antes se manejaba en planillas físicas y Excel, permitiendo trazabilidad completa desde la recepción del oficio hasta la confirmación de recepción por parte del juzgado.

### Tecnologías utilizadas
- **Backend**: Laravel 13 (PHP 8.3)
- **Base de datos**: MySQL (MariaDB 10.4)
- **Frontend**: Blade + Bootstrap + CSS personalizado
- **Servidor local**: Laravel Herd (NGINX + PHP-FPM)
- **Autenticación**: Laravel Breeze
- **PDF**: barryvdh/laravel-dompdf v3.1
- **Excel**: maatwebsite/excel v3.1
- **Markdown**: erusev/parsedown v1.8

---

## 2. Flujo principal del sistema

```
JUZGADO
   │
   │ Envía oficio (papel / email / WhatsApp)
   ▼
SECRETARÍA — Registra el oficio en SIPSI
   │           (estado: PENDIENTE)
   │
   │ Asigna turno al profesional
   ▼
PACIENTE — Asiste o no asiste al turno
   │         (estado: EN CURSO)
   │
   │ Profesional redacta y carga el informe
   ▼
INFORME GENERADO — Se descarga PDF
   │                (estado: CERRADO)
   │
   ├──► Se envía al juzgado → Marcar como enviado
   ├──► Se envía a Dirección → Marcar como enviado a Dirección
   └──► Juzgado confirma recepción → Confirmar recepción
```

---

## 3. Procesos detallados

### 3.1 Recepción de oficio judicial

**Responsable**: Secretaría / Administrativo

**Pasos**:
1. El juzgado envía el oficio por papel, email o WhatsApp
2. El administrativo ingresa a SIPSI → **Oficios** → **+ Nuevo oficio**
3. Registra:
   - Número de oficio (único, asignado por el juzgado)
   - Juzgado remitente
   - Paciente (si no existe, se crea en el momento)
   - Fecha de recepción
   - Medio de recepción
   - Tipo de pedido
   - Fecha de vencimiento (si el juzgado la indica)
4. El oficio queda en estado **Pendiente**

**Validaciones del sistema**:
- El número de oficio no puede repetirse
- El juzgado y el paciente deben existir en el sistema
- La fecha de recepción es obligatoria

---

### 3.2 Asignación de turno

**Responsable**: Secretaría / Coordinador

**Pasos**:
1. Desde el detalle del oficio (estado: Pendiente)
2. Hacé clic en **+ Asignar turno**
3. Seleccioná el profesional disponible, fecha y hora
4. El sistema actualiza el oficio a estado **En curso**

**Reglas de negocio**:
- Solo se puede asignar un turno por oficio
- Si se elimina el turno, el oficio vuelve a Pendiente
- El sistema considera hasta 8 turnos por profesional por día como capacidad máxima

**Alertas automáticas**:
- El dashboard muestra oficios pendientes sin turno hace más de 15 días
- El chatbot puede consultar disponibilidad por fecha y profesional

---

### 3.3 Registro de asistencia

**Responsable**: Profesional / Secretaría

**Pasos**:
1. Desde el detalle del oficio, en la card del turno
2. Registrar si el paciente **asistió** o **no asistió**
3. Si no asistió, ingresar el motivo

**Consecuencias**:
- Si asistió → el turno pasa a estado **Realizado** → se habilita cargar informe clínico
- Si no asistió → el turno pasa a estado **Ausente** → el sistema redirige a cargar informe de inasistencia

---

### 3.4 Carga del informe pericial

**Responsable**: Profesional interviniente

**Tipos de informe**:
- **Clínico**: Resultado de la evaluación psicológica/psiquiátrica
- **Inasistencia**: Constancia de que el paciente no se presentó

**Pasos**:
1. Desde el detalle del oficio (estado: En curso, con turno realizado)
2. Hacé clic en **+ Cargar informe**
3. Completá: profesional, fecha del informe, contenido
4. El sistema actualiza el oficio a estado **Cerrado**

**Validaciones**:
- El contenido del informe es obligatorio
- El profesional debe estar registrado en el sistema
- Si se elimina el informe, el oficio vuelve a En curso

---

### 3.5 Generación y envío del informe

**Responsable**: Secretaría / Profesional

**Pasos**:
1. Desde el detalle del oficio, en la card del informe
2. Hacé clic en **PDF** para descargar el informe en formato A4
3. Enviarlo al juzgado por el medio acordado (email, papel)
4. Volver al sistema y hacer clic en **Marcar como enviado**
5. El sistema registra la fecha de envío automáticamente

**Envío a Dirección**:
- Si el informe también debe enviarse a la Dirección del hospital, usar **Marcar como enviado a Dirección**

**Alertas automáticas**:
- El dashboard muestra informes sin enviar
- El chatbot alerta sobre informes con más de 7 días sin enviar

---

### 3.6 Notificación y confirmación del juzgado

**Responsable**: Secretaría

**Registrar notificación**:
1. Desde el detalle del oficio, registrar quién fue notificado:
   - **Dirección**: La dirección del hospital fue notificada
   - **Juzgado**: El juzgado fue notificado directamente
   - **Conflicto**: Se registró un conflicto en la notificación

**Confirmar recepción del juzgado**:
1. Una vez que el juzgado confirma haber recibido el informe
2. Hacé clic en **Confirmar recepción del juzgado**
3. El sistema registra la fecha de confirmación automáticamente

---

### 3.7 Seguimiento de vencimientos

**Responsable**: Coordinador / Secretaría

El sistema monitorea automáticamente:

| Alerta | Condición |
|--------|-----------|
| 🔴 Oficio vencido | `fecha_vencimiento` < hoy, estado no cerrado |
| 🟡 Por vencer | `fecha_vencimiento` dentro de 7 días |
| ⏳ Sin turno | Pendiente hace más de 15 días sin turno |
| 📄 Informe sin enviar | Informe con más de 7 días sin enviar al juzgado |
| 📅 Turnos hoy | Turnos pendientes para el día actual |

Estas alertas aparecen en:
- El **Dashboard** (panel de alertas)
- El **Asistente** (al abrir el chat o escribir "alertas")

---

### 3.8 Generación de estadísticas

**Responsable**: Dirección / Coordinador

1. Andá a **Est. Juzgados** en el sidebar
2. Filtrá por **año** y **rango de meses**
3. El sistema muestra la tabla de oficios por juzgado con totales y porcentajes
4. Exportá en **Excel**, **PDF** o **Word** para presentar a la Dirección

---

## 4. Estructura de la base de datos

### Tablas principales

| Tabla | Descripción | Campos clave |
|-------|-------------|--------------|
| `users` | Usuarios del sistema | name, email, password |
| `profesionales` | Profesionales del servicio | user_id, nombre, apellido, especialidad, rol |
| `pacientes` | Pacientes evaluados | nombre, apellido, dni, fecha_nacimiento, telefono, direccion |
| `juzgados` | Juzgados remitentes | nombre, ciudad, contacto |
| `oficios` | Oficios judiciales | numero_oficio, juzgado_id, paciente_id, fecha_recepcion, medio_recepcion, tipo_pedido, estado, fecha_vencimiento, notificado_por, confirmacion_juzgado |
| `turnos` | Turnos asignados | oficio_id, profesional_id, fecha_turno, hora, estado, asistencia, motivo_inasistencia |
| `informes` | Informes periciales | oficio_id, profesional_id, tipo, contenido, fecha_informe, enviado_juzgado, enviado_direccion, fecha_envio, fecha_envio_direccion |

### Relaciones

```
juzgados ──┐
           ├──► oficios ──► turnos ──► profesionales
pacientes ─┘         └──► informes ──► profesionales
```

### Estados del oficio

```
PENDIENTE ──► EN CURSO ──► CERRADO
    ▲              │
    └──────────────┘ (si se elimina el turno o el informe)
```

---

## 5. Roles de usuario

| Rol | Descripción |
|-----|-------------|
| **Profesional** | Profesional del servicio de psiquiatría |
| **Admin** | Administrador con acceso completo |
| **Dirección** | Dirección del hospital |

> Nota: Los roles están definidos en la base de datos. El control de acceso por rol está pendiente de implementación completa.

---

## 6. Importación masiva de datos

### Importar pacientes
- Formato: Excel (.xlsx, .xls) o CSV
- Columnas requeridas: `nombre`, `apellido`, `dni`
- Columnas opcionales: `fecha_nacimiento`, `telefono`, `direccion`
- Los DNI duplicados se omiten automáticamente

### Importar oficios
- Formato: Excel (.xlsx, .xls) o CSV
- Columnas requeridas: `numero_oficio`, `juzgado`, `dni_paciente`
- Si el paciente o juzgado no existe, se crea automáticamente
- Los números de oficio duplicados se omiten

---

## 7. Módulo de Ayuda

El sistema incluye un centro de ayuda accesible desde el sidebar (**Ayuda**) que muestra:
- **Manual de Usuario**: Guía paso a paso para usuarios finales
- **Manual de Procesos**: Documentación técnica del sistema

Los manuales se almacenan como archivos Markdown en la raíz del proyecto (`MANUAL_USUARIO.md` y `MANUAL_PROCESOS.md`) y se renderizan en HTML usando la librería `erusev/parsedown`. Cada manual tiene opción de impresión.

---

## 8. Mantenimiento del sistema

### Actualizar el código desde GitHub
```bash
cd C:\laragon\www\sistema-oficios
git pull origin main
php artisan migrate
npm run build
```

### Backup de la base de datos
Desde HeidiSQL → seleccionar `sispsi` → Herramientas → Exportar SQL → guardar como `sispsi_backup_FECHA.sql`

### Servidor
- **Herd** debe estar activo para que el sitio funcione
- URL: `http://sistema-oficios.test`
- PHP: 8.3
- Base de datos: MySQL en `127.0.0.1:3306`, base `sispsi`

### Compatibilidad de base de datos
El sistema usa el helper `App\Helpers\DbHelper` para detectar automáticamente si la BD es MySQL o SQLite y usar las funciones de fecha correctas en cada caso. Esto permite que el proyecto funcione en ambos entornos sin cambios de código.

---

## 9. Glosario

| Término | Definición |
|---------|------------|
| **Oficio judicial** | Documento enviado por un juzgado solicitando una evaluación psiquiátrica o psicológica |
| **Informe pericial** | Documento técnico elaborado por el profesional como respuesta al oficio |
| **Informe de inasistencia** | Constancia de que el paciente no se presentó al turno asignado |
| **Turno** | Cita asignada al paciente para la evaluación |
| **Estado pendiente** | Oficio recibido sin turno asignado |
| **Estado en curso** | Oficio con turno asignado, evaluación en proceso |
| **Estado cerrado** | Oficio con informe cargado |
| **Inasistencia** | Cuando el paciente no se presenta al turno asignado |
| **Notificación** | Registro de a quién se notificó sobre el oficio |
| **Confirmación del juzgado** | Registro de que el juzgado confirmó haber recibido el informe |
