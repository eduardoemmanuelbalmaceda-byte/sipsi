<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Collection;

class EstadisticasJuzgadosExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    protected Collection $datos;
    protected int $totalGeneral;
    protected string $periodo;
    protected string $chartImg;
    protected ?string $tmpImgPath = null;

    public function __construct(Collection $datos, int $totalGeneral, string $periodo, string $chartImg = '')
    {
        $this->datos        = $datos;
        $this->totalGeneral = $totalGeneral;
        $this->periodo      = $periodo;
        $this->chartImg     = $chartImg;

        // Crear el archivo temporal AHORA, antes de que PhpSpreadsheet lo necesite
        if (!empty($chartImg) && str_starts_with($chartImg, 'data:image/png;base64,')) {
            $imgData = base64_decode(substr($chartImg, strlen('data:image/png;base64,')));
            $path    = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'chart_' . uniqid() . '.png';
            file_put_contents($path, $imgData);
            $this->tmpImgPath = $path;
        }
    }

    public function __destruct()
    {
        if ($this->tmpImgPath && file_exists($this->tmpImgPath)) {
            @unlink($this->tmpImgPath);
        }
    }

    public function collection(): Collection
    {
        $rows = $this->datos->map(fn($row) => [
            'juzgado' => $row->nombre,
            'total'   => $row->total,
        ]);
        $rows->push(['juzgado' => 'TOTAL', 'total' => $this->totalGeneral]);
        return $rows;
    }

    public function headings(): array
    {
        return ['Oficina / Juzgado', 'Total Oficios'];
    }

    public function title(): string
    {
        return 'Estadísticas';
    }

    public function styles(Worksheet $sheet): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Fila de título arriba de todo
                $sheet->insertNewRowBefore(1);
                $sheet->setCellValue('A1', 'Estadísticas Oficios Judiciales — ' . $this->periodo);
                $sheet->mergeCells('A1:B1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF2C3E6B']],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Encabezado de columnas (ahora en fila 2)
                $sheet->getStyle('A2:B2')->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => 'FF333333']],
                    'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFF5C518']],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Fila TOTAL (título + encabezado + filas + total)
                $lastRow = $this->datos->count() + 3;
                $sheet->getStyle("A{$lastRow}:B{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF5CB85C']],
                ]);

                // Imagen del gráfico
                if ($this->tmpImgPath && file_exists($this->tmpImgPath)) {
                    $imgRow = $lastRow + 2;

                    $drawing = new Drawing();
                    $drawing->setName('Gráfico');
                    $drawing->setDescription('Distribución por juzgado');
                    $drawing->setPath($this->tmpImgPath);
                    $drawing->setHeight(220);
                    $drawing->setCoordinates('A' . $imgRow);
                    $drawing->setWorksheet($sheet);
                }
            },
        ];
    }
}
