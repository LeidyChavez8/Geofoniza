<?php

namespace App\Imports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class DataUpdateImport implements ToCollection
{
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
            ];

            // Buscar el registro existente
            $existingRecord = Data::where('medidor', $data['medidor'])
                ->where('cuenta', $data['cuenta'])
                ->first();

            if ($existingRecord) {
                // Actualizar todos los campos excepto 'medidor'
                $existingRecord->update([
                    'ciclo' => $data['ciclo'],
                    'nombre_cliente' => $data['nombre_cliente'],
                    'email' => $data['email'],
                    'telefono' => $data['telefono'],
                    'firma' => $data['firma'],
                    'cuenta' => $data['cuenta'],
                    'direccion' => $data['direccion'],
                    'recorrido' => $data['recorrido'],
                    'año' => $data['año'],
                    'mes' => $data['mes'],
                    'periodo' => $data['periodo']
                ]);
            }
        }
    }
}
