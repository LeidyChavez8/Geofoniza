<?php
namespace App\Exports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Storage;

class DataExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $ciclo;

    public function __construct($ciclo = null)
    {
        $this->ciclo = $ciclo;
    }

    public function query()
    {
        // Aplicar filtro de ciclo si se proporciona y asegurar que estado sea 1
        return Data::query()
            ->where('estado', 1)
            ->when($this->ciclo, function ($query) {
                return $query->where('ciclo', $this->ciclo);
            });
    }

    public function headings(): array
    {
        return [
            'Contrato',
            'Producto',
            'Nombres',
            'Calificación',
            'Categoría',
            'Dirección',
            'Ubicación',
            'Medidor',
            'Orden',
            'Lectura Anterior',
            'Fecha Lectura Anterior',
            'Observación Lectura Anterior',
            'Ciclo',
            'Recorrido',
            'Lectura',
            'Observación Inspección',
            'URL Foto',
            'Inspector',
            'Firma',
        ];
    }

    public function map($data): array
    {
        return [
            $data->contrato,
            $data->producto,
            $data->nombres,
            $data->calificacion,
            $data->categoria,
            $data->direccion,
            $data->ubicacion,
            $data->medidor,
            $data->orden,
            $data->lectura_anterior,
            $data->fecha_lectura_anterior,
            $data->observacion_lectura_anterior,
            $data->ciclo,
            $data->recorrido,
            $data->lectura,
            $data->observacion_inspeccion,
            $data->url_foto,
            $data->user->name, 
            $data->firma, 
            // optional($data->user)->name,
            // optional($data->firma),
        ];
    }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $column = 'S'; // Columna donde se colocará la imagen (ajusta si es necesario)
    
                for ($row = 2; $row <= $highestRow; $row++) { // Inicia en 2 para evitar encabezados
                    $cellValue = $sheet->getCell($column . $row)->getValue();
    
                    if ($cellValue) {
                        $filePath = Storage::disk('public')->path($cellValue); // Ruta completa
    
                        // Verificar si el archivo existe
                        if (file_exists($filePath)) {
                            $drawing = new Drawing();
                            $drawing->setName('Firma');
                            $drawing->setDescription('Firma');
                            $drawing->setPath($filePath); // Establecer la ruta de la imagen
                            $drawing->setHeight(40); // Ajusta el tamaño de la imagen
                            $drawing->setCoordinates($column . $row); // Coordenadas de la celda
                            $drawing->setWorksheet($sheet); // Asignar la hoja
    
                            // Redimensionar la celda para coincidir con la imagen
                            $sheet->getColumnDimension($column)->setWidth(15);
                            $sheet->getRowDimension($row)->setRowHeight(35);
    
                            // Limpiar el contenido de la celda
                            $sheet->getCell($column . $row)->setValue(null);
                        } else {
                            // Si no existe, escribir un mensaje de error
                            $sheet->getCell($column . $row)->setValue('Imagen no encontrada');
                        }
                    }
                }

                // Aplicar estilo a los encabezados
                $sheet->getStyle('A1:S1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // Aplicar formato numérico a la columna 'recorrido'
                $sheet->getStyle('F2:F' . $highestRow)->getNumberFormat()->setFormatCode('0');

                // Alinear todos los datos a la izquierda
                $sheet->getStyle('A1:M' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                ]);
            },
        ];
    }
}
