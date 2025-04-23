<?php

namespace App\Http\Controllers;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Data;
use Storage;

class TicketController extends Controller
{   
    public function generateTicket($id, Request $request)
    {
        $data = Data::findOrFail($id);

        if($data->estado != 1) {
            abort(404);
        }

        if(Auth::user()->rol != 'admin'){
            if ($data->id_user != Auth::id()) {
                abort(403, 'No tienes permiso para ver este ticket.');
            }
        }

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
                ->setPaper([0, 0, 227, 830], 'portrait'); // Tamaño ajustado
                
        $pdf->render();

        if($request->routeIs('ticket.generate')) {
            return $pdf->stream();
        } else if($request->routeIs('ticket.download')) {
            return $pdf->download('ticket'.$data->orden .'.pdf');
        }
    }

    public function showTicketOptions($id)
    {
        // Obtener los datos del registro
        $data = Data::findOrFail($id);

        // // Pasar los datos a la vista
        return view('Data.DataUser.download', compact('data'));
    }

    public function generateActa($id)
    {

        $data = Data::findOrFail($id);
        // Generar el PDF usando la vista

        // FIRMA DEL USUARIO
        $firmaUsuario = file_get_contents($data->firmaUsuario);
        
        $firmaUsuarioBase64 = base64_encode($firmaUsuario);

        $data->firmaUsuario = 'data:image/png;base64,' . $firmaUsuarioBase64;

    // FIRMA DEL ADMIN
        $firmaTecnico = file_get_contents($data->firmaTecnico);

        $firmaTecnicoBase64 = base64_encode($firmaTecnico);

        $data->firmaTecnico = 'data:image/png;base64,' . $firmaTecnicoBase64;


        $pdf = Pdf::loadView('pdf.revisionTecnica', compact('data'));

        // Devolver el PDF como respuesta para visualizarlo en el navegador
        return $pdf->stream('carta.pdf');
    }


    public function generateRemision($id)
    {
        $data = Data::findOrFail($id);

        // FIRMA DEL Líder de Proyecto e innovación
        $path = storage_path('app/public/firmas/remision/santiago_firma.png');
        
        $firmaBase64 = base64_encode(file_get_contents($path));

        $data->firma = 'data:image/png;base64,' . $firmaBase64;

        // Obtener datos para el PDF (puedes ajustar esta consulta según tus necesidades)

        $data->load('detalleVisita.servicio');

        // Generar el PDF usando la vista
        $pdf = Pdf::loadView('pdf.remisionCotizacion',compact('data'));

        // Devolver el PDF como respuesta para visualizarlo en el navegador
        return $pdf->stream('carta.pdf');
    }


}
