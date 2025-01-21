@extends('layouts.app')

@section('titulo', 'Apptualiza')

@section('styles')
<style>
    body {
        background-image: url('/img/AAA.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .main-container {
        width: 100%;
        max-width: 42rem;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 0.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .header-container {
        text-align: center;
        padding: 1.5rem;
    }

    h1 {
        font-size: 1.875rem;
        font-weight: bold;
        color: white;
    }

    .content-container {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    select,
    input {
        width: 100%;
        padding: 0.75rem;
        background-color: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 0.25rem;
        color: white;
        font-size: 1rem;
    }

    select::placeholder,
    input::placeholder {
        color: rgba(219, 234, 254, 1);
    }

    select:focus,
    input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }

    .btn-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .btn {
        padding: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: center;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn svg {
        margin-right: 0.5rem;
        width: 1rem;
        height: 1rem;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-success {
        background-color: #10b981;
        color: white;
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
    }

    .btn:hover {
        transform: scale(1.05);
    }

    .message {
        text-align: center;
        color: rgba(219, 234, 254, 1);
        margin-top: 1rem;
    }

    .table-container {
        margin-top: 2rem;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th,
    td {
        padding: 0.75rem;
        text-align: left;
        color: white;
    }

    th {
        background-color: rgba(59, 130, 246, 0.5);
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin-top: 1rem;
    }

    .pagination li {
        margin: 0 0.25rem;
    }

    .pagination a,
    .pagination span {
        display: block;
        padding: 0.5rem 0.75rem;
        color: white;
        background-color: rgba(59, 130, 246, 0.5);
        border-radius: 0.25rem;
        text-decoration: none;
    }

    .pagination .active span {
        background-color: rgba(59, 130, 246, 0.8);
    }
</style>
@endsection

@section('content')
<div class="main-container">
    <div class="header-container">
        <h1>Exportar Órdenes Actualizadas</h1>
    </div>

    <div class="content-container">
        <form action="{{ route('data.filter') }}" method="GET">
            <div class="form-group">
                <select name="ciclo" id="ciclo">
                    <option value="">Selecciona un ciclo</option>
                    @foreach ($ciclos as $ciclo)
                    <option style="color: black;" value="{{ $ciclo }}" {{ request('ciclo') == $ciclo ? 'selected' : '' }}>
                        {{ $ciclo }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" name="action" value="filter" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    Aplicar filtros
                </button>
                <a href="{{ route('data.export', ['ciclo' => request('ciclo')]) }}" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Exportar a Excel
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Volver
                    </a>
            </div>
        </form>

        @if(isset($datas) && $datas->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ciclo</th>
                        <th>Año</th>
                        <th>Mes</th>
                        <th>Cuenta</th>
                        <th>Dirección</th>
                        <th>Recorrido</th>
                        <th>Medidor</th>
                        <th>Nombre Cliente</th>
                        <th>Periodo</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                    <tr>
                        <td>{{ $data->ciclo }}</td>
                        <td>{{ $data->ano }}</td>
                        <td>{{ $data->mes }}</td>
                        <td>{{ $data->cuenta }}</td>
                        <td>{{ $data->direccion }}</td>
                        <td>{{ (int) $data->recorrido }}</td>
                        <td>{{ $data->medidor }}</td>
                        <td>{{ $data->nombre_cliente }}</td>
                        <td>{{ $data->periodo }}</td>
                        <td>{{ $data->telefono }}</td>
                        <td>{{ $data->email }}</td>
                        <td>{{ $data->estado }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $datas->links() }}
        @else
        <p class="message">No se encontraron órdenes para el ciclo seleccionado.</p>
        @endif
    </div>
</div>
@endsection