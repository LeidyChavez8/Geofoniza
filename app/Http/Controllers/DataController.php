<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
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
        $validColumns = ['ciclo', 'direccion', 'orden'];
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
        $validColumns = ['ciclo', 'direccion', 'orden'];
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

        if ($request->filled('buscador-ciclo')) {
            $query->where('ciclo', 'like', '%' . $request->input('buscador-ciclo') . '%');
        }
        if ($request->filled('buscador-direccion')) {
            $query->where('direccion', 'like', '%' . $request->input('buscador-direccion') . '%');
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
        $validColumns = ['ciclo', 'direccion', 'orden'];
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
        $validColumns = ['ciclo', 'direccion', 'orden'];
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
        if ($request->filled('buscador-ciclo')) {
            $query->where('ciclo', 'like', '%' . $request->input('buscador-ciclo') . '%');
        }
        if ($request->filled('buscador-direccion')) {
            $query->where('direccion', 'like', '%' . $request->input('buscador-direccion') . '%');
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

        $observacion = [
            "CAUSA DE CIERRE",
            "AR-Amerita Revisión De Laboratorio",
            "AU-USUARIO AUSENTE",
            "CB-CAJA REGISTRO OBSTACULIZADA",
            "CD-PREDIO CERRADO",
            "CO-CONSTRUCCION",
            "DH-DESHABITADO",
            "DM-PREDIO DEMOLIDO",
            "DS-RECOMENDACIÓN NO ACATADA",
            "FN-FUGA IMPERCEPTIBLE",
            "FP-FUGA PERCEPTIBLE",
            "FR-FUGA REPARADA",
            "FS-FUGA SIN REPARAR",
            "GFN-FUGA UBICADA GAS TRAZADOR",
            "IS-INSPECCION SIN CONCLUIR",
            "MI-MEDIDOR INVERTIDO",
            "NA-NO ATIENDEN",
            "ND-NO DEJAN",
            "NF-NO EXISTE FUGA",
            "NO SE PUDO COMPROBAR INFORMACION",
            "NP-NO PERMITEN INGRESO AL PREDIO",
            "NU-FUGA IMPERCEPTIBLE NO UBICADA",
            "OR-ORDEN REPROGRAMADA POR EL USUARIO",
            "PG-PROGRAMAR GEOFONO",
            "RI-PREDIO CON RECONEXION ILEGAL",
            "SD-SERVICIO DIRECTO",
            "SP-SECTOR PELIGROSO",
            "SSA-SECTOR SIN AGUA",
            "SS-SERVICIO SUSPENDIDO",
            "SV-SIN VEHICULO",
            "VM-VIA EN MAL ESTADO",
            "NE-No Se Encuentra Medidor",
            "VI-Verificación Incompleta",
            "ET-Medidor Enterrado",
            "FV-Fuga Visible",
            "Predio comercial cerrado",
            "ND-No Dejan Pasar",
            "Medidor sin conexión interna, vecino le regala agua",
            "AS-Agua Suspendida",
            "CA-Medidor con Candado",
            "CO-Construcción",
            "DC-Distinto Contador",
            "FI-Fuga En Instalación",
            "LT-Lote",
            "Macromedidor",
            "Medidor sin conexión interna, se surte del medidor del vecino",
            "PG-Programar geófono",
            "PNF-Persona No Facultada",
            "Predio sin medidor",
            "RI-Predio Con Reconexión Ilegal",
            "SM-Sin Medidor"
        ];

        $data -> observacion_inspeccion = $observacion;
        return view('Data.DataUser.edit', compact('data'));
    }


    public function asignadosUpdate(Request $request, $id)
    {
        $data = Data::findOrFail($id);

     $validatedData = $request->validate([
        'medidor' => 'required',
    	'lectura' => 'required',
    	'aforo' => 'required',
    	'resultado' => 'required',
    	'observacion_inspeccion' => 'required',
    	'foto' => 'required|image|mimes:jpeg,png,jpg,bmp,tiff|max:51200',
    	'firmaUsuario' => 'required|string',
    	'firmaTecnico' => 'required|string',
    	'puntoHidraulico' => 'required|int',
    	'numeroPersonas' => 'required|int',
    ],[
    	'foto.required' => 'La evidencia es obligatoria.',
    	'foto.image' => 'El archivo debe ser una imagen.',
    	'foto.mimes' => 'La imagen debe estar en formato: jpeg, png, jpg, bmp o tiff.',
    ]);

        // Subir la foto a Google Drive
        $mesActual = date('F');
        $userFolder = auth()->user()->name; // Subcarpeta personalizada por usuario
        $fileName = $validatedData['foto']->getClientOriginalName();
        $filePath = "Apptualiza/"."$mesActual/$userFolder/$fileName";

        // Subida de foto
        $driveResponse = Gdrive::put($filePath, $validatedData['foto']);

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

        $firmaPath = null;
        try {
            $firmaTecnicoPath = '';

            $firmaUsuarioPath = '';

            if($request->has('firmaUsuario')){
                try {
                    $image = str_replace('data:image/png;base64,','',$request->input('firmaUsuario'));
                    $image = str_replace(' ','+',$image);
                    $firmaUsuarioPath = 'firmas/' . uniqid() . '.png';
                    Storage::disk('public')->put($firmaUsuarioPath,base64_decode($image));

                } catch (Exception $e){
                    return redirect()->back()->with('error','Error al procesar la firma' . $e->getMessage());
                }

            }

            if($request->has('firmaTecnico')){
                try {
                    $image = str_replace('data:image/png;base64,','',$request->input('firmaTecnico'));
                    $image = str_replace(' ','+',$image);
                    $firmaTecnicoPath = 'firmas/' . uniqid() . '.png';
                    Storage::disk('public')->put($firmaTecnicoPath,base64_decode($image));

                } catch (Exception $e){
                    return redirect()->back()->with('error','Error al procesar la firma' . $e->getMessage());
                }

            }
        } catch (Exception $e){
            return redirect()->back()->with('error','Error al procesar la firma' . $e->getMessage());
        }

        $data->fill($validatedData);

        $data->firmaTecnico = $firmaTecnicoPath;

        $data->firmaUsuario = $firmaUsuarioPath;
        
        $data->estado = 1;

        $data->save();

        return redirect()->route('generate.ticket', ['id' => $data->id])->with('success', 'Datos actualizados correctamente');
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

        // Validar que la columna y la dirección sean válidas
        $validColumns = ['id', 'ciclo', 'nombre_cliente', 'direccion', 'orden', 'direccion', 'año', 'mes'];
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

    public function completadosFiltrar(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id'); // Columna de orden por defecto
        $direction = $request->get('direction', 'asc'); // Dirección de orden por defecto

        // Validar la columna y la dirección de ordenamiento
        $validColumns = ['ciclo', 'direccion', 'orden'];
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
        if ($request->filled('buscador-ciclo')) {
            $query->where('ciclo', 'like', '%' . $request->input('buscador-ciclo') . '%');
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
}
