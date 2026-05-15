# 🚀 Inicio Rápido - Chatbot con IA

## ⏱️ Configuración en 5 minutos

### Paso 1: Obtener API Key (2 minutos)

1. Abrí tu navegador y andá a: **https://console.groq.com**

2. Hacé clic en **"Sign Up"** (o "Get Started")

3. Registrate con:
   - 🔵 Google (recomendado - más rápido)
   - 🟣 GitHub
   - ✉️ Email

4. Una vez dentro, andá a: **https://console.groq.com/keys**

5. Hacé clic en **"Create API Key"**

6. Dale un nombre: `SIPSI Chatbot`

7. **Copiá la clave** (empieza con `gsk_...`)

   ⚠️ **IMPORTANTE:** Solo se muestra una vez, guardala bien!

### Paso 2: Configurar en SIPSI (1 minuto)

1. Abrí el archivo `.env` en la raíz del proyecto

2. Buscá esta línea:
   ```env
   GROQ_API_KEY=
   ```

3. Pegá tu clave:
   ```env
   GROQ_API_KEY=gsk_tu_clave_muy_larga_aqui
   ```

4. Guardá el archivo (Ctrl+S)

### Paso 3: Verificar (1 minuto)

Ejecutá el script de prueba:

```bash
php test_groq_api.php
```

**Resultado esperado:**
```
🔍 Probando conexión con Groq API...
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

✅ ¡Conexión exitosa!

📝 Respuesta de la IA:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[Respuesta inteligente sobre el SIPSI]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

🎉 El chatbot con IA está listo para usar!
```

### Paso 4: Reiniciar servidor (30 segundos)

Si el servidor ya está corriendo:

```bash
# Detener (Ctrl+C)
# Iniciar de nuevo
php artisan serve
```

### Paso 5: Probar (30 segundos)

1. Abrí el navegador: **http://localhost:8000/chatbot/page**

2. Deberías ver el badge **🤖 IA** en el header

3. Probá una consulta:
   ```
   ¿Qué es un oficio judicial?
   ```

4. Si recibís una respuesta inteligente: **¡Listo! 🎉**

## ✅ Checklist de Verificación

- [ ] Cuenta creada en Groq
- [ ] API Key copiada
- [ ] `.env` actualizado con la clave
- [ ] Script de prueba ejecutado exitosamente
- [ ] Servidor reiniciado
- [ ] Badge "🤖 IA" visible en el chatbot
- [ ] Consulta de prueba respondida correctamente

## 🎯 Primeras Consultas Recomendadas

Probá estas consultas para ver la IA en acción:

### Nivel 1: Conceptos básicos
```
¿Qué es el SIPSI?
```

### Nivel 2: Procedimientos
```
¿Cómo asigno un turno a un paciente?
```

### Nivel 3: Consejos
```
Dame consejos para organizar mejor los turnos
```

### Nivel 4: Análisis
```
¿Cuál es la situación actual del sistema?
```

## 🔧 Solución de Problemas Rápida

### ❌ "La IA no está configurada"

**Causa:** La API key no está en el `.env` o está mal copiada

**Solución:**
1. Abrí `.env`
2. Verificá que la línea sea exactamente:
   ```env
   GROQ_API_KEY=gsk_tu_clave_aqui
   ```
3. Sin espacios antes o después
4. Guardá y reiniciá el servidor

### ❌ "Error al conectar con la IA"

**Causa:** Problema de conexión o clave inválida

**Solución:**
1. Verificá tu internet
2. Andá a https://console.groq.com/keys
3. Verificá que la clave esté activa
4. Si no funciona, creá una nueva clave

### ❌ El script de prueba falla

**Causa:** Dependencias faltantes

**Solución:**
```bash
composer install
php test_groq_api.php
```

### ❌ No veo el badge "🤖 IA"

**Causa:** El servidor no se reinició

**Solución:**
1. Detené el servidor (Ctrl+C)
2. Iniciá de nuevo: `php artisan serve`
3. Refrescá el navegador (Ctrl+F5)

## 📊 Uso del Plan Gratuito

### Límites:
- ✅ **30 solicitudes/minuto** (más que suficiente)
- ✅ **14,400 solicitudes/día** (muy generoso)
- ✅ **Sin tarjeta de crédito**
- ✅ **Sin vencimiento**

### Estimación de uso:
- **Usuario promedio:** 10-20 consultas/día
- **Uso intensivo:** 50-100 consultas/día
- **Límite diario:** 14,400 consultas

**Conclusión:** El plan gratuito es más que suficiente para SIPSI.

## 🎨 Personalización Opcional

### Cambiar el modelo de IA

Editá `app/Services/GroqAIService.php`:

```php
// Línea 11
private string $model = 'llama-3.3-70b-versatile'; // Actual

// Opciones:
// 'llama-3.3-70b-versatile'  → Más inteligente (recomendado)
// 'llama-3.1-8b-instant'     → Más rápido
// 'mixtral-8x7b-32768'       → Mejor para textos largos
```

### Ajustar la temperatura

Editá `app/Services/GroqAIService.php`:

```php
// Línea 42
'temperature' => 0.7, // Actual (balanceado)

// Opciones:
// 0.3 → Más preciso y consistente
// 0.7 → Balanceado (recomendado)
// 1.0 → Más creativo y variado
```

### Cambiar el límite de tokens

Editá `app/Services/GroqAIService.php`:

```php
// Línea 43
'max_tokens' => 500, // Actual

// Opciones:
// 200  → Respuestas cortas
// 500  → Respuestas medianas (recomendado)
// 1000 → Respuestas largas
```

## 📚 Documentación Completa

- **Configuración detallada:** [CONFIGURACION_IA.md](CONFIGURACION_IA.md)
- **Ejemplos de consultas:** [EJEMPLOS_CONSULTAS_IA.md](EJEMPLOS_CONSULTAS_IA.md)
- **Resumen de cambios:** [CAMBIOS_IA.md](CAMBIOS_IA.md)

## 🆘 ¿Necesitás Ayuda?

### Recursos:
1. **Documentación de Groq:** https://console.groq.com/docs
2. **Logs del sistema:** `storage/logs/laravel.log`
3. **Script de prueba:** `php test_groq_api.php`

### Comandos útiles:
```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Limpiar caché
php artisan cache:clear
php artisan config:clear

# Verificar configuración
php artisan config:show

# Probar conexión
php test_groq_api.php
```

## 🎉 ¡Listo!

Si llegaste hasta acá y todo funciona:

**¡Felicitaciones! 🎊**

Tu chatbot ahora tiene inteligencia artificial y puede responder consultas en lenguaje natural.

### Próximos pasos:
1. Explorá las consultas de ejemplo
2. Probá diferentes tipos de preguntas
3. Compartí con tu equipo
4. Disfrutá de un chatbot más inteligente

---

**Tiempo total:** ~5 minutos  
**Costo:** $0 (100% gratis)  
**Dificultad:** ⭐⭐☆☆☆ (Fácil)
