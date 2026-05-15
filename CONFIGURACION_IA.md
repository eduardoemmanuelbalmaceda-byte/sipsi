# 🤖 Configuración de IA para el Chatbot SIPSI

El chatbot de SIPSI ahora puede interpretar consultas en lenguaje natural usando **Groq AI** (100% gratuito).

## 🎯 ¿Qué es Groq?

Groq es una plataforma que ofrece acceso **gratuito** a modelos de IA de última generación como:
- **Llama 3.3 70B** (el que usa SIPSI por defecto)
- Mixtral
- Gemma

**Ventajas:**
- ✅ Completamente gratuito
- ✅ Muy rápido (respuestas en menos de 1 segundo)
- ✅ Sin necesidad de tarjeta de crédito
- ✅ 30 solicitudes por minuto en el plan gratuito

## 📝 Cómo obtener tu API Key (5 minutos)

### Paso 1: Crear cuenta en Groq

1. Visitá: https://console.groq.com
2. Hacé clic en **"Sign Up"** o **"Get Started"**
3. Podés registrarte con:
   - Google
   - GitHub
   - Email

### Paso 2: Obtener la API Key

1. Una vez dentro, andá a: https://console.groq.com/keys
2. Hacé clic en **"Create API Key"**
3. Dale un nombre (ejemplo: "SIPSI Chatbot")
4. Copiá la clave que aparece (empieza con `gsk_...`)

⚠️ **IMPORTANTE:** Guardá la clave en un lugar seguro, solo se muestra una vez.

### Paso 3: Configurar en SIPSI

1. Abrí el archivo `.env` en la raíz del proyecto
2. Buscá la línea que dice `GROQ_API_KEY=`
3. Pegá tu clave después del `=`:

```env
GROQ_API_KEY=gsk_tu_clave_aqui_muy_larga
```

4. Guardá el archivo

### Paso 4: Reiniciar el servidor

Si estás usando `php artisan serve`, detené el servidor (Ctrl+C) y volvé a iniciarlo:

```bash
php artisan serve
```

## ✅ Verificar que funciona

1. Andá al chatbot: http://localhost:8000/chatbot/page
2. Probá una consulta que antes no funcionaba, por ejemplo:
   - "¿Qué es un oficio judicial?"
   - "Explicame cómo funciona el sistema"
   - "¿Para qué sirve el SIPSI?"
   - "Dame consejos para organizar mejor los turnos"

Si la IA está configurada correctamente, vas a recibir respuestas inteligentes y contextualizadas.

## 🔧 Solución de problemas

### "La IA no está configurada"

- Verificá que copiaste bien la API key en el `.env`
- Asegurate de que no haya espacios antes o después de la clave
- Reiniciá el servidor de Laravel

### "Error al conectar con la IA"

- Verificá tu conexión a internet
- Revisá que la API key sea válida en: https://console.groq.com/keys
- Mirá los logs en `storage/logs/laravel.log` para más detalles

### "Rate limit exceeded"

- El plan gratuito permite 30 solicitudes por minuto
- Esperá un minuto y volvé a intentar
- Si necesitás más, podés crear otra cuenta o esperar

## 🎨 Personalización

Si querés cambiar el modelo de IA o ajustar las respuestas, editá el archivo:

```
app/Services/GroqAIService.php
```

**Modelos disponibles:**
- `llama-3.3-70b-versatile` (por defecto, el más inteligente)
- `llama-3.1-8b-instant` (más rápido, menos preciso)
- `mixtral-8x7b-32768` (bueno para textos largos)

Para cambiar el modelo, modificá la línea:

```php
private string $model = 'llama-3.3-70b-versatile';
```

## 📊 Límites del plan gratuito

- **Solicitudes por minuto:** 30
- **Solicitudes por día:** 14,400
- **Tokens por minuto:** 7,000

Para un sistema como SIPSI, estos límites son más que suficientes.

## 🔒 Seguridad

- ⚠️ **NUNCA** compartas tu API key públicamente
- ⚠️ **NO** la subas a GitHub (el `.env` está en `.gitignore`)
- ⚠️ Si la clave se filtra, eliminala en https://console.groq.com/keys y creá una nueva

## 💡 Ejemplos de consultas que ahora funcionan

Con la IA activada, el chatbot puede responder:

**Consultas generales:**
- "¿Qué es el SIPSI?"
- "¿Cómo funciona el sistema de oficios?"
- "Explicame el flujo de trabajo"

**Consejos y recomendaciones:**
- "¿Cómo organizo mejor los turnos?"
- "Dame tips para gestionar oficios vencidos"
- "¿Qué debo hacer cuando llega un oficio nuevo?"

**Preguntas sobre el contexto:**
- "¿Cuál es la situación actual del sistema?"
- "¿Hay algo urgente que deba atender?"
- "Dame un resumen de lo más importante"

**Consultas técnicas:**
- "¿Qué significa que un oficio esté en curso?"
- "¿Cuál es la diferencia entre un informe y un oficio?"

## 🚀 Próximos pasos

Si querés mejorar aún más el chatbot, podés:

1. **Agregar memoria de conversación:** Que recuerde el contexto de mensajes anteriores
2. **Integrar con WhatsApp:** Usar la IA también en notificaciones
3. **Análisis de sentimiento:** Detectar urgencia en las consultas
4. **Sugerencias proactivas:** Que la IA sugiera acciones basadas en el estado del sistema

---

**¿Necesitás ayuda?** Revisá la documentación oficial de Groq: https://console.groq.com/docs
