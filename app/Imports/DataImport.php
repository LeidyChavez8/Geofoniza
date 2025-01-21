<?php

namespace App\Imports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class DataImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar la fila de encabezado

            // Mapear los datos de Excel a las columnas de la base de datos
            $data = [
                'ciclo' => $row[0] ?? null,
                'nombre_cliente' => $row[7] ?? null,
                'email' => $row[10] ?? null,
                'telefono' => $row[9] ?? null,
                'firma' => $row[11] ?? null,
                'cuenta' => $row[3] ?? null,
                'direccion' => $row[4] ?? null,
                'recorrido' => $row[5] ?? null,
                'medidor' => $row[6] ?? null,
                'año' => $row[1] ?? null,
                'mes' => $row[2] ?? null,
                'periodo' => $row[8] ?? null,
                'id_operario' => null, // Aquí puedes definir el valor que necesites
                'estado' => null // Aquí puedes definir el valor que necesites
            ];

            // Verificar si el registro ya existe en la base de datos por 'cuenta'
            $existingRecord = Data::where('cuenta', $data['cuenta'])->first();

            if (!$existingRecord) {
                // Insertar nuevo registro si no existe
                Data::create($data);
            }

            // Si el registro ya existe, no se actualiza ni se modifica
        }
    }
}
