<?php

namespace App\Services;

use App\Models\Oficio;
use App\Models\Paciente;
use App\Models\Turno;
use App\Models\Informe;
use App\Models\Profesional;
use App\Models\Juzgado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotActionService
{
    /**
     * Detecta la intención del usuario y ejecuta la acción correspondiente
     */
    public function ejecutarAccion(string $mensaje, array $contexto = []): array
    {
        $mensajeOriginal = $mensaje;
        $mensaje = strtolower(trim($mensaje));
        
        // ── TURNOS ──
        // Detectar "asignar", "crear", "dar" + "turno"
        if ((str_contains($mensaje, 'asign') || str_contains($mensaje, 'cre') || str_contains($mensaje, 'dar') || str_contains($mensaje, 'agend')) 
            && str_contains($mensaje, 'turno')) {
            return $this->asignarTurno($mensaje, $contexto);
        }
        
        // Detectar "cancelar", "eliminar", "borrar" + "turno"
        if ((str_contains($mensaje, 'cancel') || str_contains($mensaje, 'elimin') || str_contains($mensaje, 'borr')) 
            && str_contains($mensaje, 'turno')) {
            return $this->cancelarTurno($mensaje, $contexto);
        }
        
        // Detectar "modificar", "cambiar", "reprogramar" + "turno"
        if ((str_contains($mensaje, 'modific') || str_contains($mensaje, 'cambi') || str_contains($mensaje, 'reprogram')) 
            && str_contains($mensaje, 'turno')) {
            return $this->modificarTurno($mensaje, $contexto);
        }
        
        // Detectar "asistencia" o "asistió" o "presente"
        if ((str_contains($mensaje, 'asistencia') || str_contains($mensaje, 'asistió') || str_contains($mensaje, 'asistio') || str_contains($mensaje, 'presente'))
            && !str_contains($mensaje, 'inasistencia') && !str_contains($mensaje, 'no asistió')) {
            return $this->registrarAsistencia($mensaje, $contexto, 'realizado');
        }
        
        // Detectar "inasistencia" o "no asistió" o "ausente" o "faltó"
        if (str_contains($mensaje, 'inasistencia') || str_contains($mensaje, 'no asistió') || str_contains($mensaje, 'no asistio') 
            || str_contains($mensaje, 'ausente') || str_contains($mensaje, 'faltó') || str_contains($mensaje, 'falto')) {
            return $this->registrarAsistencia($mensaje, $contexto, 'ausente');
        }
        
        // ── OFICIOS ──
        // Detectar "cerrar", "finalizar", "completar" + "oficio"
        if ((str_contains($mensaje, 'cerr') || str_contains($mensaje, 'finaliz') || str_contains($mensaje, 'complet') || str_contains($mensaje, 'cierr')) 
            && str_contains($mensaje, 'oficio')) {
            return $this->cambiarEstadoOficio($mensaje, $contexto, 'cerrado');
        }
        
        // Detectar "notific" + "oficio" o "paciente"
        if (str_contains($mensaje, 'notific') && (str_contains($mensaje, 'oficio') || str_contains($mensaje, 'paciente'))) {
            return $this->registrarNotificacion($mensaje, $contexto);
        }
        
        // ── INFORMES ──
        // Detectar "marcar" o "enviar" + "informe"
        if ((str_contains($mensaje, 'marc') || str_contains($mensaje, 'envi')) && str_contains($mensaje, 'informe')) {
            return $this->marcarInformeEnviado($mensaje, $contexto);
        }
        
        return ['tipo' => 'no_accion', 'mensaje' => null];
    }
    
    /**
     * Verifica si el mensaje contiene alguna de las intenciones
     */
    private function esIntencion(string $mensaje, array $palabrasClave): bool
    {
        foreach ($palabrasClave as $clave) {
            if (str_contains($mensaje, $clave)) {
                return true;
            }
        }
        return false;
    }
    
    // ═══════════════════════════════════════════════════════════════════
    // ACCIONES DE TURNOS
    // ═══════════════════════════════════════════════════════════════════
    
    private function asignarTurno(string $mensaje, array $contexto): array
    {
        try {
            // Extraer parámetros
            $oficioId = $this->extraerNumero($mensaje, ['oficio', 'nro', 'número']);
            
            // Si no encontró número, buscar por nombre de paciente
            if (!$oficioId) {
                $oficioId = $this->buscarOficioPorPaciente($mensaje);
            }
            
            $profesionalId = $this->extraerProfesional($mensaje);
            $fecha = $this->extraerFecha($mensaje);
            $hora = $this->extraerHora($mensaje);
            
            // Validaciones
            if (!$oficioId) {
                return $this->respuestaError('No pude identificar el oficio. Podés especificar el número (ej: "asignar turno para el oficio 1239") o el nombre del paciente (ej: "asignar turno para González María")');
            }
            
            $oficio = Oficio::with('paciente')->find($oficioId);
            if (!$oficio) {
                return $this->respuestaError("No encontré el oficio #$oficioId");
            }
            
            if ($oficio->estado === 'cerrado') {
                return $this->respuestaError("El oficio #$oficioId ya está cerrado");
            }
            
            if (!$profesionalId) {
                // Sugerir profesionales disponibles
                $profesionales = Profesional::orderBy('apellido')->get();
                if ($profesionales->isEmpty()) {
                    return $this->respuestaError("No hay profesionales registrados en el sistema. Primero necesitás crear al menos un profesional.");
                }
                $lista = $profesionales->map(fn($p) => "• Dr/a. {$p->apellido}, {$p->nombre} (ID: {$p->id})")->join("\n");
                return $this->respuestaError("No especificaste el profesional. Disponibles:\n$lista\n\nEjemplo: \"asignar turno para {$oficio->paciente->apellido} con {$profesionales->first()->apellido} para mañana a las 10hs\"");
            }
            
            $profesional = Profesional::find($profesionalId);
            if (!$profesional) {
                return $this->respuestaError("No encontré el profesional con ID $profesionalId");
            }
            
            if (!$fecha) {
                $fecha = Carbon::tomorrow();
            }
            
            if (!$hora) {
                $hora = '10:00:00';
            }
            
            // Verificar disponibilidad
            $turnosEnHorario = Turno::where('profesional_id', $profesionalId)
                ->whereDate('fecha_turno', $fecha)
                ->where('hora', $hora)
                ->count();
            
            if ($turnosEnHorario > 0) {
                return $this->respuestaError("El Dr/a. {$profesional->apellido} ya tiene un turno asignado el " . $fecha->format('d/m/Y') . " a las " . substr($hora, 0, 5) . "hs");
            }
            
            // Crear turno
            $turno = Turno::create([
                'oficio_id' => $oficioId,
                'profesional_id' => $profesionalId,
                'fecha_turno' => $fecha,
                'hora' => $hora,
                'estado' => 'pendiente',
            ]);
            
            // Actualizar estado del oficio
            $oficio->update(['estado' => 'en_curso']);
            
            return [
                'tipo' => 'exito',
                'accion' => 'turno_asignado',
                'mensaje' => "✅ *Turno asignado exitosamente*\n\n" .
                            "📋 Oficio: #{$oficio->numero_oficio}\n" .
                            "👤 Paciente: {$oficio->paciente->apellido}, {$oficio->paciente->nombre}\n" .
                            "👨‍⚕️ Profesional: Dr/a. {$profesional->apellido}, {$profesional->nombre}\n" .
                            "📅 Fecha: " . $fecha->format('d/m/Y') . "\n" .
                            "🕐 Hora: " . substr($hora, 0, 5) . "hs\n" .
                            "🔗 Ver turno: " . url("/turnos/{$turno->id}/edit"),
                'datos' => [
                    'turno_id' => $turno->id,
                    'oficio_id' => $oficioId,
                ]
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al asignar turno desde chatbot', ['error' => $e->getMessage()]);
            return $this->respuestaError('Ocurrió un error al asignar el turno. Intentá de nuevo.');
        }
    }
    
    private function cancelarTurno(string $mensaje, array $contexto): array
    {
        try {
            $turnoId = $this->extraerNumero($mensaje, ['turno', 'nro', 'número', 'id']);
            
            if (!$turnoId) {
                return $this->respuestaError('No pude identificar el número de turno. Ejemplo: "cancelar turno 45"');
            }
            
            $turno = Turno::with(['oficio.paciente', 'profesional'])->find($turnoId);
            if (!$turno) {
                return $this->respuestaError("No encontré el turno #$turnoId");
            }
            
            // Guardar info antes de eliminar
            $info = "📋 Oficio: {$turno->oficio->numero_oficio}\n" .
                    "👤 Paciente: {$turno->oficio->paciente->apellido}, {$turno->oficio->paciente->nombre}\n" .
                    "👨‍⚕️ Profesional: Dr/a. {$turno->profesional->apellido}\n" .
                    "📅 Fecha: " . Carbon::parse($turno->fecha_turno)->format('d/m/Y') . " " . substr($turno->hora, 0, 5) . "hs";
            
            // Actualizar estado del oficio
            $turno->oficio->update(['estado' => 'pendiente']);
            
            // Eliminar turno
            $turno->delete();
            
            return [
                'tipo' => 'exito',
                'accion' => 'turno_cancelado',
                'mensaje' => "✅ *Turno cancelado exitosamente*\n\n$info\n\n⚠️ El oficio volvió a estado *pendiente*"
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al cancelar turno desde chatbot', ['error' => $e->getMessage()]);
            return $this->respuestaError('Ocurrió un error al cancelar el turno.');
        }
    }
    
    private function modificarTurno(string $mensaje, array $contexto): array
    {
        try {
            $turnoId = $this->extraerNumero($mensaje, ['turno', 'nro', 'número', 'id']);
            
            if (!$turnoId) {
                return $this->respuestaError('No pude identificar el número de turno. Ejemplo: "modificar turno 45 para el 20/05 a las 15hs"');
            }
            
            $turno = Turno::with(['oficio.paciente', 'profesional'])->find($turnoId);
            if (!$turno) {
                return $this->respuestaError("No encontré el turno #$turnoId");
            }
            
            $nuevaFecha = $this->extraerFecha($mensaje);
            $nuevaHora = $this->extraerHora($mensaje);
            
            if (!$nuevaFecha && !$nuevaHora) {
                return $this->respuestaError('Especificá la nueva fecha y/o hora. Ejemplo: "modificar turno 45 para el 20/05 a las 15hs"');
            }
            
            $cambios = [];
            if ($nuevaFecha) {
                $turno->fecha_turno = $nuevaFecha;
                $cambios[] = "📅 Nueva fecha: " . $nuevaFecha->format('d/m/Y');
            }
            if ($nuevaHora) {
                $turno->hora = $nuevaHora;
                $cambios[] = "🕐 Nueva hora: " . substr($nuevaHora, 0, 5) . "hs";
            }
            
            $turno->save();
            
            return [
                'tipo' => 'exito',
                'accion' => 'turno_modificado',
                'mensaje' => "✅ *Turno modificado exitosamente*\n\n" .
                            "📋 Oficio: {$turno->oficio->numero_oficio}\n" .
                            "👤 Paciente: {$turno->oficio->paciente->apellido}, {$turno->oficio->paciente->nombre}\n" .
                            "👨‍⚕️ Profesional: Dr/a. {$turno->profesional->apellido}\n\n" .
                            implode("\n", $cambios)
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al modificar turno desde chatbot', ['error' => $e->getMessage()]);
            return $this->respuestaError('Ocurrió un error al modificar el turno.');
        }
    }
    
    private function registrarAsistencia(string $mensaje, array $contexto, string $estado): array
    {
        try {
            $turnoId = $this->extraerNumero($mensaje, ['turno', 'nro', 'número', 'id']);
            
            if (!$turnoId) {
                return $this->respuestaError('No pude identificar el número de turno. Ejemplo: "registrar asistencia del turno 45"');
            }
            
            $turno = Turno::with(['oficio.paciente', 'profesional'])->find($turnoId);
            if (!$turno) {
                return $this->respuestaError("No encontré el turno #$turnoId");
            }
            
            $turno->update(['estado' => $estado]);
            
            $emoji = $estado === 'realizado' ? '✅' : '❌';
            $texto = $estado === 'realizado' ? 'Asistencia registrada' : 'Inasistencia registrada';
            
            return [
                'tipo' => 'exito',
                'accion' => 'asistencia_registrada',
                'mensaje' => "$emoji *$texto*\n\n" .
                            "📋 Oficio: {$turno->oficio->numero_oficio}\n" .
                            "👤 Paciente: {$turno->oficio->paciente->apellido}, {$turno->oficio->paciente->nombre}\n" .
                            "👨‍⚕️ Profesional: Dr/a. {$turno->profesional->apellido}\n" .
                            "📅 Fecha: " . Carbon::parse($turno->fecha_turno)->format('d/m/Y')
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al registrar asistencia desde chatbot', ['error' => $e->getMessage()]);
            return $this->respuestaError('Ocurrió un error al registrar la asistencia.');
        }
    }
    
    // ═══════════════════════════════════════════════════════════════════
    // ACCIONES DE OFICIOS
    // ═══════════════════════════════════════════════════════════════════
    
    private function crearOficio(string $mensaje, array $contexto): array
    {
        return $this->respuestaError('Para crear un oficio necesito más información. Usá el formulario: ' . url('/oficios/create'));
    }
    
    private function cambiarEstadoOficio(string $mensaje, array $contexto, string $nuevoEstado): array
    {
        try {
            $oficioId = $this->extraerNumero($mensaje, ['oficio', 'nro', 'número']);
            
            // Si no encontró número, buscar por nombre de paciente
            if (!$oficioId) {
                $oficioId = $this->buscarOficioPorPaciente($mensaje);
            }
            
            if (!$oficioId) {
                return $this->respuestaError('No pude identificar el oficio. Podés especificar el número (ej: "cerrar oficio 1239") o el nombre del paciente (ej: "cerrar oficio de González María")');
            }
            
            $oficio = Oficio::with('paciente')->find($oficioId);
            if (!$oficio) {
                return $this->respuestaError("No encontré el oficio con ID #$oficioId. Verificá el número de oficio.");
            }
            
            $estadoAnterior = $oficio->estado;
            $oficio->update(['estado' => $nuevoEstado]);
            
            $estadosTexto = [
                'pendiente' => 'Pendiente',
                'en_curso' => 'En curso',
                'cerrado' => 'Cerrado'
            ];
            
            return [
                'tipo' => 'exito',
                'accion' => 'oficio_actualizado',
                'mensaje' => "✅ *Oficio actualizado exitosamente*\n\n" .
                            "📋 Oficio: {$oficio->numero_oficio}\n" .
                            "👤 Paciente: {$oficio->paciente->apellido}, {$oficio->paciente->nombre}\n" .
                            "📊 Estado anterior: *" . ($estadosTexto[$estadoAnterior] ?? $estadoAnterior) . "*\n" .
                            "📊 Nuevo estado: *" . ($estadosTexto[$nuevoEstado] ?? ucfirst($nuevoEstado)) . "*"
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de oficio desde chatbot', ['error' => $e->getMessage()]);
            return $this->respuestaError('Ocurrió un error al actualizar el oficio.');
        }
    }
    
    private function registrarNotificacion(string $mensaje, array $contexto): array
    {
        try {
            $oficioId = $this->extraerNumero($mensaje, ['oficio', 'nro', 'número']);
            
            if (!$oficioId) {
                return $this->respuestaError('No pude identificar el número de oficio. Ejemplo: "registrar notificación del oficio 1239"');
            }
            
            $oficio = Oficio::with('paciente')->find($oficioId);
            if (!$oficio) {
                return $this->respuestaError("No encontré el oficio #$oficioId");
            }
            
            $oficio->update([
                'notificacion_paciente' => true,
                'fecha_notificacion' => Carbon::now()
            ]);
            
            return [
                'tipo' => 'exito',
                'accion' => 'notificacion_registrada',
                'mensaje' => "✅ *Notificación registrada*\n\n" .
                            "📋 Oficio: {$oficio->numero_oficio}\n" .
                            "👤 Paciente: {$oficio->paciente->apellido}, {$oficio->paciente->nombre}\n" .
                            "📅 Fecha: " . Carbon::now()->format('d/m/Y H:i')
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al registrar notificación desde chatbot', ['error' => $e->getMessage()]);
            return $this->respuestaError('Ocurrió un error al registrar la notificación.');
        }
    }
    
    // ═══════════════════════════════════════════════════════════════════
    // ACCIONES DE INFORMES
    // ═══════════════════════════════════════════════════════════════════
    
    private function crearInforme(string $mensaje, array $contexto): array
    {
        return $this->respuestaError('Para crear un informe necesito más información. Usá el formulario correspondiente.');
    }
    
    private function marcarInformeEnviado(string $mensaje, array $contexto): array
    {
        try {
            $informeId = $this->extraerNumero($mensaje, ['informe', 'nro', 'número', 'id']);
            
            if (!$informeId) {
                return $this->respuestaError('No pude identificar el número de informe. Ejemplo: "marcar informe 12 como enviado"');
            }
            
            $informe = Informe::with(['oficio.paciente', 'oficio.juzgado'])->find($informeId);
            if (!$informe) {
                return $this->respuestaError("No encontré el informe #$informeId");
            }
            
            $informe->update([
                'enviado_juzgado' => true,
                'fecha_envio_juzgado' => Carbon::now()
            ]);
            
            return [
                'tipo' => 'exito',
                'accion' => 'informe_enviado',
                'mensaje' => "✅ *Informe marcado como enviado*\n\n" .
                            "📋 Oficio: {$informe->oficio->numero_oficio}\n" .
                            "👤 Paciente: {$informe->oficio->paciente->apellido}, {$informe->oficio->paciente->nombre}\n" .
                            "⚖️ Juzgado: {$informe->oficio->juzgado->nombre}\n" .
                            "📅 Fecha de envío: " . Carbon::now()->format('d/m/Y H:i')
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al marcar informe como enviado desde chatbot', ['error' => $e->getMessage()]);
            return $this->respuestaError('Ocurrió un error al marcar el informe como enviado.');
        }
    }
    
    // ═══════════════════════════════════════════════════════════════════
    // ACCIONES DE PACIENTES
    // ═══════════════════════════════════════════════════════════════════
    
    private function crearPaciente(string $mensaje, array $contexto): array
    {
        return $this->respuestaError('Para crear un paciente necesito más información. Usá el formulario: ' . url('/pacientes/create'));
    }
    
    // ═══════════════════════════════════════════════════════════════════
    // HELPERS - EXTRACCIÓN DE PARÁMETROS
    // ═══════════════════════════════════════════════════════════════════
    
    private function extraerNumero(string $mensaje, array $palabrasClave): ?int
    {
        foreach ($palabrasClave as $palabra) {
            // Buscar "palabra 123" o "palabra #123" o "palabra nro 123"
            if (preg_match('/' . $palabra . '\s*#?\s*(\d+)/i', $mensaje, $matches)) {
                return (int) $matches[1];
            }
        }
        
        // Buscar cualquier número en el mensaje
        if (preg_match('/\b(\d+)\b/', $mensaje, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }
    
    /**
     * Busca un oficio por nombre de paciente
     */
    private function buscarOficioPorPaciente(string $mensaje): ?int
    {
        // Buscar nombres comunes en el mensaje
        $pacientes = Paciente::all();
        
        foreach ($pacientes as $paciente) {
            $nombreCompleto = strtolower($paciente->nombre . ' ' . $paciente->apellido);
            $apellidoNombre = strtolower($paciente->apellido . ' ' . $paciente->nombre);
            $soloApellido = strtolower($paciente->apellido);
            
            if (str_contains($mensaje, $nombreCompleto) || 
                str_contains($mensaje, $apellidoNombre) ||
                str_contains($mensaje, $soloApellido)) {
                
                // Buscar el oficio más reciente de este paciente
                $oficio = Oficio::where('paciente_id', $paciente->id)
                    ->whereIn('estado', ['pendiente', 'en_curso'])
                    ->orderBy('fecha_recepcion', 'desc')
                    ->first();
                
                if ($oficio) {
                    return $oficio->id;
                }
            }
        }
        
        return null;
    }
    
    private function extraerProfesional(string $mensaje): ?int
    {
        // Buscar por ID
        if (preg_match('/profesional\s+(\d+)/i', $mensaje, $matches)) {
            return (int) $matches[1];
        }
        
        // Buscar por apellido
        $profesionales = Profesional::all();
        foreach ($profesionales as $prof) {
            if (str_contains($mensaje, strtolower($prof->apellido))) {
                return $prof->id;
            }
        }
        
        return null;
    }
    
    private function extraerFecha(string $mensaje): ?Carbon
    {
        // "mañana"
        if (str_contains($mensaje, 'mañana') || str_contains($mensaje, 'manana')) {
            return Carbon::tomorrow();
        }
        
        // "hoy"
        if (str_contains($mensaje, 'hoy')) {
            return Carbon::today();
        }
        
        // "pasado mañana"
        if (str_contains($mensaje, 'pasado')) {
            return Carbon::today()->addDays(2);
        }
        
        // Formato dd/mm/yyyy o dd/mm
        if (preg_match('/(\d{1,2})\/(\d{1,2})(?:\/(\d{4}))?/', $mensaje, $m)) {
            $dia = (int) $m[1];
            $mes = (int) $m[2];
            $anio = isset($m[3]) ? (int) $m[3] : Carbon::now()->year;
            try {
                return Carbon::createFromDate($anio, $mes, $dia);
            } catch (\Exception $e) {
                return null;
            }
        }
        
        return null;
    }
    
    private function extraerHora(string $mensaje): ?string
    {
        // Formato "10hs", "10:00hs", "10:00"
        if (preg_match('/(\d{1,2}):?(\d{2})?\s*hs?/i', $mensaje, $m)) {
            $hora = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $min = isset($m[2]) ? $m[2] : '00';
            return "$hora:$min:00";
        }
        
        return null;
    }
    
    private function respuestaError(string $mensaje): array
    {
        return [
            'tipo' => 'error',
            'mensaje' => "⚠️ $mensaje"
        ];
    }
}
