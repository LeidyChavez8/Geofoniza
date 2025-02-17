<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Data;
use Storage;

class TicketController extends Controller
{
    public function generateTicket(Request $request, $id)
    {
        // Actualizar los datos en la base de datos
        $data = Data::findOrFail($id);
        $data->update($request->all());

        // Generar el ticket PDF y guardarlo temporalmente
        $pdf = PDF::loadView('pdf.ticket', ['data' => $data])
                ->setPaper([0, 0, 227, 400], 'portrait'); // Tamaño ajustado

        // Asegurarse de que la carpeta 'tickets' exista
        Storage::disk('public')->makeDirectory('tickets');

        // Guardar el PDF temporalmente
        $pdfPath = storage_path('app/public/tickets/ticket_' . $data->id . '.pdf');
        file_put_contents($pdfPath, $pdf->output());

        // Redirigir a la vista `download_ticket` con los datos necesarios
        return redirect()->route('download.ticket', ['id' => $data->id])->with('success', 'Registro actualizado correctamente.');;
    }

    public function showDownloadOptions($id)
    {
        // Obtener los datos del registro
        $data = Data::findOrFail($id);

        // Generar las URLs para visualizar y descargar el ticket
        $ticketUrl = asset('storage/tickets/ticket_' . $data->id . '.pdf');

        // Pasar los datos a la vista
        return view('Data.DataUser.download', [
            'ticketUrl' => $ticketUrl,
            'data' => $data,
        ]);
    }
}
