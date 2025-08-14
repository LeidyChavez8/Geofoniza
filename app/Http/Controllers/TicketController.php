<?php

namespace App\Http\Controllers;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Data;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{    
    private function getFileContents($path)
    {
        // Check if the path is a remote URL (e.g., from Google Drive)
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            // It's a URL, read it directly.
            // This requires allow_url_fopen to be enabled in php.ini
            return file_get_contents($path);
        }

        // It's a local path, read it from the local disk.
        // Assumes it's a relative path saved in the database.
        $localPath = Storage::disk('public')->path($path);
        return file_get_contents($localPath);
    }

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
        try {
            $firmaUsuarioContenido = $this->getFileContents($data->firmaUsuario);
            $firmaUsuarioBase64 = base64_encode($firmaUsuarioContenido);
            $data->firmaUsuario = 'data:image/png;base64,' . $firmaUsuarioBase64;
        } catch (\Exception $e) {
            // Handle cases where the file might be missing
            $data->firmaUsuario = ''; // or a placeholder
        }

        // FIRMA DEL ADMIN
        try {
            $firmaTecnicoContenido = $this->getFileContents($data->firmaTecnico);
            $firmaTecnicoBase64 = base64_encode($firmaTecnicoContenido);
            $data->firmaTecnico = 'data:image/png;base64,' . $firmaTecnicoBase64;
        } catch (\Exception $e) {
            $data->firmaTecnico = ''; // or a placeholder
        }

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
        try {
            $firmaUsuarioContenido = $this->getFileContents($data->firmaUsuario);
            $firmaUsuarioBase64 = base64_encode($firmaUsuarioContenido);
            $data->firmaUsuario = 'data:image/png;base64,' . $firmaUsuarioBase64;
        } catch (\Exception $e) {
            $data->firmaUsuario = '';
        }

        // FIRMA DEL ADMIN
        try {
            $firmaTecnicoContenido = $this->getFileContents($data->firmaTecnico);
            $firmaTecnicoBase64 = base64_encode($firmaTecnicoContenido);
            $data->firmaTecnico = 'data:image/png;base64,' . $firmaTecnicoBase64;
        } catch (\Exception $e) {
            $data->firmaTecnico = '';
        }

        $pdf = Pdf::loadView('pdf.revisionTecnica', compact('data'));

        // Devolver el PDF como respuesta para visualizarlo en el navegador
        return $pdf->stream('carta.pdf');
    }

    public function generateRemision($id)
    {
        $data = Data::findOrFail($id);

        // FIRMA DEL Líder de Proyecto e innovación
        // Esta línea ya estaba bien, ya que usa storage_path
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
