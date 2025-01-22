<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Imports\DataImport;
use App\Imports\DataUpdateImport;
use App\Models\Data;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller
{
    public function index()
    {
        $datas = Data::where('estado', 1)->get();
        return view('Data.index', compact('datas'));
    }
    public function show($id)
    {
        $data = Data::findOrFail($id); // Encontrar el registro por ID
        return view('Data.show', compact('data'));
    }

    //IMPORTAR
    public function showUploadForm()
    {
        return view('Excel.import');
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

    // public function updateData(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls',
    //     ]);

    //     // Importar datos y actualizar si ya existen
    //     Excel::import(new DataUpdateImport, $request->file('file'));

    //     return redirect()->back()->with('success', 'Datos actualizados exitosamente.');
    // }

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



    //EXPORTAR
    public function seleccionarCiclo()
    {
        $ciclos = Data::select('ciclo')->distinct()->pluck('ciclo');
        return view('Data.export', compact('ciclos'));
    }

    public function filter(Request $request)
    {
        // Obtener el valor del ciclo del request
        $ciclo = $request->input('ciclo');

        // Filtrar los datos donde el estado es 1 y aplicar el filtro por ciclo si se proporciona
        $datas = Data::where('estado', 1)
            ->when($ciclo, function ($query, $ciclo) {
                return $query->where('ciclo', $ciclo);
            })
            ->paginate(10);

        // Obtener ciclos únicos para el filtro en la vista
        $ciclos = Data::select('ciclo')->distinct()->pluck('ciclo');

        // Pasar los datos y ciclos a la vista
        return view('Data.export', compact('datas', 'ciclos'));
    }



    public function exportData(Request $request)
    {
        $ciclo = $request->input('ciclo');

        // Asegurarse de que el filtro por ciclo se aplique junto con el estado = 1
        return Excel::download(new DataExport($ciclo), 'data_estado_1.xlsx');
    }




    // =============================      ASIGNACION      =============================


    //ASIGNAR
    public function asignarIndex(Request $request)
    {
        // Obtener los parámetros de ordenamiento
        $sortBy = $request->get('sortBy', 'id'); // Columna por defecto
        $direction = $request->get('direction', 'asc'); // Dirección por defecto

        // Validar que la columna y la dirección sean válidas
        $validColumns = ['id', 'ciclo', 'nombre_cliente', 'cuenta', 'direccion', 'recorrido', 'medidor', 'año', 'mes', 'periodo'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Obtener los datos ordenados
        $data = Data::where('id_operario', null)
            ->orderBy($sortBy, $direction)
            ->paginate(100);

        $operarios = User::where('rol', 'user')
            ->orderBy('name', 'asc')
            ->get();

        $totalResultados = Data::where('id_operario', null)->count();

        return view('Asignacion.asignacion', [
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
        $validColumns = ['id', 'ciclo', 'nombre_cliente', 'cuenta', 'direccion', 'recorrido', 'medidor', 'año', 'mes', 'periodo'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Construir la consulta con filtros
        $query = Data::where('estado', null);

        if ($request->filled('buscador-nombre')) {
            $query->where('nombre_cliente', 'like', '%' . $request->input('buscador-nombre') . '%');
        }
        if ($request->filled('buscador-cuenta')) {
            $query->where('cuenta', 'like', '%' . $request->input('buscador-cuenta') . '%');
        }
        if ($request->filled('buscador-medidor')) {
            $query->where('medidor', 'like', '%' . $request->input('buscador-medidor') . '%');
        }
        if ($request->filled('buscador-ciclo')) {
            $query->where('ciclo', 'like', '%' . $request->input('buscador-ciclo') . '%');
        }

        // Aplicar el ordenamiento
        $data = $query
            ->orderBy($sortBy, $direction)
            ->paginate(100)
            ->appends($request->except('page')); // Conservar parámetros en la paginación

        $operarios = User::where('rol', 'user')->get();
        $totalResultados = $query->count();

        return view('Asignacion.asignacion', compact('data', 'operarios', 'sortBy', 'direction', 'totalResultados'));
    }




    // Método para manejar la asignación de operarios
    public function asignarOperario(Request $request)
    {
        $request->validate([
            'Programacion' => 'required|array',
            'operario' => 'required|exists:users,id'
        ]);

        $programaciones = $request->input('Programacion');
        $operarioId = $request->input('operario');

        Data::whereIn('id', $programaciones)->update(['id_operario' => $operarioId]);

        return redirect()->route('asignar.index')->with('success', 'Operario asignado exitosamente');
    }



    // =============================      DESASIGNACION      =============================


    public function desasignarIndex(Request $request)
    {
        // Obtener los parámetros de ordenamiento
        $sortBy = $request->get('sortBy', 'id'); // Columna por defecto
        $direction = $request->get('direction', 'asc'); // Dirección por defecto

        // Validar que la columna y la dirección sean válidas
        $validColumns = ['id', 'ciclo', 'nombre_cliente', 'cuenta', 'direccion', 'recorrido', 'medidor', 'año', 'mes', 'periodo'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Obtener los datos ordenados
        $data = Data::whereNotNull('id_operario')
            ->orderBy($sortBy, $direction)
            ->paginate(100);

        $operarios = User::where('rol', 'user')
            ->orderBy('name', 'asc')
            ->get();

        $totalResultados = Data::whereNotNull('id_operario')->count();

        return view('Asignacion.desasignacion', [
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
        $validColumns = ['id', 'ciclo', 'nombre_cliente', 'cuenta', 'direccion', 'recorrido', 'medidor', 'año', 'mes', 'periodo'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Construir la consulta con filtros
        $query = Data::whereNotNull('id_operario');

        if ($request->filled('buscador-nombre')) {
            $query->where('nombre_cliente', 'like', '%' . $request->input('buscador-nombre') . '%');
        }
        if ($request->filled('buscador-cuenta')) {
            $query->where('cuenta', 'like', '%' . $request->input('buscador-cuenta') . '%');
        }
        if ($request->filled('buscador-medidor')) {
            $query->where('medidor', 'like', '%' . $request->input('buscador-medidor') . '%');
        }
        if ($request->filled('buscador-ciclo')) {
            $query->where('ciclo', 'like', '%' . $request->input('buscador-ciclo') . '%');
        }

        // Aplicar el ordenamiento
        $data = $query
            ->orderBy($sortBy, $direction)
            ->paginate(100)
            ->appends($request->except('page')); // Conservar parámetros en la paginación

        $operarios = User::where('rol', 'user')->get();
        $totalResultados = $query->count();

        return view('Asignacion.desasignacion', compact('data', 'operarios', 'sortBy', 'direction', 'totalResultados'));
    }

    public function desasignarOperario(Request $request)
    {
        $request->validate([
            'Programacion' => 'required|array',
        ]);

        $programaciones = $request->input('Programacion');

        Data::whereIn('id', $programaciones)->update(['id_operario' => null]);

        return redirect()->route('desasignar.index')->with('success', 'Operario desasignado exitosamente');
    }



    // public function desasignarOperario(Request $request)
    // {
    //     $request->validate([
    //         'Programacion' => 'required|array',
    //     ]);

    //     $programaciones = $request->input('Programacion');

    //     Data::whereIn('id', $programaciones)->update(['id_operario' => null]);

    //     return redirect()->route('desasignacion')->with('success', 'Operario desasignado exitosamente!!');
    // }


    public function edit($id)
    {
        $data = Data::findOrFail($id);
        return view('Operario.edit', compact('data'));
    }



    /// OPERARIO





    public function listarData(Request $request)
    {
        $operarioId = Auth::user()->id;
        session(['previous_url' => $request->fullUrl()]);


        // Crear la consulta base
        $query = Data::where('id_operario', $operarioId)
            ->where(function ($query) {
                $query->where('estado', 0)
                    ->orWhereNull('estado');
            })
            ->orderBy('recorrido', 'asc');

        // Obtener los datos paginados
        $data = $query->paginate(1);

        return view('Operario.index', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Data::findOrFail($id);

        $validatedData = $request->validate([
            'nombre_cliente' => 'required',
            'telefono' => 'required|size:10',
            'email' => 'required|email',
        ]);

        $data->fill($validatedData);

        if ($request->hasFile('firma')) {
            $firmaBase64 = base64_encode(file_get_contents($request->file('firma')->path()));
            $data->firma = $firmaBase64;
        }

        // Cambiar el estado a 1 (actualizado)
        $data->estado = 1;

        $data->save();

        return redirect()->route('operario.listarData')->with('success', 'Datos actualizados correctamente');
    }

    public function volver()
    {
        return redirect()->back();
    }



}
