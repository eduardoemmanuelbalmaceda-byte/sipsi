<?php

namespace App\Imports;

use App\Models\Paciente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class PacientesImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors;
    use SkipsFailures;

    private int $importados = 0;
    private int $omitidos   = 0;

    public function model(array $row): ?Paciente
    {
        // Normalizar claves: quitar espacios y pasar a minúsculas
        $row = array_change_key_case(array_map('trim', $row), CASE_LOWER);

        $dni = $row['dni'] ?? null;

        if (!$dni) {
            $this->omitidos++;
            return null;
        }

        // Si ya existe el DNI, omitir
        if (Paciente::where('dni', $dni)->exists()) {
            $this->omitidos++;
            return null;
        }

        $this->importados++;

        return new Paciente([
            'nombre'           => $row['nombre']           ?? '',
            'apellido'         => $row['apellido']         ?? '',
            'dni'              => $dni,
            'fecha_nacimiento' => $this->parseDate($row['fecha_nacimiento'] ?? null),
            'telefono'         => $row['telefono']         ?? null,
            'direccion'        => $row['direccion']        ?? null,
        ]);
    }

    private function parseDate(?string $value): ?string
    {
        if (!$value) return null;
        // Intentar parsear distintos formatos
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getImportados(): int { return $this->importados; }
    public function getOmitidos(): int   { return $this->omitidos; }
}
