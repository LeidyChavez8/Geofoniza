<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Data;
use Storage;

class TicketController extends Controller
{
    public function generateTicket($id)
    {
        // Actualizar los datos en la base de datos
        $data = Data::findOrFail($id);

        // Generar el ticket PDF y guardarlo temporalmente
        $pdf = PDF::loadView('pdf.ticket', ['data' => $data])
                ->setPaper([0, 0, 227, 400], 'portrait'); // Tamaño ajustado
                
        return $pdf->stream();
    }
    public function downloadTicket($id)
    {
        // Actualizar los datos en la base de datos
        $data = Data::findOrFail($id);

        // Generar el ticket PDF y guardarlo temporalmente
        $pdf = PDF::loadView('pdf.ticket', ['data' => $data])
                ->setPaper([0, 0, 227, 400], 'portrait'); // Tamaño ajustado

        return $pdf->download('ticket'.$data->orden .'.pdf');
    }

    public function showTicketOptions($id)
    {
        // Obtener los datos del registro
        $data = Data::findOrFail($id);

        // // Pasar los datos a la vista
        return view('Data.DataUser.download', compact('data'));
    }
}
