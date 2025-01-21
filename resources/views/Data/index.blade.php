@extends('layouts.app')

@section('titulo', 'Apptualiza')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    body {
        background-image: url('/img/AAA.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5%;
    }

    .container-fluid {
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        padding: 1.5rem;
        width: 100%;
        color: white;
        max-width: 95%;
    }

    h1 {
        font-size: 1.875rem;
        font-weight: bold;
        color: white;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .table-wrapper {
        max-height: 400px;
        overflow-y: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 11px;
    }

    th {
        background-color: rgba(46, 39, 39, 0.529);
        font-weight: bold;
    }

    td {
        background-color: rgba(46, 39, 39, 0.161);
        font-weight: bold;
    }


    .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: center;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
    }

    .btn:hover {
        transform: scale(1.05);
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
<div class="container-fluid">
    <h1>Órdenes Actualizadas</h1>
    <div class="table-container">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Nombre Cliente</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Cuenta</th>
                        <th>Dirección</th>
                        <th>Recorrido</th>
                        <th>Ciclo</th>
                        <th>Medidor</th>
                        <th>Periodo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                    <tr>
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->nombre_cliente }}</td>
                        <td>{{ $data->email }}</td>
                        <td>{{ $data->telefono }}</td>
                        <td>{{ $data->cuenta }}</td>
                        <td>{{ $data->direccion }}</td>
                        <td>{{ $data->recorrido }}</td>
                        <td>{{ $data->ciclo }}</td>
                        <td>{{ $data->medidor }}</td>
                        <td>{{ $data->periodo }}</td>
                        <td>
                            <a href="{{ route('data.show', $data->id) }}" class="btn btn-primary btn-sm">Ver Firma</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        @if ($datas->onFirstPage())
        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $datas->previousPageUrl() }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        @endif

        @foreach(range(1, $datas->lastPage()) as $i)
        <li class="page-item {{ ($i == $datas->currentPage()) ? 'active' : '' }}">
            <a class="page-link" href="{{ $datas->url($i) }}">{{ $i }}</a>
        </li>
        @endforeach

        @if ($datas->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $datas->nextPageUrl() }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        @else
        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
</nav>

<div class="text-center mt-3">
    <a href="{{ route('home') }}" class="btn btn-danger">Volver</a>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/Users.js') }}"></script>
@endsection

