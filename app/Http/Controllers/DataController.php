<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Exports\DataExportComplete;
use App\Imports\DataImport;
use App\Imports\DataUpdateImport;
use App\Models\Data;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
    // =============================    SIDEBAR     =============================
    public function sidebarSearch(Request $request)
    {
        $query = $request->input('buscador-sidebar');

        // Lista de secciones disponibles
        $secciones = [
            'inicio' => route('home'),
            'usuarios' => route('users.index'),
            'asignar' => route('asignar.index'),
            'desasignar' => route('desasignar.index'),
            'completados' => route('completados.index'),
            'cargar excel' => route('import.import'),
            'generar excel' => route('export'),
        ];

        // Busca una coincidencia en las secciones
        $resultado = array_filter($secciones, function($seccion) use ($query){
            return stripos($seccion, $query) !== false; // Buscar la coincidencia
        }, ARRAY_FILTER_USE_KEY);

        // Si encuentra una sección, redirige
        if (!empty($resultado)) {
            return redirect(array_values($resultado)[0]);
        }

        // Si no encuentra una sección, redirige al inicio
        return back()->with('error-sidebar', 'Sección no encontrada.');
    }

    // =============================      ASIGNACION      =============================

    public function asignarIndex(Request $request)
    {
        // Obtener los parámetros de ordenamiento
        $sortBy = $request->get('sortBy', 'direction');
        $direction = $request->get('direction', 'asc');

        // Validar que la columna y la dirección sean válidas
        $validColumns = ['nombres', 'direccion', 'barrio', 'telefono', 'correo', 'orden'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Obtener los datos ordenados solo si no están relacionados con un usuario
        $data = Data::whereNull('id_user')
            ->orWhere('id_user', '')
            ->orderBy($sortBy, $direction)
            ->paginate(100);

        $operarios = User::where('rol', 'user')
            ->orderBy('name', 'asc')
            ->get();

        $totalResultados = Data::where('id_user', null)->count();

        return view('Data.Asignacion.asignacion', [
            'data' => $data,
            'operarios' => $operarios,
            'totalResultados' => $totalResultados,
            'sortBy' => $sortBy,
            'direction' => $direction,
        ]);
    }

    public function asignarFiltrar(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id'); // Columna de orden por defecto
        $direction = $request->get('direction', 'asc'); // Dirección de orden por defecto

        // Validar la columna y la dirección de ordenamiento
        $validColumns = ['nombres', 'direccion', 'barrio', 'telefono', 'correo', 'orden'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Genera la consulta donde 
        // -> id_user sea null para no incluir los ya asignados
        // -> estado sea null para no incluir los ya completados
        $query = Data::whereNull('id_user')
            ->whereNull('estado');

        if ($request->filled('buscador-nombre')) {
            $query->where('nombres', 'like', '%' . $request->input('buscador-nombre') . '%');
        }

        if ($request->filled('buscador-direccion')) {
            $query->where('direccion', 'like', '%' . $request->input('buscador-direccion') . '%');
        }

        if ($request->filled('buscador-barrio')) {
            $query->where('barrio', 'like', '%' . $request->input('buscador-barrio') . '%');
        }

        if ($request->filled('buscador-telefono')) {
            $query->where('telefono', 'like', '%' . $request->input('buscador-telefono') . '%');
        }

        if ($request->filled('buscador-correo')) {
            $query->where('correo', 'like', '%' . $request->input('buscador-correo') . '%');
        }
        
        if ($request->filled('buscador-orden')) {
            $query->where('orden', 'like', '%' . $request->input('buscador-orden') . '%');
        }

        

        // Aplicar el ordenamiento
        $data = $query
            ->orderBy($sortBy, $direction)
            ->paginate(100)
            ->appends($request->except('page')); // Conservar parámetros en la paginación

        $operarios = User::where('rol', 'user')->get();
        $totalResultados = $query->count();

        return view('Data.Asignacion.asignacion', compact('data', 'operarios', 'sortBy', 'direction', 'totalResultados'));
    }

    public function asignarOperario(Request $request)
    {
        $request->validate([
            'Programacion' => 'required|array',
            'operario' => 'required|exists:users,id'
        ]);

        $programaciones = $request->input('Programacion');
        $userId = $request->input('operario');

        Data::whereIn('id', $programaciones)->update(['id_user' => $userId]);

        return redirect()->route('asignar.index')->with('success', 'Operario asignado exitosamente');
    }

    // =============================      DESASIGNACION      =============================
    public function desasignarIndex(Request $request)
    {
        // Obtener los parámetros de ordenamiento
        $sortBy = $request->get('sortBy', 'id'); // Columna por defecto
        $direction = $request->get('direction', 'asc'); // Dirección por defecto

        // Validar que la columna y la dirección sean válidas
        $validColumns = ['operario', 'nombres', 'direccion', 'barrio', 'telefono', 'correo', 'orden'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Obtener los datos ordenados
        $data = Data::whereNotNull('id_user')
            ->whereNull('estado')
            ->orderBy($sortBy, $direction)
            ->paginate(100);

        $operarios = User::where('rol', 'user')
            ->orderBy('name', 'asc')
            ->get();

        $totalResultados = Data::whereNotNull('id_user')->count();

        return view('Data.Asignacion.desasignacion', [
            'data' => $data,
            'operarios' => $operarios,
            'totalResultados' => $totalResultados,
            'sortBy' => $sortBy,
            'direction' => $direction,
        ]);
    }

    public function desasignarFiltrar(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id'); // Columna de orden por defecto
        $direction = $request->get('direction', 'asc'); // Dirección de orden por defecto

        // Validar la columna y la dirección de ordenamiento
        $validColumns = ['operario', 'nombres', 'direccion', 'barrio', 'telefono', 'correo', 'orden'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Construir la consulta con filtros
        $query = Data::whereNotNull('id_user')
            ->whereNull('estado');

        if ($request->filled('buscador-operario')) {
            // buscamos el id del usuario por el nombre
            $userId = User::where('name', 'like', '%' . $request->input('buscador-operario') . '%')->pluck('id');
            // filtramos por el id del usuario
            $query->whereIn('id_user', $userId);
        }
        if ($request->filled('buscador-nombre')) {
            $query->where('nombres', 'like', '%' . $request->input('buscador-nombre') . '%');
        }

        if ($request->filled('buscador-direccion')) {
            $query->where('direccion', 'like', '%' . $request->input('buscador-direccion') . '%');
        }

        if ($request->filled('buscador-barrio')) {
            $query->where('barrio', 'like', '%' . $request->input('buscador-barrio') . '%');
        }

        if ($request->filled('buscador-telefono')) {
            $query->where('telefono', 'like', '%' . $request->input('buscador-telefono') . '%');
        }

        if ($request->filled('buscador-correo')) {
            $query->where('correo', 'like', '%' . $request->input('buscador-correo') . '%');
        }
        
        if ($request->filled('buscador-orden')) {
            $query->where('orden', 'like', '%' . $request->input('buscador-orden') . '%');
        }
        

        // Aplicar el ordenamiento
        $data = $query
            ->orderBy($sortBy, $direction)
            ->paginate(100)
            ->appends($request->except('page')); // Conservar parámetros en la paginación

        $operarios = User::where('rol', 'user')->get();
        $totalResultados = $query->count();

        return view('Data.Asignacion.desasignacion', compact('data', 'operarios', 'sortBy', 'direction', 'totalResultados'));
    }

    public function desasignarOperario(Request $request)
    {
        $request->validate([
            'Programacion' => 'required|array',
        ]);

        $programaciones = $request->input('Programacion');

        Data::whereIn('id', $programaciones)->update(['id_user' => null]);

        return redirect()->route('desasignar.index')->with('success', 'Operario desasignado exitosamente');
    }

    // =============================      USERDATA      =============================
    public function asignadosListar(Request $request)
    {
        $userId = Auth::user()->id;
        session(['previous_url' => $request->fullUrl()]);


        // Crear la consulta base
        $query = Data::where('id_user', $userId)
            ->where(function ($query) {
                $query->where('estado', 0)
                    ->orWhereNull('estado');
            });

        // Obtener los datos paginados
        $data = $query->paginate(1);

        return view('Data.DataUser.index', compact('data'));
    }

    public function asignadosEdit($id)
    {
        $data = Data::findOrFail($id);

        $resultado = [
            "Fuga Imperceptible",
            "Fuga Perceptible",
            "Medidor Instalado en Reversa",
            "Predio sin de Fuga",       
            "Fuga No Visible No Localizada",
            "Sector sin Suministro de Agua",
            "Acceso Dificultoso",
            "Revisión Inconclusa",
            "Fuga Visible",     
            "Fuga En Instalación",
            "No Hay Medidor en el Predio",
        ];

        $data -> resultado = $resultado;
        return view('Data.DataUser.edit', compact('data'));
    }


    public function asignadosUpdate(Request $request, $id)
    {
        $data = Data::findOrFail($id);

        $direccion = $data->direccion;
        
        $request->merge([
            'numeroPersonas' => (int) $request->numeroPersonas,
        ]);
        
        $validatedData = $request->validate([
            'numeroPersonas' => 'required|integer',
            'categoria' => 'required|string|in:residencial,comercial,industrial',
            'puntoHidraulico' => 'required|integer',
            'medidor' => 'required|string',
            'lectura' => 'required|string',
            'aforo' => 'required|string',
            'observacion_inspeccion' => 'required|string',
            'resultado' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,bmp,tiff|max:51200',
            'firmaUsuario' => 'required|string',
            'firmaTecnico' => 'required|string',
        ], [
            'numeroPersonas.required' => 'El número de personas es obligatorio.',
            'numeroPersonas.integer' => 'El número de personas debe ser un número válido.',
        
            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in' => 'La categoría debe ser Residencial, Comercial o Industrial.',
        
            'puntoHidraulico.required' => 'El punto hidráulico es obligatorio.',
            'puntoHidraulico.integer' => 'El punto hidráulico debe ser un número válido.',
        
            'medidor.required' => 'El medidor es obligatorio.',
            'medidor.string' => 'El medidor debe ser un texto válido.',
        
            'lectura.required' => 'La lectura es obligatoria.',
            'lectura.integer' => 'La lectura debe ser un número entero.',
        
            'aforo.required' => 'El aforo es obligatorio.',
            'aforo.string' => 'El aforo debe ser un texto válido.',
        
            'observacion_inspeccion.required' => 'La observación es obligatoria.',
            'observacion_inspeccion.string' => 'La observación debe ser un texto válido.',
        
            'resultado.required' => 'El resultado es obligatorio.',
            'resultado.string' => 'El resultado debe ser un texto válido.',
        
            'foto.required' => 'La evidencia es obligatoria.',
            'foto.image' => 'La evidencia debe ser una imagen válida.',
            'foto.mimes' => 'La evidencia debe estar en formato JPEG, PNG, JPG, BMP o TIFF.',
            'foto.max' => 'La evidencia no debe superar los 50MB.',
        
            'firmaUsuario.required' => 'La firma del usuario es obligatoria.',
            'firmaUsuario.string' => 'La firma del usuario debe ser un texto válido.',
        
            'firmaTecnico.required' => 'La firma del técnico es obligatoria.',
            'firmaTecnico.string' => 'La firma del técnico debe ser un texto válido.',
        ]);

        $data->fill($validatedData);


        // Subir la foto a Google Drive
        $mesActual = date('F');
        $userFolder = auth()->user()->name; // Subcarpeta personalizada por usuario
        $fileName = $validatedData['foto']->getClientOriginalName();
        $filePath = "Apptualiza/"."$mesActual/$userFolder/$direccion/evidencia/$fileName";

        // Subida de foto
        Gdrive::put($filePath, $validatedData['foto']);

        // Establecer permisos públicos
        $disk = Storage::disk('google');
        $fileMetaData = $disk->getAdapter()->getService()->files->listFiles([
            'q' => "name='$fileName'"
        ]);

        if (!empty($fileMetaData->getFiles())) {
            $fileId = $fileMetaData->getFiles()[0]->getId();
            $permissions = new \Google\Service\Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $disk->getAdapter()->getService()->permissions->create($fileId, $permissions);

            // Obtener URL pública
            $fileUrl = "https://drive.google.com/uc?id=$fileId";
        } else {
            $fileUrl = null;
        }

        $data ->url_foto = $fileUrl;
        

        try {
            if ($request->has('firmaUsuario')) {
                $image = $request->input('firmaUsuario');
                $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);

                $imageData = base64_decode($image);
            
            
                // Crear un archivo temporal
                $tempFile = tempnam(sys_get_temp_dir(), 'upload_');
                file_put_contents($tempFile, $imageData);
            
                $_FILES['image'] = [
                    'name'     => 'imagen.png',
                    'type'     => 'image/png',
                    'tmp_name' => $tempFile,
                    'error'    => 0,
                    'size'     => filesize($tempFile)
                ];

                $fileName =uniqid(). '.png';
                $filePath = "Apptualiza/$mesActual/$userFolder/$direccion/firma del usuario/$fileName";
            

                Gdrive::put($filePath, $_FILES['image']['tmp_name']);                
                $fileMetaData = $disk->getAdapter()->getService()->files->listFiles([
                    'q' => "name='$fileName'"
                ]);
                
                // Configurar permisos y obtener URL pública
                if (!empty($fileMetaData->getFiles())) {
                    $fileId = $fileMetaData->getFiles()[0]->getId();
                    $permissions = new \Google\Service\Drive\Permission([
                        'type' => 'anyone',
                        'role' => 'reader',
                    ]);
                    $disk->getAdapter()->getService()->permissions->create($fileId, $permissions);
            
                    // Generar URL pública
                    $fileUrl = "https://drive.google.com/uc?id=$fileId";
                } else {
                    $fileUrl = null;
                }

                $data->firmaUsuario = $fileUrl;
            
                unlink($tempFile);
            }
            

            if($request->has('firmaTecnico')){
                try {
                    $image = $request->input('firmaTecnico');
                    $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
    
                    $imageData = base64_decode($image);
        
                   // Crear un archivo temporal
                    $tempFile = tempnam(sys_get_temp_dir(), 'upload_');
                    file_put_contents($tempFile, $imageData);
                
                    $_FILES['image'] = [
                        'name'     => 'imagen.png',
                        'type'     => 'image/png',
                        'tmp_name' => $tempFile,
                        'error'    => 0,
                        'size'     => filesize($tempFile)
                    ];

                    $fileName =uniqid(). '.png';
                    $filePath = "Apptualiza/$mesActual/$userFolder/$direccion/firma del tecnico/$fileName";
        

                    Gdrive::put($filePath, $_FILES['image']['tmp_name']);                
                    $fileMetaData = $disk->getAdapter()->getService()->files->listFiles([
                        'q' => "name='$fileName'"
                    ]);
                    
                    // Configurar permisos y obtener URL pública
                    if (!empty($fileMetaData->getFiles())) {
                        $fileId = $fileMetaData->getFiles()[0]->getId();
                        $permissions = new \Google\Service\Drive\Permission([
                            'type' => 'anyone',
                            'role' => 'reader',
                        ]);
                        $disk->getAdapter()->getService()->permissions->create($fileId, $permissions);
                
                        // Generar URL pública
                        $fileUrl = "https://drive.google.com/uc?id=$fileId";
                    } else {
                        $fileUrl = null;
                    }
                    
                    $data->firmaTecnico = $fileUrl;

                } catch (Exception $e){
                    return redirect()->back()->with('error','Error al procesar la firma' . $e->getMessage());
                }

            }

        } catch (Exception $e){
            dd($e->getMessage());
            return redirect()->back()->with('error','Error al procesar la firma' . $e->getMessage());
        }

        $data->estado = 1;

        $data->save();

        return redirect()->route('ticket.options', ['id' => $data->id])->with('success', 'Datos actualizados correctamente');
    }

    // =============================      AGENDAR      =============================
   
     // Mostrar formulario vacío para crear nuevo registro
    public function create()
    {
        return view('Data.Agendar.agendar');
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombres' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'cedula' => 'required|numeric|digits_between:6,10',
            'direccion' => 'required|string|max:255|regex:/^[^#]+$/',
            'barrio' => 'required|string|max:100',
            'telefono' => 'required|digits:10',
            'correo' => 'email|max:255',
            'ciclo' => 'string|max:255', 
        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'nombres.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.numeric' => 'La cédula solo puede contener números.',
            'cedula.digits_between' => 'La cédula debe tener entre 6 y 10 dígitos.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.regex' => 'Solo ingrese caracteres alfanuméricos.',
            'barrio.required' => 'El barrio es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe contener exactamente 10 dígitos.',
            'correo.email' => 'Debe ingresar un correo válido.',
            'ciclo' => 'Debe ingresar un ciclo válido.',
        ]);
        
        Data::create($validatedData);
        return redirect()->route('schedule.create')->with('success', 'Registro creado exitosamente.');
    }

    // =============================      IMPORTAR      =============================
    public function showUploadForm()
    {
        return view('Data.Importar.import');
    }

    public function replaceData(Request $request)
    {
        // dd($request->all());
        // Validar el archivo
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        // Limpiar todos los registros existentes en la tabla Data
        Data::truncate(); // Elimina todos los registros de la tabla

        // Procesar el archivo de importación
        Excel::import(new DataImport, $request->file('file'));

        // Mensaje de éxito
        return redirect()->back()->with('success', 'Datos reemplazados exitosamente.');
    }

    public function addData(Request $request)
    {
        // Validar el archivo
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        // Procesar el archivo de importación
        Excel::import(new DataImport, $request->file('file'));

        // Mensaje de éxito
        return redirect()->back()->with('success', 'Datos agregados exitosamente.');
    }

    // =============================      COMPLETADOS      =============================
    public function completadosIndex(Request $request)
    {

        // Obtener los parámetros de ordenamiento
        $sortBy = $request->get('sortBy', 'id'); // Columna por defecto
        $direction = $request->get('direction', 'asc'); // Dirección por defecto

        // Validar que la columna y la dirección de ordenamiento sean válidas
        $validColumns = ['operario', 'nombres', 'direccion', 'barrio', 'telefono', 'correo', 'orden'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Obtener los datos ordenados
        $datas = Data::where('estado', 1)
            ->orderBy($sortBy, $direction)
            ->paginate(100);

        $totalResultados = Data::where('id_user', null)->count();

        return view('Data.Completados.index', [
            'datas' => $datas,
            'totalResultados' => $totalResultados,
            'sortBy' => $sortBy,
            'direction' => $direction,
        ]);
    }


    public function editCompletados($dataId){
        $data = Data::find($dataId);
        
        $resultados = [
            "Fuga Imperceptible",
            "Fuga Perceptible",
            "Medidor Instalado en Reversa",
            "Predio sin de Fuga",       
            "Fuga No Visible No Localizada",
            "Sector sin Suministro de Agua",
            "Acceso Dificultoso",
            "Revisión Inconclusa",
            "Fuga Visible",     
            "Fuga En Instalación",
            "No Hay Medidor en el Predio",
        ];

        return view('Data.Completados.edit', compact('data', 'resultados'));
    }
    public function updateCompletados(Request $request, $dataId){
        $data = Data::findOrFail($dataId);

        $validatedData = $request->validate([
            // Nuevas validaciones agregadas
            'nombres' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'cedula' => 'required|numeric|digits_between:6,10',
            'direccion' => 'required|string|max:255|regex:/^[^#]+$/',
            'barrio' => 'required|string|max:100',
            'telefono' => 'required|digits:10',
            'correo' => 'nullable|email|max:255',
            'ciclo' => 'nullable|string|max:255',
            'numeroPersonas' => 'required|integer',
            'categoria' => 'required|string|in:residencial,comercial,industrial',
            'puntoHidraulico' => 'required|integer',
            'medidor' => 'required|string',
            'lectura' => 'required|string',
            'aforo' => 'required|string',
            'observacion_inspeccion' => 'required|string',
            'resultado' => 'required|string',
        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'nombres.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.numeric' => 'La cédula solo puede contener números.',
            'cedula.digits_between' => 'La cédula debe tener entre 6 y 10 dígitos.',
            
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.regex' => 'Solo ingrese caracteres alfanuméricos.',
            
            'barrio.required' => 'El barrio es obligatorio.',
            
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe contener exactamente 10 dígitos.',
            
            'correo.email' => 'Debe ingresar un correo válido.',
            
            'ciclo.string' => 'Debe ingresar un ciclo válido.',
        
            'numeroPersonas.required' => 'El número de personas es obligatorio.',
            'numeroPersonas.integer' => 'El número de personas debe ser un número válido.',
            
            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in' => 'La categoría debe ser Residencial, Comercial o Industrial.',
            
            'puntoHidraulico.required' => 'El punto hidráulico es obligatorio.',
            'puntoHidraulico.integer' => 'El punto hidráulico debe ser un número válido.',
            
            'medidor.required' => 'El medidor es obligatorio.',
            'medidor.string' => 'El medidor debe ser un texto válido.',
            
            'lectura.required' => 'La lectura es obligatoria.',
            
            'aforo.required' => 'El aforo es obligatorio.',
            'aforo.string' => 'El aforo debe ser un texto válido.',
            
            'observacion_inspeccion.required' => 'La observación es obligatoria.',
            'observacion_inspeccion.string' => 'La observación debe ser un texto válido.',
            
            'resultado.required' => 'El resultado es obligatorio.',
            'resultado.string' => 'El resultado debe ser un texto válido.',
        ]);

        $data->fill($validatedData);

        $data->estado = 1;

        $data->save();

        return redirect()->route('ticket.options', ['id' => $data->id])->with('success', 'Datos actualizados correctamente');
    }

    public function completadosFiltrar(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id'); // Columna de orden por defecto
        $direction = $request->get('direction', 'asc'); // Dirección de orden por defecto

        // Validar la columna y la dirección de ordenamiento
        $validColumns = ['operario', 'nombres', 'direccion', 'telefono', 'correo', 'orden'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Construir la consulta con filtros
        $query = Data::where('estado', 1);

        if ($request->filled('buscador-operario')) {
            // buscamos el id del usuario por el nombre
            $userId = User::where('name', 'like', '%' . $request->input('buscador-operario') . '%')->pluck('id');
            // filtramos por el id del usuario
            $query->whereIn('id_user', $userId);
        }
        
        if ($request->filled('buscador-orden')) {
            $query->where('orden', 'like', '%' . $request->input('buscador-orden') . '%');
        }

        if ($request->filled('buscador-nombre')) {
            $query->where('nombres', 'like', '%' . $request->input('buscador-nombre') . '%');
        }

        if ($request->filled('buscador-direccion')) {
            $query->where('direccion', 'like', '%' . $request->input('buscador-direccion') . '%');
        }


        // Aplicar el ordenamiento
        $datas = $query
            ->orderBy($sortBy, $direction)
            ->paginate(100)
            ->appends($request->except('page')); // Conservar parámetros en la paginación

        $totalResultados = $query->count();

        return view('Data.Completados.index', compact('datas', 'sortBy', 'direction', 'totalResultados'));
    }

    public function completadosShow($id)
    {
        $data = Data::findOrFail($id); // Encontrar el registro por ID
        return view('Data.show', compact('data'));
    }

    // =============================      EXPORTAR      =============================
    public function exportarIndex()
    {
        $ciclos = Data::select('ciclo')->distinct()->pluck('ciclo');
        return view('Data.Exportar.export', compact('ciclos'));
    }

    public function exportarFiltrar(Request $request)
    {
        // Obtener el valor del ciclo del request
        $ciclo = $request->input('ciclo');

        // Filtrar los datos según el ciclo
        if ($ciclo === '0') {
            // Si el ciclo es 'all', mostrar todos los registros
            $datas = Data::where('estado', 1)->paginate(10);
        } elseif ($ciclo) {
            // Si se ha seleccionado un ciclo específico, filtrar por ese ciclo
            $datas = Data::where('estado', 1)
                ->where('ciclo', $ciclo)
                ->paginate(10);
        } else {
            // Si no se ha seleccionado ningún ciclo, no se deben mostrar resultados
            $datas = collect(); // Esto es equivalente a una consulta vacía
        }

        // Obtener ciclos únicos para el filtro en la vista
        $ciclos = Data::select('ciclo')->distinct()->pluck('ciclo');

        // Si la solicitud es AJAX, devolver los datos como JSON
        if ($request->ajax()) {
            return response()->json([
                'datas' => $datas,
                'ciclos' => $ciclos,
                'pagination' => [
                    'total' => $datas->total(),
                    'current_page' => $datas->currentPage(),
                    'last_page' => $datas->lastPage(),
                ]
            ]);
        }

        // Si no es AJAX, mostrar la vista normal
        return view('Data.Exportar.export', compact('datas', 'ciclos', 'ciclo'));
    }

    public function exportData(Request $request)
    {
        $ciclo = $request->input('ciclo');

        if($ciclo === 'null') {
            return redirect()->back()->with('error', 'Debes seleccionar un ciclo para exportar.');
        }
        // Filtrar los registros según el ciclo y el estado = 1
        $query = Data::where('estado', 1);
        if ($ciclo && $ciclo !== 'all') {
            $query->where('ciclo', $ciclo);
        }
        // Obtener la cantidad de registros que serán exportados
        $cantidadRegistros = $query->count();
        // Obtener la hora actual
        $horaActual = now()->format('Y-m-d_H-i-s');  // Formato: año-mes-día_hora-minuto-segundo

        // Crear el nombre del archivo
        $nombreArchivo = 'Apptualiza_' . $horaActual . '_' . $cantidadRegistros . '.xlsx';

        // Realizar la exportación a Excel con el nombre generado
        return Excel::download(new DataExport($ciclo), $nombreArchivo);
    }

    public function exportDataComplete(Request $request)
    {
        $ciclo = $request->input('ciclo');

        if($ciclo === 'null') {
            return redirect()->back()->with('error', 'Debes seleccionar un ciclo para exportar.');
        }
        // Filtrar los registros según el ciclo y el estado = 1
        $query = Data::where('estado', 1);
        if ($ciclo && $ciclo !== 'all') {
            $query->where('ciclo', $ciclo);
        }
        // Obtener la cantidad de registros que serán exportados
        $cantidadRegistros = $query->count();
        // Obtener la hora actual
        $horaActual = now()->format('Y-m-d_H-i-s');  // Formato: año-mes-día_hora-minuto-segundo

        // Crear el nombre del archivo
        $nombreArchivo = 'Apptualiza_' . $horaActual . '_' . $cantidadRegistros . '.xlsx';

        // Realizar la exportación a Excel con el nombre generado
        return Excel::download(new DataExportComplete($ciclo), $nombreArchivo);
    }
}
