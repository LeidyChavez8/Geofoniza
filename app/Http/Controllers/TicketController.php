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
        $data = Data::findOrFail($id);

    // FIRMA DEL USUARIO
        $firmaUsuario = file_get_contents($data->firmaUsuario);
    
        $firmaUsuarioBase64 = base64_encode($firmaUsuario);

        $data->firmaUsuario = 'data:image/png;base64,' . $firmaUsuarioBase64;

    // FIRMA DEL ADMIN
        $firmaTecnico = file_get_contents($data->firmaTecnico);
    
        $firmaTecnicoBase64 = base64_encode($firmaTecnico);

        $data->firmaTecnico = 'data:image/png;base64,' . $firmaTecnicoBase64;

        // Generar el ticket PDF y guardarlo temporalmente
        $pdf = PDF::loadView('pdf.ticket', ['data' => $data])
                ->setPaper([0, 0, 227, 400], 'portrait'); // Tamaño ajustado
                
        $pdf->render();
        return $pdf->stream();
    }
    public function downloadTicket($id)
    {

        $data = Data::findOrFail($id);

        // FIRMA DEL USUARIO
            $firmaUsuario = file_get_contents($data->firmaUsuario);

            $firmaUsuarioBase64 = base64_encode($firmaUsuario);

            $data->firmaUsuario = 'data:image/png;base64,' . $firmaUsuarioBase64;
            
        // FIRMA DEL ADMIN
            $firmaTecnico = file_get_contents($data->firmaTecnico);
        
            $firmaTecnicoBase64 = base64_encode($firmaTecnico);
    
            $data->firmaTecnico = 'data:image/png;base64,' . $firmaTecnicoBase64;

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
