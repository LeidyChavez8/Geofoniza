<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Data;

class TicketController extends Controller
{
    public function generateTicket(Request $request, $id)
    {
        // Consultar los datos de la tabla Data por ID
        $data = Data::findOrFail($id); // Busca el registro por ID o lanza una excepción si no existe

        // Pasar los datos a la vista
        $pdf = PDF::loadView('pdf.ticket', ['data' => $data])
                   ->setPaper([0, 0, 227, 842], 'portrait'); // Tamaño personalizado para un ticket (80mm x 297mm)

        // Mostrar el PDF en el navegador si se pasa el parámetro "preview" en la URL
        if ($request->has('preview')) {
            return $pdf->stream('ticket.pdf');
        }

        // Retornar el PDF como descarga por defecto
        return $pdf->download('ticket.pdf');
    }
}
