<?php

use App\Http\Controllers\TicketController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware(['auth', CheckRole::class . ':admin'])->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // SIDEBAR
    Route::get('/sidebar-search', [DataController::class, 'sidebarSearch'])->name('sidebar.search');

    Route::resource('users', UserController::class);

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

    //USERDATA
    Route::get('/asignados', [DataController::class, 'asignadosListar'])->name('asignados.index');
    Route::get('/asignados/edit/{data}', [DataController::class, 'asignadosEdit'])->name('asignados.edit');
    Route::put('/operario/update/{id}', [DataController::class, 'asignadosUpdate'])->name('asignados.update');

    //COMPLETADOS
    Route::get('/completados', [DataController::class, 'completadosIndex'])->name('completados.index');
    Route::get('/completados-filtrar', [DataController::class, 'completadosFiltrar'])->name('completados.filtrar');

    //EXPORTAR EXCEL
    Route::get('/export', [DataController::class, 'exportarIndex'])->name('export');
    Route::get('/export-filtrar', [DataController::class, 'exportarFiltrar'])->name('export.filtrar');
    Route::get('/export-data', [DataController::class, 'exportData'])->name('export.excel');


    Route::get('/database/download', [DataController::class, 'download'])->name('database.download');

    Route::get('/generate-ticket/{id}', [TicketController::class, 'generateTicket']);
});

Route::middleware(['auth', CheckRole::class . ':user'])->group(function () {

    //USERDATA
    Route::get('/asignados', [DataController::class, 'asignadosListar'])->name('asignados.index');
    Route::get('/asignados/edit/{data}', [DataController::class, 'asignadosEdit'])->name('asignados.edit');
    Route::put('/operario/update/{id}', [DataController::class, 'asignadosUpdate'])->name('asignados.update');

});
