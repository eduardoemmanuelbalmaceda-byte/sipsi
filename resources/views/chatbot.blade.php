@extends('layouts.app')
@section('content')

<style>
    /* ── Pantalla completa del chatbot ── */
    .chatbot-page {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 3.5rem);
        max-width: 780px;
        margin: 0 auto;
    }

    /* ── Header ── */
    .chatbot-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: var(--surface);
        border-radius: 0.85rem 0.85rem 0 0;
        border-bottom: 1px solid var(--border);
        box-shadow: 0 1px 6px var(--shadow);
    }
    .chatbot-avatar {
        width: 46px; height: 46px; border-radius: 50%;
        background: linear-gradient(135deg, var(--lavender), var(--indigo));
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(107,95,181,0.35);
    }
    .chatbot-header-info { flex: 1; }
    .chatbot-header-name { font-size: 1.1rem; font-weight: 700; color: var(--text); }
    .chatbot-header-status {
        font-size: 0.75rem; color: var(--mint);
        display: flex; align-items: center; gap: 0.35rem; margin-top: 2px;
    }
    .status-dot-lg {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--mint);
        animation: pulse-status 2s infinite;
    }
    @keyframes pulse-status {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }

    /* ── Mensajes ── */
    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        background: var(--bg);
        scroll-behavior: smooth;
    }
    .chatbot-messages::-webkit-scrollbar { width: 5px; }
    .chatbot-messages::-webkit-scrollbar-track { background: transparent; }
    .chatbot-messages::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

    .cmsg {
        max-width: 72%;
        padding: 0.7rem 1rem;
        border-radius: 1.1rem;
        font-size: 0.9rem;
        line-height: 1.55;
        white-space: pre-wrap;
        word-break: break-word;
        animation: cmsgIn 0.2s ease;
    }
    @keyframes cmsgIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .cmsg.bot {
        background: var(--surface);
        color: var(--text);
        border-bottom-left-radius: 4px;
        align-self: flex-start;
        box-shadow: 0 1px 4px var(--shadow);
    }
    .cmsg.user {
        background: linear-gradient(135deg, var(--lavender), var(--indigo));
        color: white;
        border-bottom-right-radius: 4px;
        align-self: flex-end;
        box-shadow: 0 2px 10px rgba(107,95,181,0.3);
    }
    .cmsg-typing {
        display: flex; gap: 5px; align-items: center;
        padding: 0.75rem 1rem;
        background: var(--surface);
        border-radius: 1.1rem; border-bottom-left-radius: 4px;
        align-self: flex-start;
        box-shadow: 0 1px 4px var(--shadow);
    }
    .cmsg-typing span {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--text-muted);
        animation: cbounce 1.2s infinite;
    }
    .cmsg-typing span:nth-child(2) { animation-delay: 0.2s; }
    .cmsg-typing span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes cbounce {
        0%,60%,100% { transform: translateY(0); }
        30%          { transform: translateY(-6px); }
    }

    /* ── Alertas en chat ── */
    .cmsg-alerta {
        align-self: flex-start;
        max-width: 85%;
        border-radius: 0.85rem;
        border-bottom-left-radius: 4px;
        padding: 0.7rem 1rem;
        font-size: 0.875rem;
        line-height: 1.5;
        animation: cmsgIn 0.2s ease;
        box-shadow: 0 1px 4px var(--shadow);
    }
    .cmsg-alerta.critico     { background: #fef2f2; border-left: 3px solid #ef4444; color: #7f1d1d; }
    .cmsg-alerta.advertencia { background: #fffbeb; border-left: 3px solid #f59e0b; color: #78350f; }
    .cmsg-alerta.info        { background: #eff6ff; border-left: 3px solid #3b82f6; color: #1e3a5f; }
    .cmsg-alerta .alerta-titulo  { font-weight: 700; margin-bottom: 0.3rem; }
    .cmsg-alerta .alerta-item    { margin: 0.2rem 0 0 0.5rem; }
    .cmsg-alerta .alerta-detalle { font-size: 0.78rem; opacity: 0.75; margin-left: 0.9rem; }

    /* ── Sugerencias ── */
    .chatbot-suggestions {
        padding: 0.75rem 1.5rem 0.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        background: var(--bg);
        border-top: 1px solid var(--border);
    }
    .csug-btn {
        font-size: 0.78rem; padding: 0.35rem 0.8rem;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        background: var(--surface);
        color: var(--text-soft);
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .csug-btn:hover {
        background: var(--lavender); color: white; border-color: var(--lavender);
    }

    /* ── Input ── */
    .chatbot-input-row {
        padding: 0.85rem 1.5rem 1rem;
        display: flex;
        gap: 0.6rem;
        align-items: center;
        background: var(--surface);
        border-radius: 0 0 0.85rem 0.85rem;
        border-top: 1px solid var(--border);
        box-shadow: 0 -1px 6px var(--shadow);
    }
    .chatbot-input {
        flex: 1;
        padding: 0.65rem 1.1rem;
        border: 1.5px solid var(--input-border);
        border-radius: 2rem;
        font-size: 0.9rem;
        background: var(--input-bg);
        color: var(--text);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .chatbot-input:focus {
        border-color: var(--lavender);
        box-shadow: 0 0 0 3px var(--input-focus);
        background: var(--surface);
    }
    .chatbot-input::placeholder { color: var(--text-muted); }

    .chatbot-mic {
        width: 42px; height: 42px; border-radius: 50%;
        background: var(--surface2);
        border: 1.5px solid var(--border);
        cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); transition: all 0.15s;
    }
    .chatbot-mic:hover { background: var(--lavender); color: white; border-color: var(--lavender); }
    .chatbot-mic.recording {
        background: #ef4444; border-color: #ef4444; color: white;
        animation: pulse-mic 1s infinite;
    }
    @keyframes pulse-mic {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
        50%       { box-shadow: 0 0 0 8px rgba(239,68,68,0); }
    }

    .chatbot-send {
        width: 42px; height: 42px; border-radius: 50%;
        background: linear-gradient(135deg, var(--lavender), var(--indigo));
        border: none; cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: white;
        box-shadow: 0 3px 12px rgba(107,95,181,0.4);
        transition: transform 0.15s, opacity 0.15s;
    }
    .chatbot-send:hover { transform: scale(1.08); }
    .chatbot-send:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
</style>

<div class="chatbot-page">

    {{-- Header --}}
    <div class="chatbot-header">
        <div class="chatbot-avatar">
            <svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>
        <div class="chatbot-header-info">
            <div class="chatbot-header-name">Asistente SIPSI</div>
            <div class="chatbot-header-status">
                <div class="status-dot-lg"></div>
                En línea — listo para ayudarte
            </div>
        </div>
    </div>

    {{-- Mensajes --}}
    <div class="chatbot-messages" id="cbMessages"></div>

    {{-- Sugerencias --}}
    <div class="chatbot-suggestions" id="cbSuggestions">
        <button class="csug-btn" onclick="cbEnviarSug(this)">Alertas</button>
        <button class="csug-btn" onclick="cbEnviarSug(this)">Resumen general</button>
        <button class="csug-btn" onclick="cbEnviarSug(this)">Oficios pendientes</button>
        <button class="csug-btn" onclick="cbEnviarSug(this)">Próximos turnos</button>
        <button class="csug-btn" onclick="cbEnviarSug(this)">Informes sin enviar</button>
        <button class="csug-btn" onclick="cbEnviarSug(this)">Turnos de hoy</button>
        <button class="csug-btn" onclick="cbEnviarSug(this)">Ayuda</button>
    </div>

    {{-- Input --}}
    <div class="chatbot-input-row">
        <input type="text" class="chatbot-input" id="cbInput"
               placeholder="Escribí tu consulta..."
               onkeydown="if(event.key==='Enter') cbEnviar()">
        <button class="chatbot-mic" id="cbMicBtn" onclick="cbToggleMic()" title="Hablar">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 10v2a7 7 0 01-14 0v-2M12 19v4M8 23h8"/>
            </svg>
        </button>
        <button class="chatbot-send" id="cbSendBtn" onclick="cbEnviar()">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </button>
    </div>
</div>

<script>
(function() {
    // ── Helpers ──
    function appendMsg(el) {
        const c = document.getElementById('cbMessages');
        c.appendChild(el);
        c.scrollTop = c.scrollHeight;
    }

    function addBot(texto) {
        const el = document.createElement('div');
        el.className = 'cmsg bot';
        el.innerHTML = texto
            .replace(/\*(.*?)\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
        appendMsg(el);
    }

    function addUser(texto) {
        const el = document.createElement('div');
        el.className = 'cmsg user';
        el.textContent = texto;
        appendMsg(el);
    }

    function addTyping() {
        const el = document.createElement('div');
        el.className = 'cmsg-typing';
        el.innerHTML = '<span></span><span></span><span></span>';
        appendMsg(el);
        return el;
    }

    // ── Cargar alertas al inicio ──
    async function cargarAlertas() {
        try {
            const res  = await fetch('{{ route("chatbot.alertas") }}');
            const data = await res.json();
            if (data.total > 0) {
                data.alertas.forEach(a => {
                    const el = document.createElement('div');
                    el.className = `cmsg-alerta ${a.nivel}`;
                    let html = `<div class="alerta-titulo">${a.icono} ${a.titulo}</div>`;
                    a.items.slice(0, 3).forEach(i => {
                        html += `<div class="alerta-item">• ${i.texto}</div>`;
                        html += `<div class="alerta-detalle">${i.detalle}</div>`;
                    });
                    if (a.items.length > 3) {
                        html += `<div class="alerta-detalle" style="margin-top:4px;">...y ${a.items.length - 3} más</div>`;
                    }
                    el.innerHTML = html;
                    appendMsg(el);
                });
            }
        } catch(e) {}
    }

    // ── Enviar mensaje ──
    window.cbEnviar = async function() {
        const input = document.getElementById('cbInput');
        const texto = input.value.trim();
        if (!texto) return;

        input.value = '';
        document.getElementById('cbSendBtn').disabled = true;

        addUser(texto);
        const typing = addTyping();

        try {
            const res  = await fetch('{{ route("chatbot.responder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ mensaje: texto })
            });
            const data = await res.json();
            typing.remove();
            addBot(data.respuesta);
        } catch(e) {
            typing.remove();
            addBot('Ocurrió un error al procesar tu consulta. Intentá de nuevo.');
        }

        document.getElementById('cbSendBtn').disabled = false;
        input.focus();
    };

    window.cbEnviarSug = function(btn) {
        document.getElementById('cbInput').value = btn.textContent;
        cbEnviar();
    };

    // ── Micrófono ──
    let recognition = null;
    window.cbToggleMic = function() {
        const btn = document.getElementById('cbMicBtn');
        if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
            addBot('Tu navegador no soporta reconocimiento de voz.');
            return;
        }
        if (recognition) {
            recognition.stop();
            recognition = null;
            btn.classList.remove('recording');
            return;
        }
        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SR();
        recognition.lang = 'es-AR';
        recognition.interimResults = false;
        recognition.onresult = e => {
            document.getElementById('cbInput').value = e.results[0][0].transcript;
            cbEnviar();
        };
        recognition.onend = () => {
            btn.classList.remove('recording');
            recognition = null;
        };
        recognition.start();
        btn.classList.add('recording');
    };

    // ── Inicio ──
    addBot('¡Hola, {{ auth()->user()->name }}! 👋 Soy el asistente de SIPSI.\n\nPodés preguntarme sobre oficios, turnos, pacientes, informes o profesionales.\n\nEscribí *ayuda* para ver todas las opciones.');
    cargarAlertas();
    document.getElementById('cbInput').focus();
})();
</script>

@endsection
