<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Paciente;
use App\Models\Turno;
use App\Models\Informe;
use App\Models\Profesional;
use App\Models\Juzgado;
use App\Services\GroqAIService;
use App\Services\ChatbotActionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected GroqAIService $groqService;
    protected ChatbotActionService $actionService;

    public function __construct(GroqAIService $groqService, ChatbotActionService $actionService)
    {
        $this->groqService = $groqService;
        $this->actionService = $actionService;
    }

    public function alertas()
    {
        $hoy     = Carbon::today();
        $alertas = [];

        // ── 1. Oficios vencidos (fecha_vencimiento < hoy, no cerrados) ──
        $vencidos = Oficio::with('paciente')
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', $hoy)
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->orderBy('fecha_vencimiento')
            ->get();

        if ($vencidos->isNotEmpty()) {
            $alertas[] = [
                'nivel'   => 'critico',
                'icono'   => '🔴',
                'titulo'  => "Oficios vencidos ({$vencidos->count()})",
                'items'   => $vencidos->map(fn($o) => [
                    'texto' => "{$o->numero_oficio} — {$o->paciente->apellido}, {$o->paciente->nombre}",
                    'detalle' => "Venció el " . Carbon::parse($o->fecha_vencimiento)->format('d/m/Y') .
                                 " (" . Carbon::parse($o->fecha_vencimiento)->diffForHumans() . ")",
                ])->toArray(),
            ];
        }

        // ── 2. Oficios por vencer en los próximos 7 días ──
        $porVencer = Oficio::with('paciente')
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [$hoy, $hoy->copy()->addDays(7)])
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->orderBy('fecha_vencimiento')
            ->get();

        if ($porVencer->isNotEmpty()) {
            $alertas[] = [
                'nivel'   => 'advertencia',
                'icono'   => '🟡',
                'titulo'  => "Oficios por vencer esta semana ({$porVencer->count()})",
                'items'   => $porVencer->map(fn($o) => [
                    'texto' => "{$o->numero_oficio} — {$o->paciente->apellido}, {$o->paciente->nombre}",
                    'detalle' => "Vence el " . Carbon::parse($o->fecha_vencimiento)->format('d/m/Y') .
                                 " (" . Carbon::parse($o->fecha_vencimiento)->diffForHumans() . ")",
                ])->toArray(),
            ];
        }

        // ── 3. Turnos de hoy sin confirmar ──
        $turnosHoy = Turno::with(['oficio.paciente', 'profesional'])
            ->whereDate('fecha_turno', $hoy)
            ->where('estado', 'pendiente')
            ->orderBy('hora')
            ->get();

        if ($turnosHoy->isNotEmpty()) {
            $alertas[] = [
                'nivel'   => 'info',
                'icono'   => '📅',
                'titulo'  => "Turnos pendientes hoy ({$turnosHoy->count()})",
                'items'   => $turnosHoy->map(fn($t) => [
                    'texto' => substr($t->hora, 0, 5) . "hs — " .
                               $t->oficio->paciente->apellido . ", " . $t->oficio->paciente->nombre,
                    'detalle' => "Dr/a. " . $t->profesional->apellido,
                ])->toArray(),
            ];
        }

        // ── 4. Oficios pendientes sin turno hace más de 15 días ──
        $sinTurnoViejo = Oficio::with('paciente')
            ->where('estado', 'pendiente')
            ->where('fecha_recepcion', '<', $hoy->copy()->subDays(15))
            ->whereDoesntHave('turno')
            ->orderBy('fecha_recepcion')
            ->take(5)
            ->get();

        $totalSinTurno = Oficio::where('estado', 'pendiente')
            ->where('fecha_recepcion', '<', $hoy->copy()->subDays(15))
            ->whereDoesntHave('turno')
            ->count();

        if ($totalSinTurno > 0) {
            $alertas[] = [
                'nivel'   => 'advertencia',
                'icono'   => '⏳',
                'titulo'  => "$totalSinTurno oficio" . ($totalSinTurno !== 1 ? 's' : '') . " sin turno hace +15 días",
                'items'   => $sinTurnoViejo->map(fn($o) => [
                    'texto' => "{$o->numero_oficio} — {$o->paciente->apellido}, {$o->paciente->nombre}",
                    'detalle' => "Recibido el " . Carbon::parse($o->fecha_recepcion)->format('d/m/Y') .
                                 " (" . Carbon::parse($o->fecha_recepcion)->diffForHumans() . ")",
                ])->toArray(),
            ];
        }

        // ── 5. Informes sin enviar al juzgado hace más de 7 días ──
        $informesPendientes = Informe::with(['oficio.paciente', 'oficio.juzgado'])
            ->where('enviado_juzgado', false)
            ->where('fecha_informe', '<', $hoy->copy()->subDays(7))
            ->orderBy('fecha_informe')
            ->take(5)
            ->get();

        $totalInformes = Informe::where('enviado_juzgado', false)
            ->where('fecha_informe', '<', $hoy->copy()->subDays(7))
            ->count();

        if ($totalInformes > 0) {
            $alertas[] = [
                'nivel'   => 'advertencia',
                'icono'   => '📄',
                'titulo'  => "$totalInformes informe" . ($totalInformes !== 1 ? 's' : '') . " sin enviar al juzgado (+7 días)",
                'items'   => $informesPendientes->map(fn($i) => [
                    'texto' => $i->oficio->numero_oficio . " — " . $i->oficio->paciente->apellido,
                    'detalle' => "Juzgado: " . $i->oficio->juzgado->nombre .
                                 " | Informe del " . Carbon::parse($i->fecha_informe)->format('d/m/Y'),
                ])->toArray(),
            ];
        }

        return response()->json([
            'alertas' => $alertas,
            'total'   => collect($alertas)->sum(fn($a) => count($a['items'])),
        ]);
    }

    public function responder(Request $request)
    {
        $msg = strtolower(trim($request->input('mensaje', '')));

        $respuesta = $this->procesar($msg);

        return response()->json(['respuesta' => $respuesta]);
    }

    private function procesar(string $msg): string
    {
        // ── PRIMERO: Intentar ejecutar una acción ──
        $resultadoAccion = $this->actionService->ejecutarAccion($msg);
        
        if ($resultadoAccion['tipo'] !== 'no_accion') {
            return $resultadoAccion['mensaje'];
        }
        
        // ── SEGUNDO: Procesar con reglas predefinidas ──
        
        // ── Saludos ──
        if ($this->contiene($msg, ['hola', 'buenas', 'buen dia', 'buenos dias', 'buenas tardes', 'buenas noches', 'hey'])) {
            $hora = (int) Carbon::now()->format('H');
            if ($hora < 12)      $saludo = 'Buenos días';
            elseif ($hora < 19)  $saludo = 'Buenas tardes';
            else                 $saludo = 'Buenas noches';
            return "$saludo 👋 Soy el asistente de SIPSI. Podés preguntarme sobre oficios, turnos, pacientes, informes o profesionales.";
        }

        // ── Ayuda ──
        if ($this->contiene($msg, ['ayuda', 'que podes', 'qué podés', 'que sabes', 'comandos', 'opciones', 'como funciona'])) {
            return "Puedo responder preguntas y *realizar acciones*:\n\n" .
                   "*📊 CONSULTAS:*\n" .
                   "• ¿Cuántos oficios pendientes hay?\n" .
                   "• ¿Cuáles son los próximos turnos?\n" .
                   "• Resumen general / Alertas\n\n" .
                   "*⚡ ACCIONES - TURNOS:*\n" .
                   "• Asignar turno para el oficio 1239 con el Dr. Gomez para mañana a las 10hs\n" .
                   "• Cancelar turno 45\n" .
                   "• Modificar turno 45 para el 20/05 a las 15hs\n" .
                   "• Registrar asistencia del turno 45\n" .
                   "• Registrar inasistencia del turno 45\n\n" .
                   "*⚡ ACCIONES - OFICIOS:*\n" .
                   "• Cerrar oficio 1239\n" .
                   "• Registrar notificación del oficio 1239\n\n" .
                   "*⚡ ACCIONES - INFORMES:*\n" .
                   "• Marcar informe 12 como enviado\n\n" .
                   "💡 También podés hacer preguntas en lenguaje natural si tenés la IA activada.";
        }

        // ── Resumen general ──
        if ($this->contiene($msg, ['resumen', 'estado general', 'como estamos', 'cómo estamos', 'panorama'])) {
            return $this->resumenGeneral();
        }

        // ── Alertas / urgencias ──
        if ($this->contiene($msg, ['alerta', 'alertas', 'urgente', 'urgentes', 'vencido', 'vencidos', 'vencer', 'critico', 'crítico', 'prioridad'])) {
            return $this->resumenAlertas();
        }

        // ── Oficios ──
        if ($this->contiene($msg, ['oficio', 'oficios'])) {
            return $this->consultaOficios($msg);
        }

        // ── Turnos / disponibilidad ──
        if ($this->contiene($msg, ['turno', 'turnos', 'cita', 'citas', 'agenda', 'disponib', 'libre', 'libres', 'ocupado', 'ocupados'])) {
            return $this->consultaTurnos($msg);
        }

        // ── Informes ──
        if ($this->contiene($msg, ['informe', 'informes', 'reporte', 'reportes'])) {
            return $this->consultaInformes($msg);
        }

        // ── Pacientes ──
        if ($this->contiene($msg, ['paciente', 'pacientes'])) {
            return $this->consultaPacientes($msg);
        }

        // ── Profesionales ──
        if ($this->contiene($msg, ['profesional', 'profesionales', 'medico', 'médico', 'doctor', 'psiquiatra'])) {
            $total = Profesional::count();
            return "Hay $total profesional" . ($total !== 1 ? 'es' : '') . " registrado" . ($total !== 1 ? 's' : '') . " en el sistema.";
        }

        // ── Juzgados ──
        if ($this->contiene($msg, ['juzgado', 'juzgados'])) {
            $total = Juzgado::count();
            return "Hay $total juzgado" . ($total !== 1 ? 's' : '') . " registrado" . ($total !== 1 ? 's' : '') . " en el sistema.";
        }

        // ── Fallback con IA ──
        return $this->responderConIA($msg);
    }

    // ── Consultas específicas ──

    private function resumenAlertas(): string
    {
        $hoy    = Carbon::today();
        $lineas = ["🚨 *Alertas activas:*\n"];
        $hayAlgo = false;

        $vencidos = Oficio::whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', $hoy)
            ->whereIn('estado', ['pendiente', 'en_curso'])->count();
        if ($vencidos > 0) {
            $hayAlgo = true;
            $lineas[] = "🔴 *$vencidos oficio" . ($vencidos !== 1 ? 's' : '') . " vencido" . ($vencidos !== 1 ? 's' : '') . "*";
        }

        $porVencer = Oficio::whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [$hoy, $hoy->copy()->addDays(7)])
            ->whereIn('estado', ['pendiente', 'en_curso'])->count();
        if ($porVencer > 0) {
            $hayAlgo = true;
            $lineas[] = "🟡 *$porVencer por vencer* esta semana";
        }

        $turnosHoy = Turno::whereDate('fecha_turno', $hoy)->where('estado', 'pendiente')->count();
        if ($turnosHoy > 0) {
            $hayAlgo = true;
            $lineas[] = "📅 *$turnosHoy turno" . ($turnosHoy !== 1 ? 's' : '') . " pendiente" . ($turnosHoy !== 1 ? 's' : '') . " hoy*";
        }

        $sinTurno = Oficio::where('estado', 'pendiente')
            ->where('fecha_recepcion', '<', $hoy->copy()->subDays(15))
            ->whereDoesntHave('turno')->count();
        if ($sinTurno > 0) {
            $hayAlgo = true;
            $lineas[] = "⏳ *$sinTurno oficio" . ($sinTurno !== 1 ? 's' : '') . " sin turno* hace +15 días";
        }

        $informes = Informe::where('enviado_juzgado', false)
            ->where('fecha_informe', '<', $hoy->copy()->subDays(7))->count();
        if ($informes > 0) {
            $hayAlgo = true;
            $lineas[] = "📄 *$informes informe" . ($informes !== 1 ? 's' : '') . " sin enviar* al juzgado (+7 días)";
        }

        if (!$hayAlgo) return "✅ No hay alertas activas. Todo está al día.";

        $lineas[] = "\nEscribí *vencidos*, *por vencer*, *sin turno* o *informes sin enviar* para más detalle.";
        return implode("\n", $lineas);
    }

    private function resumenGeneral(): string
    {
        $total     = Oficio::count();
        $pend      = Oficio::where('estado', 'pendiente')->count();
        $curso     = Oficio::where('estado', 'en_curso')->count();
        $cerrados  = Oficio::where('estado', 'cerrado')->count();
        $pacientes = Paciente::count();
        $proxTurno = Turno::where('estado', 'pendiente')
                        ->where('fecha_turno', '>=', Carbon::today())
                        ->orderBy('fecha_turno')->orderBy('hora')
                        ->first();
        $sinEnviar = Informe::where('enviado_juzgado', false)->count();

        $lineas = [
            "📋 *Resumen general*",
            "• Oficios totales: $total ($pend pendientes, $curso en curso, $cerrados cerrados)",
            "• Pacientes registrados: $pacientes",
        ];

        if ($proxTurno) {
            $fecha = Carbon::parse($proxTurno->fecha_turno)->format('d/m/Y');
            $hora  = substr($proxTurno->hora, 0, 5);
            $lineas[] = "• Próximo turno: $fecha a las {$hora}hs";
        }

        if ($sinEnviar > 0) {
            $lineas[] = "⚠️ Hay $sinEnviar informe" . ($sinEnviar !== 1 ? 's' : '') . " pendiente" . ($sinEnviar !== 1 ? 's' : '') . " de envío al juzgado.";
        } else {
            $lineas[] = "✅ Todos los informes fueron enviados al juzgado.";
        }

        return implode("\n", $lineas);
    }

    private function consultaOficios(string $msg): string
    {
        // Pendientes
        if ($this->contiene($msg, ['pendiente', 'pendientes', 'sin turno'])) {
            $n = Oficio::where('estado', 'pendiente')->count();
            return "Hay $n oficio" . ($n !== 1 ? 's' : '') . " pendiente" . ($n !== 1 ? 's' : '') . " (sin turno asignado).";
        }

        // En curso
        if ($this->contiene($msg, ['en curso', 'curso', 'activo', 'activos'])) {
            $n = Oficio::where('estado', 'en_curso')->count();
            return "Hay $n oficio" . ($n !== 1 ? 's' : '') . " en curso (con turno asignado).";
        }

        // Cerrados
        if ($this->contiene($msg, ['cerrado', 'cerrados', 'finalizado', 'finalizados'])) {
            $n = Oficio::where('estado', 'cerrado')->count();
            return "Hay $n oficio" . ($n !== 1 ? 's' : '') . " cerrado" . ($n !== 1 ? 's' : '') . " (con informe cargado).";
        }

        // Hoy
        if ($this->contiene($msg, ['hoy', 'de hoy'])) {
            $n = Oficio::whereDate('fecha_recepcion', Carbon::today())->count();
            return $n > 0
                ? "Se recibieron $n oficio" . ($n !== 1 ? 's' : '') . " hoy."
                : "No se recibieron oficios hoy.";
        }

        // Esta semana
        if ($this->contiene($msg, ['semana', 'esta semana'])) {
            $n = Oficio::whereBetween('fecha_recepcion', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            return "Esta semana se recibieron $n oficio" . ($n !== 1 ? 's' : '') . ".";
        }

        // Este mes
        if ($this->contiene($msg, ['mes', 'este mes'])) {
            $n = Oficio::whereMonth('fecha_recepcion', Carbon::now()->month)
                       ->whereYear('fecha_recepcion', Carbon::now()->year)
                       ->count();
            return "Este mes se recibieron $n oficio" . ($n !== 1 ? 's' : '') . ".";
        }

        // Total
        $total    = Oficio::count();
        $pend     = Oficio::where('estado', 'pendiente')->count();
        $curso    = Oficio::where('estado', 'en_curso')->count();
        $cerrados = Oficio::where('estado', 'cerrado')->count();
        return "Total de oficios: $total\n• Pendientes: $pend\n• En curso: $curso\n• Cerrados: $cerrados";
    }

    private function consultaTurnos(string $msg): string
    {
        // ── Detectar fecha en el mensaje ──
        $fecha = $this->extraerFecha($msg);

        // ── Detectar profesional en el mensaje ──
        $profesional = $this->extraerProfesional($msg);

        // ── Consulta de disponibilidad (fecha específica) ──
        if ($fecha) {
            return $this->disponibilidadFecha($fecha, $profesional, $msg);
        }

        // ── Turnos de hoy ──
        if ($this->contiene($msg, ['hoy', 'de hoy'])) {
            return $this->disponibilidadFecha(Carbon::today(), $profesional, $msg);
        }

        // ── Mañana ──
        if ($this->contiene($msg, ['mañana', 'manana'])) {
            return $this->disponibilidadFecha(Carbon::tomorrow(), $profesional, $msg);
        }

        // ── Esta semana ──
        if ($this->contiene($msg, ['semana', 'esta semana'])) {
            return $this->turnosSemana();
        }

        // ── Próximos ──
        if ($this->contiene($msg, ['proximo', 'próximo', 'proximos', 'próximos', 'siguiente', 'pendiente', 'pendientes'])) {
            return $this->turnosProximos();
        }

        // ── Disponibilidad general ──
        if ($this->contiene($msg, ['disponib', 'libre', 'libres', 'cuando', 'cuándo'])) {
            return $this->disponibilidadGeneral();
        }

        // ── Total ──
        $total   = Turno::count();
        $pend    = Turno::where('estado', 'pendiente')->count();
        $realiz  = Turno::where('estado', 'realizado')->count();
        $ausente = Turno::where('estado', 'ausente')->count();
        return "Total de turnos: $total\n• Pendientes: $pend\n• Realizados: $realiz\n• Ausentes: $ausente";
    }

    private function disponibilidadFecha(Carbon $fecha, ?Profesional $profesional, string $msg): string
    {
        $fechaStr    = $fecha->format('d/m/Y');
        $esFuturo    = $fecha->isFuture() || $fecha->isToday();
        $lineas      = [];

        // ── A. Turnos YA asignados ese día ──
        $query = Turno::with(['oficio.paciente', 'profesional'])
            ->whereDate('fecha_turno', $fecha)
            ->orderBy('hora');

        if ($profesional) {
            $query->where('profesional_id', $profesional->id);
        }

        $turnosAsignados = $query->get();

        if ($turnosAsignados->isNotEmpty()) {
            $quien = $profesional ? "Dr/a. {$profesional->apellido}" : "todos los profesionales";
            $lineas[] = "📅 *Turnos asignados el $fechaStr* ($quien):";
            foreach ($turnosAsignados as $t) {
                $hora  = substr($t->hora, 0, 5);
                $pac   = $t->oficio->paciente->apellido . ', ' . $t->oficio->paciente->nombre;
                $prof  = $t->profesional->apellido;
                $estado = $t->estado === 'pendiente' ? '🟡' : ($t->estado === 'realizado' ? '✅' : '❌');
                $lineas[] = "• {$hora}hs — $pac (Dr/a. $prof) $estado";
            }
        } else {
            $quien = $profesional ? "Dr/a. {$profesional->apellido}" : "ningún profesional";
            $lineas[] = "📅 El $fechaStr no hay turnos asignados para $quien.";
        }

        // ── B. Disponibilidad (solo si es fecha futura o hoy) ──
        if ($esFuturo) {
            $lineas[] = "";

            $profesionales  = Profesional::orderBy('apellido')->get();
            $hayDisponibles = false;
            $resumen        = [];

            foreach ($profesionales as $prof) {
                $cantTurnos  = Turno::where('profesional_id', $prof->id)
                    ->whereDate('fecha_turno', $fecha)
                    ->count();
                $disponibles = max(0, 8 - $cantTurnos);

                $resumen[] = [
                    'nombre'      => "Dr/a. {$prof->apellido}, {$prof->nombre}",
                    'turnos'      => $cantTurnos,
                    'disponibles' => $disponibles,
                ];

                if ($disponibles > 0) $hayDisponibles = true;
            }

            if ($profesionales->isEmpty()) {
                $lineas[] = "⚠️ No hay profesionales registrados en el sistema.";
            } elseif ($hayDisponibles) {
                $lineas[] = "🟢 *Disponibilidad el $fechaStr:*";
                foreach ($resumen as $r) {
                    if ($r['disponibles'] > 0) {
                        $lineas[] = "• {$r['nombre']} — {$r['disponibles']} lugar" . ($r['disponibles'] !== 1 ? 'es' : '') . " libre" . ($r['disponibles'] !== 1 ? 's' : '') . " ({$r['turnos']} asignado" . ($r['turnos'] !== 1 ? 's' : '') . ")";
                    } else {
                        $lineas[] = "• {$r['nombre']} — completo (8/8)";
                    }
                }
            } else {
                $lineas[] = "🔴 Todos los profesionales tienen la agenda completa el $fechaStr.";
            }

            // ── C. Oficios pendientes sin turno (para asignar) ──
            $oficiosSinTurno = Oficio::with('paciente')
                ->where('estado', 'pendiente')
                ->whereDoesntHave('turno')
                ->orderBy('fecha_recepcion')
                ->take(3)
                ->get();

            if ($oficiosSinTurno->isNotEmpty()) {
                $lineas[] = "";
                $lineas[] = "📋 *Oficios pendientes sin turno* (podés asignarles uno el $fechaStr):";
                foreach ($oficiosSinTurno as $o) {
                    $url = url("/oficios/{$o->id}/turno/create");
                    $lineas[] = "• Oficio {$o->numero_oficio} — {$o->paciente->apellido}, {$o->paciente->nombre} → $url";
                }
            }
        }

        return implode("\n", $lineas);
    }

    private function turnosSemana(): string
    {
        $inicio = Carbon::now()->startOfWeek();
        $fin    = Carbon::now()->endOfWeek();

        $turnos = Turno::with(['oficio.paciente', 'profesional'])
            ->whereBetween('fecha_turno', [$inicio, $fin])
            ->where('estado', 'pendiente')
            ->orderBy('fecha_turno')
            ->orderBy('hora')
            ->get();

        if ($turnos->isEmpty()) return "No hay turnos pendientes esta semana.";

        $lineas = ["📅 *Turnos pendientes esta semana* ({$turnos->count()}):"];
        $diaActual = '';
        foreach ($turnos as $t) {
            $dia = Carbon::parse($t->fecha_turno)->locale('es')->isoFormat('dddd D/MM');
            if ($dia !== $diaActual) {
                $lineas[] = "\n*$dia*";
                $diaActual = $dia;
            }
            $hora = substr($t->hora, 0, 5);
            $pac  = $t->oficio->paciente->apellido . ', ' . $t->oficio->paciente->nombre;
            $lineas[] = "• {$hora}hs — $pac (Dr/a. {$t->profesional->apellido})";
        }
        return implode("\n", $lineas);
    }

    private function turnosProximos(): string
    {
        $turnos = Turno::with(['oficio.paciente', 'profesional'])
            ->where('estado', 'pendiente')
            ->where('fecha_turno', '>=', Carbon::today())
            ->orderBy('fecha_turno')->orderBy('hora')
            ->take(5)
            ->get();

        if ($turnos->isEmpty()) return "No hay turnos próximos pendientes.";

        $lineas = ["📅 Próximos turnos:"];
        foreach ($turnos as $t) {
            $fecha = Carbon::parse($t->fecha_turno)->format('d/m/Y');
            $hora  = substr($t->hora, 0, 5);
            $pac   = $t->oficio->paciente->apellido . ', ' . $t->oficio->paciente->nombre;
            $lineas[] = "• $fecha {$hora}hs — $pac (Dr/a. {$t->profesional->apellido})";
        }
        return implode("\n", $lineas);
    }

    private function disponibilidadGeneral(): string
    {
        $hoy  = Carbon::today();
        $dias = [];

        for ($i = 0; $i <= 6; $i++) {
            $fecha = $hoy->copy()->addDays($i);
            $cant  = Turno::whereDate('fecha_turno', $fecha)->count();
            $dias[] = $fecha->format('d/m') . " (" . $cant . " turno" . ($cant !== 1 ? 's' : '') . ")";
        }

        $lineas = ["📅 *Turnos asignados próximos 7 días:*"];
        foreach ($dias as $d) {
            $lineas[] = "• $d";
        }
        $lineas[] = "\nPreguntame por una fecha específica, por ejemplo: *turnos del 30/04*";
        return implode("\n", $lineas);
    }

    // ── Detectar fecha en el mensaje ──
    private function extraerFecha(string $msg): ?Carbon
    {
        // Formato dd/mm/yyyy o dd/mm
        if (preg_match('/(\d{1,2})\/(\d{1,2})(?:\/(\d{4}))?/', $msg, $m)) {
            $dia  = (int) $m[1];
            $mes  = (int) $m[2];
            $anio = isset($m[3]) ? (int) $m[3] : Carbon::now()->year;
            try {
                return Carbon::createFromDate($anio, $mes, $dia);
            } catch (\Exception $e) {
                return null;
            }
        }

        // Formato "el 25 de abril" o "25 de abril"
        $mesesMap = [
            'enero'=>1,'febrero'=>2,'marzo'=>3,'abril'=>4,'mayo'=>5,'junio'=>6,
            'julio'=>7,'agosto'=>8,'septiembre'=>9,'octubre'=>10,'noviembre'=>11,'diciembre'=>12
        ];
        foreach ($mesesMap as $nombre => $num) {
            if (preg_match('/(\d{1,2})\s+de\s+' . $nombre . '/', $msg, $m)) {
                try {
                    return Carbon::createFromDate(Carbon::now()->year, $num, (int)$m[1]);
                } catch (\Exception $e) {
                    return null;
                }
            }
        }

        return null;
    }

    // ── Detectar profesional en el mensaje ──
    private function extraerProfesional(string $msg): ?Profesional
    {
        $profesionales = Profesional::all();
        foreach ($profesionales as $p) {
            if (str_contains($msg, strtolower($p->apellido)) ||
                str_contains($msg, strtolower($p->nombre))) {
                return $p;
            }
        }
        return null;
    }

    private function consultaInformes(string $msg): string
    {
        // Sin enviar
        if ($this->contiene($msg, ['sin enviar', 'no enviado', 'pendiente', 'pendientes', 'falta enviar'])) {
            $n = Informe::where('enviado_juzgado', false)->count();
            return $n > 0
                ? "Hay $n informe" . ($n !== 1 ? 's' : '') . " pendiente" . ($n !== 1 ? 's' : '') . " de envío al juzgado."
                : "✅ Todos los informes fueron enviados al juzgado.";
        }

        // Enviados
        if ($this->contiene($msg, ['enviado', 'enviados', 'ya enviado'])) {
            $n = Informe::where('enviado_juzgado', true)->count();
            return "Hay $n informe" . ($n !== 1 ? 's' : '') . " enviado" . ($n !== 1 ? 's' : '') . " al juzgado.";
        }

        // Total
        $total    = Informe::count();
        $enviados = Informe::where('enviado_juzgado', true)->count();
        $pend     = $total - $enviados;
        return "Total de informes: $total\n• Enviados al juzgado: $enviados\n• Pendientes de envío: $pend";
    }

    private function consultaPacientes(string $msg): string
    {
        $total = Paciente::count();

        if ($this->contiene($msg, ['cuantos', 'cuántos', 'total', 'cantidad'])) {
            return "Hay $total paciente" . ($total !== 1 ? 's' : '') . " registrado" . ($total !== 1 ? 's' : '') . " en el sistema.";
        }

        // Con oficios activos
        $activos = Paciente::whereHas('oficios', fn($q) => $q->whereIn('estado', ['pendiente', 'en_curso']))->count();
        return "Pacientes registrados: $total\n• Con oficios activos: $activos";
    }

    // ── Helper ──
    private function contiene(string $texto, array $palabras): bool
    {
        foreach ($palabras as $p) {
            if (str_contains($texto, $p)) return true;
        }
        return false;
    }

    /**
     * Responde usando IA cuando no se entiende la consulta
     */
    private function responderConIA(string $msg): string
    {
        // Si la IA no está configurada, mostrar mensaje de ayuda
        if (!$this->groqService->estaConfigurado()) {
            return "No entendí esa consulta 🤔 Escribí *ayuda* para ver qué puedo responder.\n\n💡 *Tip:* Podés activar la IA para consultas más naturales. Pedile al administrador que configure GROQ_API_KEY en el archivo .env";
        }

        // Obtener contexto del sistema para la IA
        $contexto = $this->obtenerContextoSistema();

        // Consultar a la IA
        return $this->groqService->interpretar($msg, $contexto);
    }

    /**
     * Obtiene estadísticas del sistema para dar contexto a la IA
     */
    private function obtenerContextoSistema(): array
    {
        $hoy = Carbon::today();

        return [
            'Oficios totales' => Oficio::count(),
            'Oficios pendientes' => Oficio::where('estado', 'pendiente')->count(),
            'Oficios en curso' => Oficio::where('estado', 'en_curso')->count(),
            'Oficios cerrados' => Oficio::where('estado', 'cerrado')->count(),
            'Oficios vencidos' => Oficio::whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '<', $hoy)
                ->whereIn('estado', ['pendiente', 'en_curso'])
                ->count(),
            'Pacientes registrados' => Paciente::count(),
            'Profesionales activos' => Profesional::count(),
            'Juzgados registrados' => Juzgado::count(),
            'Turnos pendientes hoy' => Turno::whereDate('fecha_turno', $hoy)
                ->where('estado', 'pendiente')
                ->count(),
            'Informes sin enviar' => Informe::where('enviado_juzgado', false)->count(),
        ];
    }
}
