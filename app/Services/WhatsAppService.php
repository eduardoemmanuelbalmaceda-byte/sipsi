<?php

namespace App\Services;

use App\Models\Turno;
use Carbon\Carbon;

class WhatsAppService
{
    /**
     * Generar enlace de WhatsApp Web para enviar mensaje
     * 100% GRATIS - No requiere API ni costos
     */
    public function generarEnlaceWhatsApp(Turno $turno)
    {
        $paciente = $turno->oficio->paciente;
        $telefono = $this->formatearTelefono($paciente->telefono);

        if (!$telefono) {
            return null;
        }

        $mensaje = $this->generarMensajeTurno($turno);
        
        // Codificar mensaje para URL
        $mensajeCodificado = urlencode($mensaje);
        
        // Generar enlace de WhatsApp Web
        // Funciona en desktop y mobile
        return "https://wa.me/{$telefono}?text={$mensajeCodificado}";
    }

    /**
     * Generar enlace de recordatorio
     */
    public function generarEnlaceRecordatorio(Turno $turno)
    {
        $paciente = $turno->oficio->paciente;
        $telefono = $this->formatearTelefono($paciente->telefono);

        if (!$telefono) {
            return null;
        }

        $mensaje = $this->generarMensajeRecordatorio($turno);
        $mensajeCodificado = urlencode($mensaje);
        
        return "https://wa.me/{$telefono}?text={$mensajeCodificado}";
    }

    /**
     * Generar mensaje de turno asignado
     */
    private function generarMensajeTurno(Turno $turno)
    {
        $paciente = $turno->oficio->paciente;
        $profesional = $turno->profesional;
        $fecha = Carbon::parse($turno->fecha_turno)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
        $hora = Carbon::parse($turno->hora)->format('H:i');

        return "🏥 *SIPSI - Turno Asignado*\n\n" .
               "Hola {$paciente->nombre} {$paciente->apellido},\n\n" .
               "Se le ha asignado un turno:\n\n" .
               "📅 *Fecha:* {$fecha}\n" .
               "🕐 *Hora:* {$hora} hs\n" .
               "👨‍⚕️ *Profesional:* {$profesional->apellido}, {$profesional->nombre}\n" .
               "📍 *Lugar:* Servicio de Psiquiatría Hospitalaria\n\n" .
               "⚠️ *Importante:* Por favor confirme su asistencia o avise si no puede concurrir.\n\n" .
               "Ante cualquier consulta, comuníquese con el servicio.";
    }

    /**
     * Generar mensaje de recordatorio
     */
    private function generarMensajeRecordatorio(Turno $turno)
    {
        $paciente = $turno->oficio->paciente;
        $profesional = $turno->profesional;
        $fecha = Carbon::parse($turno->fecha_turno)->locale('es')->isoFormat('dddd D [de] MMMM');
        $hora = Carbon::parse($turno->hora)->format('H:i');

        return "🔔 *RECORDATORIO DE TURNO*\n\n" .
               "Hola {$paciente->nombre},\n\n" .
               "Le recordamos que mañana tiene turno:\n\n" .
               "📅 *Fecha:* {$fecha}\n" .
               "🕐 *Hora:* {$hora} hs\n" .
               "👨‍⚕️ *Profesional:* {$profesional->apellido}, {$profesional->nombre}\n\n" .
               "Lo esperamos!";
    }

    /**
     * Formatear teléfono al formato internacional (sin +)
     */
    private function formatearTelefono($telefono)
    {
        if (!$telefono) {
            return null;
        }

        // Remover espacios, guiones, paréntesis y +
        $telefono = preg_replace('/[\s\-\(\)\+]/', '', $telefono);

        // Si empieza con 0, quitarlo
        if (substr($telefono, 0, 1) === '0') {
            $telefono = substr($telefono, 1);
        }

        // Si no empieza con 54, agregarlo (código Argentina)
        if (substr($telefono, 0, 2) !== '54') {
            $telefono = '54' . $telefono;
        }

        return $telefono;
    }

    /**
     * Validar si el paciente tiene teléfono
     */
    public function tieneWhatsApp(Turno $turno)
    {
        $paciente = $turno->oficio->paciente;
        return !empty($paciente->telefono);
    }
}
