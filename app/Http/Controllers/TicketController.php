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
        // Check if the path is a remote URL
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            // Read directly from the URL. Requires allow_url_fopen to be enabled.
            return @file_get_contents($path);
        }

        // It's a local path, so we use Laravel's Storage facade to handle it.
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->get($path);
        }

        return false;
    }

    /**
     * Convertir imagen a Base64 para usar en el PDF.
     */
    private function convertToBase64($path)
    {
        try {
            $contenido = $this->getFileContents($path);
            if ($contenido === false) {
                return ''; // Si el contenido no se puede leer, devuelve vacío
            }
            return 'data:image/png;base64,' . base64_encode($contenido);
        } catch (\Exception $e) {
            return ''; // En caso de cualquier error, devolver vacío
        }
    }

    /**
     * Generar Ticket en PDF.
     */
    public function generateTicket($id, Request $request)
    {
        $data = Data::findOrFail($id);

        if ($data->estado != 1) {
            abort(404);
        }

        if (Auth::user()->rol != 'admin' && $data->id_user != Auth::id()) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }

        // Firmas en Base64
        $data->firmaUsuario = $this->convertToBase64($data->firmaUsuario);
        $data->firmaTecnico = $this->convertToBase64($data->firmaTecnico);

        // Generar PDF
        $pdf = PDF::loadView('pdf.ticket', ['data' => $data])
            ->setPaper([0, 0, 227, 830], 'portrait');

        $pdf->render();

        if ($request->routeIs('ticket.generate')) {
            return $pdf->stream();
        } elseif ($request->routeIs('ticket.download')) {
            return $pdf->download('ticket' . $data->orden . '.pdf');
        }
    }

    /**
     * Mostrar opciones de ticket.
     */
    public function showTicketOptions($id)
    {
        $data = Data::findOrFail($id);
        return view('Data.DataUser.download', compact('data'));
    }

    /**
     * Generar Acta PDF.
     */
    public function generateActa($id)
    {
        $data = Data::findOrFail($id);

        // Firmas
        $data->firmaUsuario = $this->convertToBase64($data->firmaUsuario);
        $data->firmaTecnico = $this->convertToBase64($data->firmaTecnico);

        $pdf = Pdf::loadView('pdf.revisionTecnica', compact('data'));

        return $pdf->stream('carta.pdf');
    }

    /**
     * Generar Remisión PDF.
     */
    public function generateRemision($id)
    {
        $data = Data::findOrFail($id);

        // Firma fija del líder de proyecto
        $path = 'firmas/remision/santiago_firma.png'; // Corregida la ruta
        $data->firma = $this->convertToBase64($path);

        // Cargar relación
        $data->load('detalleVisita.servicio');

        $pdf = Pdf::loadView('pdf.remisionCotizacion', compact('data'));

        return $pdf->stream('carta.pdf');
    }
}
