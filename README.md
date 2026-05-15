# SIPSI - Sistema de Información Psiquiátrica Judicial

Sistema de gestión para oficios judiciales, pacientes, turnos e informes psiquiátricos.

## 🚀 Características principales

- 📋 **Gestión de Oficios Judiciales:** Control completo de oficios recibidos de juzgados
- 👥 **Registro de Pacientes:** Base de datos de pacientes con historial
- 📅 **Sistema de Turnos:** Agenda de citas con profesionales
- 📄 **Generación de Informes:** Creación y envío de informes a juzgados
- 👨‍⚕️ **Gestión de Profesionales:** Registro de psiquiatras y médicos
- 🤖 **Chatbot con IA:** Asistente virtual inteligente (Groq AI - 100% gratuito)
- ⚡ **Chatbot con Acciones:** Asignar turnos, registrar asistencias, y más desde el chat
- 📊 **Dashboard con Estadísticas:** Visualización de métricas y alertas
- 📱 **Notificaciones WhatsApp:** Integración con WhatsApp Business API

## 🤖 Chatbot con Inteligencia Artificial

El sistema incluye un chatbot que puede **responder consultas** y **realizar acciones** usando **Groq AI** (completamente gratuito).

### Consultas:
- "¿Qué es un oficio judicial?"
- "Dame consejos para organizar mejor los turnos"
- "¿Cuál es la situación actual?"

### Acciones:
- "Asignar turno para el oficio 1239 con el Dr. Gomez para mañana a las 10hs"
- "Registrar asistencia del turno 45"
- "Marcar informe 12 como enviado"
- "Cancelar turno 46"

### Configuración rápida (5 minutos):

1. Obtené tu API key gratis en: https://console.groq.com/keys
2. Agregala al archivo `.env`:
   ```env
   GROQ_API_KEY=tu_clave_aqui
   ```
3. Reiniciá el servidor

📖 **Guías completas:** 
- [CONFIGURACION_IA.md](CONFIGURACION_IA.md) - Configuración de IA
- [CHATBOT_ACCIONES.md](CHATBOT_ACCIONES.md) - Guía de acciones

## 📦 Instalación

```bash
# Clonar el repositorio
git clone [url-del-repo]
cd sipsi

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos (SQLite por defecto)
touch database/database.sqlite
php artisan migrate

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

## 🔧 Configuración

### Base de datos
El sistema usa SQLite por defecto. Para usar MySQL/PostgreSQL, editá el `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipsi
DB_USERNAME=root
DB_PASSWORD=
```

### Chatbot con IA (Opcional pero recomendado)
```env
GROQ_API_KEY=tu_clave_de_groq
```

### WhatsApp (Opcional)
```env
WHATSAPP_API_URL=https://api.whatsapp.com
WHATSAPP_TOKEN=tu_token
```

## 📚 Documentación adicional

- [MANUAL_USUARIO.md](MANUAL_USUARIO.md) - Guía de uso del sistema
- [MANUAL_PROCESOS.md](MANUAL_PROCESOS.md) - Procesos y flujos de trabajo
- [CONFIGURACION_IA.md](CONFIGURACION_IA.md) - Configuración del chatbot con IA
- [CHATBOT_ACCIONES.md](CHATBOT_ACCIONES.md) - Guía de acciones del chatbot
- [EJEMPLOS_CONSULTAS_IA.md](EJEMPLOS_CONSULTAS_IA.md) - Ejemplos de consultas
- [README_WHATSAPP.md](README_WHATSAPP.md) - Integración con WhatsApp

## 🛠️ Tecnologías

- **Backend:** Laravel 13 (PHP 8.3)
- **Frontend:** Blade Templates + Alpine.js
- **Base de datos:** SQLite / MySQL / PostgreSQL
- **IA:** Groq API (Llama 3.3 70B)
- **Exportación:** Excel, PDF, Word
- **Estilos:** CSS personalizado con variables

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

