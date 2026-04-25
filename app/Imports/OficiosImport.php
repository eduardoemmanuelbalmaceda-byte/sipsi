<?php

namespace App\Imports;

use App\Models\Oficio;
use App\Models\Paciente;
use App\Models\Juzgado;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Carbon\Carbon;

class OficiosImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    private int $importados = 0;
    private int $omitidos   = 0;
    private array $errores  = [];

    public function model(array $row): ?Oficio
    {
        $row = array_change_key_case(array_map('trim', $row), CASE_LOWER);

        $numeroOficio = $row['numero_oficio'] ?? $row['numero'] ?? null;

        if (!$numeroOficio) {
            $this->omitidos++;
            return null;
        }

        // Si ya existe el número de oficio, omitir
        if (Oficio::where('numero_oficio', $numeroOficio)->exists()) {
            $this->omitidos++;
            $this->errores[] = "Oficio \"$numeroOficio\" ya existe — omitido.";
            return null;
        }

        // Buscar paciente por DNI
        $dni      = $row['dni_paciente'] ?? $row['dni'] ?? null;
        $paciente = $dni ? Paciente::where('dni', $dni)->first() : null;

        // Si no existe el paciente, crearlo con los datos disponibles
        if (!$paciente && $dni) {
            $nombre   = $row['nombre_paciente'] ?? $row['nombre'] ?? 'Sin nombre';
            $apellido = $row['apellido_paciente'] ?? $row['apellido'] ?? 'Sin apellido';
            $paciente = Paciente::create([
                'nombre'   => $nombre,
                'apellido' => $apellido,
                'dni'      => $dni,
            ]);
        }

        if (!$paciente) {
            $this->omitidos++;
            $this->errores[] = "Oficio \"$numeroOficio\" sin paciente válido — omitido.";
            return null;
        }

        // Buscar juzgado por nombre (parcial)
        $nombreJuzgado = $row['juzgado'] ?? null;
        $juzgado = null;
        if ($nombreJuzgado) {
            $juzgado = Juzgado::where('nombre', 'like', "%$nombreJuzgado%")->first();
            // Si no existe, crearlo
            if (!$juzgado) {
                $juzgado = Juzgado::create([
                    'nombre'   => $nombreJuzgado,
                    'ciudad'   => $row['ciudad'] ?? 'San Juan',
                    'contacto' => null,
                ]);
            }
        }

        if (!$juzgado) {
            $this->omitidos++;
            $this->errores[] = "Oficio \"$numeroOficio\" sin juzgado válido — omitido.";
            return null;
        }

        // Parsear fecha
        $fechaRecepcion = $this->parseDate($row['fecha_recepcion'] ?? $row['fecha'] ?? null);
        if (!$fechaRecepcion) {
            $fechaRecepcion = now()->toDateString();
        }

        // Medio de recepción
        $medio = strtolower($row['medio_recepcion'] ?? $row['medio'] ?? 'papel');
        if (!in_array($medio, ['papel', 'email', 'whatsapp'])) {
            $medio = 'papel';
        }

        $this->importados++;

        return new Oficio([
            'numero_oficio'   => $numeroOficio,
            'juzgado_id'      => $juzgado->id,
            'paciente_id'     => $paciente->id,
            'fecha_recepcion' => $fechaRecepcion,
            'medio_recepcion' => $medio,
            'tipo_pedido'     => $row['tipo_pedido'] ?? $row['tipo'] ?? 'Sin especificar',
            'estado'          => 'pendiente',
            'observaciones'   => $row['observaciones'] ?? null,
        ]);
    }

    private function parseDate(?string $value): ?string
    {
        if (!$value) return null;
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getImportados(): int  { return $this->importados; }
    public function getOmitidos(): int    { return $this->omitidos; }
    public function getErrores(): array   { return $this->errores; }
}
