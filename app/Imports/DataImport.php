<?php

namespace App\Imports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class DataImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue;
            // Mapear los datos de Excel a las columnas de la base de datos
            $data = [
                'contrato' => $row[0] ?? null,
                'producto' => $row[1] ?? null,
                'nombres' => $row[2] ?? null,
                'calificacion' => $row[3] ?? null,
                'categoria' => $row[4] ?? null,
                'direccion' => $row[5] ?? null,
                'ubicacion' => $row[6] ?? null,
                'medidor' => $row[7] ?? null,
                'orden' => $row[8] ?? null,
                'lectura_anterior' => $row[9] ?? null,
                'fecha_lectura_anterior' => $row[10] ?? null,
                'observacion_lectura_anterior' => $row[11] ?? null,
                'ciclo' => $row[12] ?? null,
                'recorrido' => $row[13] ?? null,

                'lectura' => null,
                'observacion_inspeccion' => null,
                'url_foto' => null,
                'firma' => null,

                
                'id_user' => null,
                'estado' => null, // estado de actualizacion de la lectura
            ];

            // Verificar si el registro ya existe en la base de datos por 'contrato'
            $existingRecord = Data::where('contrato', $data['contrato'])->first();

            if (!$existingRecord) {
                // Insertar nuevo registro si no existe
                Data::create($data);
            }

            // Si el registro ya existe, no se actualiza ni se modifica
        }
    }
}
