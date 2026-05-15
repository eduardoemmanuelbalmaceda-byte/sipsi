# 🤖 Chatbot con Acciones - Guía Completa

El chatbot de SIPSI ahora puede **realizar acciones** además de responder consultas. Es un **agente autónomo** que puede gestionar turnos, oficios, informes y más.

---

## 🎯 ¿Qué puede hacer?

### ✅ TURNOS

#### 1. Asignar Turno
Crea un nuevo turno para un oficio.

**Ejemplos:**
```
asignar turno para el oficio 1239 con el Dr. Gomez para mañana a las 10hs
crear turno para oficio 1240 con profesional 2 para el 20/05 a las 15hs
dar turno al oficio 1241 con Gomez para hoy a las 11hs
```

**Parámetros:**
- **Oficio** (requerido): Número de oficio
- **Profesional** (requerido): Apellido o ID del profesional
- **Fecha** (opcional): Si no se especifica, usa mañana
  - "mañana", "hoy", "pasado mañana"
  - Formato: dd/mm o dd/mm/yyyy
- **Hora** (opcional): Si no se especifica, usa 10:00hs
  - Formato: "10hs", "10:00hs", "15:30hs"

**Respuesta:**
```
✅ Turno asignado exitosamente

📋 Oficio: #1239
👤 Paciente: González, María
👨‍⚕️ Profesional: Dr/a. Gomez, Jose
📅 Fecha: 16/05/2026
🕐 Hora: 10:00hs
🔗 Ver turno: http://sipsi.test/turnos/45
```

---

#### 2. Cancelar Turno
Elimina un turno y vuelve el oficio a estado "pendiente".

**Ejemplos:**
```
cancelar turno 45
eliminar turno 46
borrar el turno 47
```

**Respuesta:**
```
✅ Turno cancelado exitosamente

📋 Oficio: 1239
👤 Paciente: González, María
👨‍⚕️ Profesional: Dr/a. Gomez
📅 Fecha: 16/05/2026 10:00hs

⚠️ El oficio volvió a estado pendiente
```

---

#### 3. Modificar Turno
Cambia la fecha y/o hora de un turno existente.

**Ejemplos:**
```
modificar turno 45 para el 20/05 a las 15hs
cambiar turno 45 para mañana
reprogramar turno 45 para las 11hs
```

**Respuesta:**
```
✅ Turno modificado exitosamente

📋 Oficio: 1239
👤 Paciente: González, María
👨‍⚕️ Profesional: Dr/a. Gomez

📅 Nueva fecha: 20/05/2026
🕐 Nueva hora: 15:00hs
```

---

#### 4. Registrar Asistencia
Marca que el paciente asistió al turno.

**Ejemplos:**
```
registrar asistencia del turno 45
marcar asistencia turno 45
el paciente asistió al turno 45
presente en turno 45
```

**Respuesta:**
```
✅ Asistencia registrada

📋 Oficio: 1239
👤 Paciente: González, María
👨‍⚕️ Profesional: Dr/a. Gomez
📅 Fecha: 16/05/2026
```

---

#### 5. Registrar Inasistencia
Marca que el paciente NO asistió al turno.

**Ejemplos:**
```
registrar inasistencia del turno 45
marcar inasistencia turno 45
el paciente no asistió al turno 45
ausente en turno 45
faltó al turno 45
```

**Respuesta:**
```
❌ Inasistencia registrada

📋 Oficio: 1239
👤 Paciente: González, María
👨‍⚕️ Profesional: Dr/a. Gomez
📅 Fecha: 16/05/2026
```

---

### ✅ OFICIOS

#### 6. Cerrar Oficio
Cambia el estado del oficio a "cerrado".

**Ejemplos:**
```
cerrar oficio 1239
finalizar oficio 1239
completar oficio 1239
```

**Respuesta:**
```
✅ Oficio actualizado

📋 Oficio: 1239
👤 Paciente: González, María
📊 Nuevo estado: Cerrado
```

---

#### 7. Registrar Notificación
Marca que se notificó al paciente.

**Ejemplos:**
```
registrar notificación del oficio 1239
notificar paciente del oficio 1239
marcar notificación oficio 1239
```

**Respuesta:**
```
✅ Notificación registrada

📋 Oficio: 1239
👤 Paciente: González, María
📅 Fecha: 15/05/2026 19:45
```

---

### ✅ INFORMES

#### 8. Marcar Informe como Enviado
Registra que el informe fue enviado al juzgado.

**Ejemplos:**
```
marcar informe 12 como enviado
informe 12 enviado
enviar informe 12
```

**Respuesta:**
```
✅ Informe marcado como enviado

📋 Oficio: 1239
👤 Paciente: González, María
⚖️ Juzgado: Juzgado de Familia N°3
📅 Fecha de envío: 15/05/2026 19:45
```

---

## 🔄 Flujo de Trabajo Completo

### Ejemplo: Gestión completa de un oficio

```
1. Usuario: "resumen general"
   Bot: [Muestra estadísticas, incluyendo oficios pendientes]

2. Usuario: "asignar turno para el oficio 1239 con el Dr. Gomez para mañana a las 10hs"
   Bot: ✅ Turno asignado exitosamente...

3. [El paciente asiste al turno]

4. Usuario: "registrar asistencia del turno 45"
   Bot: ✅ Asistencia registrada...

5. [El profesional carga el informe en el sistema]

6. Usuario: "marcar informe 12 como enviado"
   Bot: ✅ Informe marcado como enviado...

7. Usuario: "cerrar oficio 1239"
   Bot: ✅ Oficio actualizado - Estado: Cerrado
```

---

## 💡 Tips y Trucos

### Lenguaje Natural
El chatbot entiende variaciones:
```
✅ "asignar turno para el oficio 1239"
✅ "crear turno para oficio 1239"
✅ "dar turno al oficio 1239"
✅ "agendar turno oficio 1239"
```

### Fechas Flexibles
```
✅ "mañana"
✅ "hoy"
✅ "pasado mañana"
✅ "20/05"
✅ "20/05/2026"
```

### Horas Flexibles
```
✅ "10hs"
✅ "10:00hs"
✅ "10:00"
✅ "15:30hs"
```

### Profesionales
Podés usar apellido o ID:
```
✅ "con el Dr. Gomez"
✅ "con Gomez"
✅ "con profesional 2"
```

---

## ⚠️ Validaciones

El chatbot valida automáticamente:

### Turnos:
- ✅ El oficio existe
- ✅ El oficio no está cerrado
- ✅ El profesional existe
- ✅ El profesional está disponible en ese horario
- ✅ La fecha es válida

### Oficios:
- ✅ El oficio existe
- ✅ El estado es válido

### Informes:
- ✅ El informe existe
- ✅ No está ya marcado como enviado

---

## 🚫 Errores Comunes

### "No pude identificar el número de oficio"
**Causa:** No especificaste el número de oficio.

**Solución:**
```
❌ "asignar turno con Gomez"
✅ "asignar turno para el oficio 1239 con Gomez"
```

---

### "No encontré el oficio #1239"
**Causa:** El oficio no existe en la base de datos.

**Solución:** Verificá el número de oficio correcto.

---

### "No especificaste el profesional"
**Causa:** No indicaste con qué profesional es el turno.

**Solución:**
```
❌ "asignar turno para el oficio 1239 mañana"
✅ "asignar turno para el oficio 1239 con Gomez mañana"
```

El bot te mostrará la lista de profesionales disponibles.

---

### "El Dr/a. Gomez ya tiene un turno asignado..."
**Causa:** El profesional ya tiene un turno en ese horario.

**Solución:** Elegí otro horario o profesional.

---

## 🔒 Seguridad

### Permisos
Actualmente, cualquier usuario autenticado puede realizar acciones. En el futuro se pueden agregar restricciones por rol.

### Auditoría
Todas las acciones quedan registradas en:
- Base de datos (timestamps de creación/modificación)
- Logs del sistema (`storage/logs/laravel.log`)

### Acciones Críticas
Para acciones como eliminar o modificar, el sistema valida que:
- El registro existe
- El usuario está autenticado
- Los datos son válidos

---

## 🎯 Casos de Uso Reales

### Caso 1: Asignación rápida de turnos
```
Usuario: "alertas"
Bot: [Muestra 3 oficios sin turno hace +15 días]

Usuario: "asignar turno para el oficio 1239 con Gomez para mañana a las 10hs"
Bot: ✅ Turno asignado...

Usuario: "asignar turno para el oficio 1240 con Gomez para mañana a las 11hs"
Bot: ✅ Turno asignado...
```

### Caso 2: Gestión de asistencias
```
Usuario: "turnos de hoy"
Bot: [Muestra 5 turnos pendientes]

Usuario: "registrar asistencia del turno 45"
Bot: ✅ Asistencia registrada...

Usuario: "registrar inasistencia del turno 46"
Bot: ❌ Inasistencia registrada...
```

### Caso 3: Cierre de oficios
```
Usuario: "informes sin enviar"
Bot: [Muestra 3 informes pendientes]

Usuario: "marcar informe 12 como enviado"
Bot: ✅ Informe marcado como enviado...

Usuario: "cerrar oficio 1239"
Bot: ✅ Oficio actualizado - Estado: Cerrado
```

---

## 🔮 Próximas Mejoras

### En desarrollo:
- [ ] Confirmaciones para acciones críticas
- [ ] Deshacer última acción
- [ ] Historial de acciones del usuario
- [ ] Sugerencias inteligentes basadas en contexto

### Planeadas:
- [ ] Crear oficios completos desde el chat
- [ ] Crear pacientes desde el chat
- [ ] Generar informes desde el chat
- [ ] Enviar notificaciones WhatsApp
- [ ] Exportar reportes

---

## 📊 Estadísticas

### Acciones más usadas (estimadas):
1. **Asignar turno** (40%)
2. **Registrar asistencia** (25%)
3. **Marcar informe enviado** (15%)
4. **Modificar turno** (10%)
5. **Cancelar turno** (5%)
6. **Otras** (5%)

---

## 🆘 Soporte

Si tenés problemas:
1. Escribí "ayuda" en el chat para ver ejemplos
2. Revisá esta documentación
3. Mirá los logs en `storage/logs/laravel.log`
4. Usá el formulario tradicional si el chatbot no puede ayudarte

---

**¿Tenés ideas para nuevas acciones?** ¡Sugerí mejoras!
