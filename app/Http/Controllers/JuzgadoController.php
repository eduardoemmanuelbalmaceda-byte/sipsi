<?php

namespace App\Http\Controllers;

use App\Models\Juzgado;
use App\Models\Oficio;
use App\Exports\EstadisticasJuzgadosExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;

class JuzgadoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $juzgados = Juzgado::when($q, fn($query) => $query
                ->where('nombre',   'like', "%$q%")
                ->orWhere('ciudad', 'like', "%$q%")
                ->orWhere('contacto','like', "%$q%")
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('juzgados.index', compact('juzgados', 'q'));
    }

    public function create()
    {
        return view('juzgados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string',
            'ciudad'   => 'required|string',
            'contacto' => 'nullable|string',
        ]);

        Juzgado::create($request->all());

        return redirect()->route('juzgados.index')->with('success', 'Juzgado registrado correctamente.');
    }

    public function show(Juzgado $juzgado)
    {
        $juzgado->load('oficios');
        return view('juzgados.show', compact('juzgado'));
    }

    public function edit(Juzgado $juzgado)
    {
        return view('juzgados.edit', compact('juzgado'));
    }

    public function update(Request $request, Juzgado $juzgado)
    {
        $request->validate([
            'nombre'   => 'required|string',
            'ciudad'   => 'required|string',
            'contacto' => 'nullable|string',
        ]);

        $juzgado->update($request->all());

        return redirect()->route('juzgados.index')->with('success', 'Juzgado actualizado correctamente.');
    }

    public function destroy(Juzgado $juzgado)
    {
        $juzgado->delete();
        return redirect()->route('juzgados.index')->with('success', 'Juzgado eliminado correctamente.');
    }

    public function estadisticas(Request $request)
    {
        $anio      = $request->input('anio', Carbon::now()->year);
        $mes_desde = $request->input('mes_desde', 1);
        $mes_hasta = $request->input('mes_hasta', 12);

        $desde = Carbon::create($anio, $mes_desde, 1)->startOfMonth();
        $hasta = Carbon::create($anio, $mes_hasta, 1)->endOfMonth();

        $datos = Juzgado::withCount(['oficios as total' => function ($q) use ($desde, $hasta) {
                $q->whereBetween('fecha_recepcion', [$desde, $hasta]);
            }])
            ->orderByDesc('total')
            ->get()
            ->filter(fn($j) => $j->total > 0)
            ->values();

        $totalGeneral = $datos->sum('total');

        $aniosDisponibles = Oficio::selectRaw("YEAR(fecha_recepcion) as anio")
            ->groupBy('anio')
            ->orderByDesc('anio')
            ->pluck('anio');

        $mesesNombres = [
            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
            5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
            9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
        ];

        return view('juzgados.estadisticas', compact(
            'datos', 'totalGeneral', 'anio', 'mes_desde', 'mes_hasta',
            'aniosDisponibles', 'mesesNombres', 'desde', 'hasta'
        ));
    }

    private function getDatosEstadisticas(Request $request): array
    {
        $anio      = $request->input('anio', Carbon::now()->year);
        $mes_desde = (int) $request->input('mes_desde', 1);
        $mes_hasta = (int) $request->input('mes_hasta', 12);

        $desde = Carbon::create($anio, $mes_desde, 1)->startOfMonth();
        $hasta = Carbon::create($anio, $mes_hasta, 1)->endOfMonth();

        $mesesNombres = [
            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
            5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
            9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
        ];

        $datos = Juzgado::withCount(['oficios as total' => function ($q) use ($desde, $hasta) {
                $q->whereBetween('fecha_recepcion', [$desde, $hasta]);
            }])
            ->orderByDesc('total')
            ->get()
            ->filter(fn($j) => $j->total > 0)
            ->values();

        $periodo = $mesesNombres[$mes_desde]
            . ($mes_desde !== $mes_hasta ? ' – ' . $mesesNombres[$mes_hasta] : '')
            . ' ' . $anio;

        return [
            'datos'        => $datos,
            'totalGeneral' => $datos->sum('total'),
            'periodo'      => $periodo,
            'chartImg'     => $request->input('chart_img', ''),
        ];
    }

    public function exportarExcel(Request $request)
    {
        $d = $this->getDatosEstadisticas($request);
        $filename = 'estadisticas-juzgados-' . now()->format('Ymd') . '.xlsx';
        return Excel::download(
            new EstadisticasJuzgadosExport($d['datos'], $d['totalGeneral'], $d['periodo'], $d['chartImg']),
            $filename
        );
    }

    public function exportarPdf(Request $request)
    {
        $d = $this->getDatosEstadisticas($request);
        $pdf = Pdf::loadView('juzgados.estadisticas-pdf', $d)->setPaper('a4', 'portrait');
        return $pdf->download('estadisticas-juzgados-' . now()->format('Ymd') . '.pdf');
    }

    public function exportarWord(Request $request)
    {
        $d = $this->getDatosEstadisticas($request);

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection();

        $section->addText(
            'Estadísticas — Oficios Judiciales',
            ['bold' => true, 'size' => 16, 'color' => '2C3E6B'],
            ['alignment' => 'center']
        );
        $section->addText(
            'Período: ' . $d['periodo'],
            ['size' => 10, 'color' => '888888'],
            ['alignment' => 'center', 'spaceAfter' => 200]
        );

        $table = $section->addTable([
            'borderSize' => 6, 'borderColor' => 'DDDDDD',
            'cellMargin' => 80, 'width' => 100 * 50,
        ]);

        $table->addRow();
        foreach (['Oficina / Juzgado', 'Total Oficios'] as $h) {
            $cell = $table->addCell(null, ['bgColor' => 'F5C518']);
            $cell->addText($h, ['bold' => true, 'size' => 10, 'color' => '333333']);
        }

        foreach ($d['datos'] as $row) {
            $table->addRow();
            $table->addCell()->addText($row->nombre, ['size' => 10]);
            $table->addCell()->addText((string) $row->total, ['size' => 10]);
        }

        $table->addRow();
        $cell = $table->addCell(null, ['bgColor' => '5CB85C']);
        $cell->addText('TOTAL', ['bold' => true, 'size' => 11, 'color' => 'FFFFFF']);
        $cell = $table->addCell(null, ['bgColor' => '5CB85C']);
        $cell->addText((string) $d['totalGeneral'], ['bold' => true, 'size' => 11, 'color' => 'FFFFFF']);

        // Imagen del gráfico
        $tmpImg = null;
        if (!empty($d['chartImg']) && str_starts_with($d['chartImg'], 'data:image/png;base64,')) {
            $imgData = base64_decode(substr($d['chartImg'], strlen('data:image/png;base64,')));
            $tmpImg  = tempnam(sys_get_temp_dir(), 'chart') . '.png';
            file_put_contents($tmpImg, $imgData);

            $section->addTextBreak(1);
            $section->addText('Gráfico de distribución:', ['bold' => true, 'size' => 11, 'color' => '2C3E6B']);
            $section->addTextBreak(1);
            $section->addImage($tmpImg, [
                'width'         => 400,
                'height'        => 280,
                'alignment'     => 'center',
                'wrappingStyle' => 'inline',
            ]);
        }

        $filename = 'estadisticas-juzgados-' . now()->format('Ymd') . '.docx';
        $temp = tempnam(sys_get_temp_dir(), 'word');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($temp);

        if ($tmpImg && file_exists($tmpImg)) {
            @unlink($tmpImg);
        }

        return response()->download($temp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }
}
