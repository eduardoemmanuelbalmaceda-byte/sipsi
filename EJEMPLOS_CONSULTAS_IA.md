# 💬 Ejemplos de Consultas para el Chatbot con IA

## 🎯 Consultas que funcionan SIN IA (Reglas predefinidas)

Estas consultas se procesan rápidamente sin usar la API de Groq:

### Oficios
- "oficios pendientes"
- "oficios en curso"
- "oficios cerrados"
- "oficios de hoy"
- "oficios de esta semana"
- "oficios del mes"
- "cuántos oficios hay"

### Turnos
- "turnos de hoy"
- "turnos de mañana"
- "próximos turnos"
- "turnos de esta semana"
- "disponibilidad"
- "turnos del 30/04"
- "turnos del 25 de mayo"

### Informes
- "informes sin enviar"
- "informes pendientes"
- "informes enviados"
- "cuántos informes hay"

### Pacientes
- "cuántos pacientes hay"
- "pacientes registrados"

### Profesionales
- "cuántos profesionales hay"
- "profesionales registrados"

### Juzgados
- "cuántos juzgados hay"
- "juzgados registrados"

### Resúmenes
- "resumen general"
- "estado general"
- "cómo estamos"
- "panorama"

### Alertas
- "alertas"
- "urgentes"
- "vencidos"
- "por vencer"
- "crítico"

### Ayuda
- "ayuda"
- "qué podés hacer"
- "comandos"
- "opciones"

## 🤖 Consultas que REQUIEREN IA

Estas consultas solo funcionan si configuraste GROQ_API_KEY:

### Conceptuales
- "¿Qué es un oficio judicial?"
- "¿Qué es el SIPSI?"
- "¿Para qué sirve este sistema?"
- "Explicame cómo funciona el flujo de trabajo"
- "¿Qué diferencia hay entre un oficio y un informe?"
- "¿Qué significa que un oficio esté en curso?"
- "¿Qué es un turno psiquiátrico?"

### Consejos y Recomendaciones
- "Dame consejos para organizar mejor los turnos"
- "¿Cómo puedo gestionar mejor los oficios vencidos?"
- "¿Qué debo hacer cuando llega un oficio nuevo?"
- "Sugerencias para mejorar la eficiencia"
- "¿Cómo priorizo las tareas urgentes?"
- "Tips para no olvidar enviar informes"

### Análisis y Contexto
- "¿Cuál es la situación actual del sistema?"
- "¿Hay algo urgente que deba atender?"
- "Dame un resumen de lo más importante"
- "¿Qué tareas debería priorizar hoy?"
- "¿Cómo está la carga de trabajo?"

### Procedimientos
- "¿Cómo asigno un turno a un paciente?"
- "¿Cómo creo un informe?"
- "¿Qué pasos sigo para cerrar un oficio?"
- "¿Cómo registro la asistencia de un paciente?"
- "¿Cómo notifico a un juzgado?"

### Interpretación de Datos
- "¿Es normal tener 5 oficios vencidos?"
- "¿Cuántos turnos por día es lo ideal?"
- "¿Qué significa tener muchos informes sin enviar?"
- "¿Está bien la cantidad de oficios pendientes?"

### Preguntas Abiertas
- "¿Qué puedo hacer para mejorar el sistema?"
- "¿Hay algún problema que deba resolver?"
- "¿Qué me recomendás hacer ahora?"
- "¿Cómo optimizo mi trabajo diario?"

### Comparaciones
- "¿Qué es mejor, asignar turnos por orden de llegada o por urgencia?"
- "¿Cuál es la diferencia entre un informe de evaluación y uno de inasistencia?"
- "¿Qué profesional tiene más carga de trabajo?"

### Explicaciones Técnicas
- "¿Cómo funciona el sistema de notificaciones?"
- "¿Qué pasa cuando un paciente no asiste?"
- "¿Cómo se calcula la fecha de vencimiento?"
- "¿Qué es la fecha de recepción de un oficio?"

## 🎭 Ejemplos de Conversaciones Naturales

### Conversación 1: Usuario nuevo
```
Usuario: "Hola, soy nuevo en el sistema"
IA: "¡Bienvenido al SIPSI! Te explico brevemente..."

Usuario: "¿Qué es un oficio?"
IA: "Un oficio judicial es un documento oficial..."

Usuario: "¿Y cómo lo proceso?"
IA: "El flujo es el siguiente: 1) Recibís el oficio..."
```

### Conversación 2: Gestión diaria
```
Usuario: "¿Qué tengo que hacer hoy?"
IA: "Según el estado actual del sistema, te recomiendo..."

Usuario: "¿Cómo priorizo?"
IA: "Te sugiero este orden: 1) Oficios vencidos..."

Usuario: "Dame más detalles sobre los vencidos"
Bot: "🔴 5 oficios vencidos: [lista]" (respuesta con reglas)
```

### Conversación 3: Resolución de problemas
```
Usuario: "Tengo muchos oficios sin turno"
IA: "Entiendo tu preocupación. Algunas estrategias..."

Usuario: "¿Y si no hay profesionales disponibles?"
IA: "En ese caso, podés considerar..."

Usuario: "Mostrame la disponibilidad de mañana"
Bot: "📅 Disponibilidad del 16/05..." (respuesta con reglas)
```

## 🔄 Cómo Funciona el Sistema Híbrido

```
┌─────────────────────────────────────┐
│  Usuario envía mensaje              │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  ¿Coincide con regla predefinida?   │
└──────────────┬──────────────────────┘
               │
       ┌───────┴───────┐
       │               │
      SÍ              NO
       │               │
       ▼               ▼
┌─────────────┐  ┌──────────────────┐
│ Respuesta   │  │ ¿IA configurada? │
│ rápida      │  └────────┬─────────┘
│ (sin IA)    │           │
└─────────────┘   ┌───────┴───────┐
                  │               │
                 SÍ              NO
                  │               │
                  ▼               ▼
         ┌────────────────┐  ┌──────────────┐
         │ Consulta a IA  │  │ Mensaje de   │
         │ con contexto   │  │ ayuda + tip  │
         └────────────────┘  └──────────────┘
```

## 💡 Tips para Mejores Resultados

### ✅ Buenas Prácticas
- Sé específico en tus preguntas
- Usá lenguaje natural y conversacional
- Podés hacer preguntas de seguimiento
- No tengas miedo de reformular si no entendés

### ❌ Evitá
- Preguntas demasiado vagas ("ayuda")
- Múltiples preguntas en un solo mensaje
- Datos sensibles de pacientes (nombres, DNI, etc.)
- Consultas fuera del ámbito del SIPSI

## 🎯 Casos de Uso Reales

### Caso 1: Administrador nuevo
**Objetivo:** Aprender a usar el sistema

**Consultas útiles:**
1. "¿Qué es el SIPSI y para qué sirve?"
2. "Explicame el flujo de trabajo completo"
3. "¿Qué es lo primero que debo hacer cada día?"
4. "¿Cómo sé si algo es urgente?"

### Caso 2: Gestión de crisis
**Objetivo:** Resolver acumulación de trabajo

**Consultas útiles:**
1. "alertas" (ver qué está crítico)
2. "¿Cómo priorizo cuando tengo muchas tareas?"
3. "Dame estrategias para reducir oficios vencidos"
4. "¿Qué hago si no hay turnos disponibles?"

### Caso 3: Optimización
**Objetivo:** Mejorar eficiencia

**Consultas útiles:**
1. "resumen general" (ver estado actual)
2. "¿Cómo puedo optimizar la asignación de turnos?"
3. "¿Qué métricas debo monitorear?"
4. "Sugerencias para prevenir vencimientos"

### Caso 4: Capacitación
**Objetivo:** Entrenar a nuevo personal

**Consultas útiles:**
1. "¿Qué es un oficio judicial?"
2. "¿Cómo se asigna un turno?"
3. "¿Qué tipos de informes existen?"
4. "¿Cuándo debo notificar al juzgado?"

## 📊 Estadísticas de Uso

### Consultas más comunes (estimadas):
1. **Alertas/Resumen** (40%) - Usan reglas
2. **Turnos del día** (25%) - Usan reglas
3. **Oficios pendientes** (20%) - Usan reglas
4. **Consultas conceptuales** (10%) - Usan IA
5. **Consejos/Ayuda** (5%) - Usan IA

### Ahorro de tokens:
- El 85% de consultas se resuelven sin IA
- Solo el 15% requiere llamadas a Groq
- Esto mantiene el uso dentro del plan gratuito

## 🔮 Futuras Mejoras

### En desarrollo:
- [ ] Memoria de conversación (recordar contexto)
- [ ] Sugerencias proactivas basadas en alertas
- [ ] Análisis de sentimiento (detectar urgencia)

### Planeadas:
- [ ] Comandos de voz mejorados
- [ ] Integración con WhatsApp
- [ ] Resúmenes automáticos diarios
- [ ] Predicción de carga de trabajo

---

**¿Tenés más ideas de consultas útiles?** Agregá tus propios ejemplos a este archivo!
