<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Auth;

Auth::routes();
// Grupo de rutas protegidas por el middleware 'auth'
Route::middleware(['auth', CheckRole::class.':admin'])->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('users', UserController::class);
    Route::resource('datos', DataController::class);

    // IMPORTAR EXCEL
    Route::get('excel-importar', [DataController::class, 'showUploadForm'])->name('import.import');
    Route::post('excel/reemplazar', [DataController::class, 'replaceData'])->name('import.replace');
    // Route::post('excel/actualizar', [DataController::class, 'updateData'])->name('import.update');
    Route::post('excel/agregar', [DataController::class, 'addData'])->name('import.add');

    // ASIGNAR
    Route::get('/asignar', [DataController::class, 'asignarIndex'])->name('asignar.index');
    Route::get('/asignar-filtrar', [DataController::class, 'asignarFiltrar'])->name('asignar.filtrar');
    Route::post('/asignar-operario', [DataController::class, 'asignarOperario'])->name('asignar.operario');

    // DESASIGNAR
    Route::get('/desasignar', [DataController::class, 'desasignarIndex'])->name('desasignar.index');
    Route::get('/desasignar-filtrar', [DataController::class, 'desasignarFiltrar'])->name('desasignar.filtrar');
    Route::post('/desasignar-operario', [DataController::class, 'desasignarOperario'])->name('desasignar.operario');




    //OPERARIO
    Route::get('/ordenes-asignadas', [DataController::class, 'indexAsignar'])->name('operario.index');
    Route::get('/operario/listar', [DataController::class, 'listarData'])->name('operario.listarData');
    Route::get('/operario/edit/{data}', [DataController::class, 'edit'])->name('operario.edit');
    Route::put('/operario/update/{id}', [DataController::class, 'update'])->name('operario.update');
    Route::post('/operario/volver', [DataController::class, 'volver'])->name('operario.volver');



    Route::get('/database/download', [DataController::class, 'download'])->name('database.download');

    Route::get('/register', [UserController::class, 'showRegistrationForm'])->middleware(['auth', 'check.role'])->name('register');
    Route::post('/register', [UserController::class, 'register'])->middleware(['auth', 'check.role']);





    //EXPORTAR EXCEL
    // Mostrar formulario para seleccionar ciclo
    Route::get('/seleccionarciclo', [DataController::class, 'seleccionarCiclo'])->name('data.seleccionar');
    // Aplicar filtros
    Route::get('/filter-data', [DataController::class, 'filter'])->name('data.filter');
    // Exportar a Excel
    Route::get('/export-data', [DataController::class, 'exportData'])->name('data.export');
});

Route::middleware(['auth', CheckRole::class.':user'])->group(function () {



});
