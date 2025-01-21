@extends('layouts.app')

@section('titulo', 'Apptualiza')

@section('styles')
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('/img/AAA.png');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;

            /* Ancho fijo al 100% del viewport */
            height: 100vh;
            /* Alto fijo al 100% del viewport */
            margin: 0;
            /* Eliminar márgenes predeterminados */
            overflow: hidden;
            /* Evitar scroll */
            padding: 10%
        }



        .main-container {
            width: 80%;
            /* Tamaño fijo relativo a la pantalla del móvil */
            max-width: 600px;
            /* Limitar el ancho máximo */
            min-width: 400px;
            /* Limitar el ancho mínimo */
            max-height: 600px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 10px;
            padding-left: 20px;
            padding-right: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            /* Permitir scroll solo dentro del contenedor si es necesario */
            overflow-x: hidden;
            /* Ocultar el scroll horizontal */
            border: 2px solid #ffffff5d;
        }

        .header-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-container h1 {
            font-size: 2rem;
            color: #000000;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.315);
            border: 1px solid #ffffff5d;
            border-radius: 10px;
            padding: 10px;
        }

        .content-container {
            margin-bottom: 30px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;

        }

        .list-item {
            background-color: rgba(255, 255, 255, 0.315);
            border: 1px solid #ffffff5d;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            /* Ancho total del contenedor padre */
            max-width: 400px;
            /* Ancho máximo fijo */
            min-width: 300px;
            /* Ancho mínimo fijo */
            height: auto;
            overflow: hidden;
            /* Ocultar cualquier contenido que se desborde */
            box-sizing: border-box;
            /* Incluir padding y border en el ancho total */
            word-wrap: break-word;
            /* Forzar el quiebre de palabras largas */
            overflow-wrap: break-word;
            /* Forzar el quiebre en navegadores más recientes */
        }

        .table {
            background-color: transparent; /* Quita el fondo de la tabla */
        }

        .table td {
            background-color: transparent; /* Quita el fondo de las celdas */
            padding: 4px;

        }

        .table th {
            white-space: nowrap; /* Evita que el texto se divida en varias líneas */
            width: 0%; /* Fuerza que la columna sea lo más pequeña posible */
            background-color: transparent; /* Quita el fondo de las celdas */
            padding: 4px;
        }

        .list-item p {
            font-size: 1rem;
            margin-bottom: 2px;
            margin-left: 1%;
        }

        .list-item strong {
            color: #000000;
        }

        .message {
            font-size: 1.5rem;
            color: #333;
            text-align: center;
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 0;
            list-style-type: none;
        }

        .page-item {
            margin: 0 5px;
        }

        .page-link {
            display: block;
            padding: 10px 10px;
            font-size: 1rem;
            color: #0072C6;
            border: 1px solid #0072C6;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .page-link:hover {
            background-color: #0072C6;
            color: #fff;
        }

        .page-item.active .page-link {
            background-color: #0072C6;
            color: #fff;
        }

        .page-item.disabled .page-link {
            color: #ccc;
            pointer-events: none;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            /* Espacio entre los botones */
            margin-top: 10px;
        }


        .btn {
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #0072C6;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #005fa3;
        }

        .btn-danger {
            background-color: #ff4d4d;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #e63939;
        }

        @media (max-width: 480px) {
            .main-container {
                width: 100%;
                padding: 20px;
            }

            .header-container h1 {
                font-size: 1.8rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 10px 15px;
            }

            .page-link {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
        }

        hr {

            color: #fff
            padding: 0px;
            margin: 3px;
            width: 95%;
        }
    </style>
@endsection
@section('content')
    <div class="main-container">
        <div class="header-container">
            <h1>Órdenes Asignadas</h1>
        </div>

        <div class="content-container">
            @if ($data->isEmpty())
                <p class="message">No hay órdenes asignadas para mostrar.</p>
            @else
                @foreach ($data as $item)
                    <div class="list-item">
                        <p><strong>Nombres:</strong> {{ $item->nombre_cliente }}</p>
                        <hr>
                        <p><strong>Ciclo:</strong> {{ $item->ciclo }}</p>
                        <hr>
                        <p> <strong>Cuenta:</strong> {{ $item->cuenta }}</p>
                        <hr>
                        <p><strong>Dirección:</strong> {{ $item->direccion }}</p>
                        <hr>
                        <p><strong>Medidor:</strong> {{ $item->medidor }}</p>
                    </div>

                @endforeach

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        {{-- Botón de retroceso a la página anterior --}}
                        @if ($data->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        {{-- Paginación, solo mostrar 5 enlaces de página --}}
                        @php
                            $start = max(1, $data->currentPage() - 1);
                            $end = min($data->lastPage(), $data->currentPage() + 1);
                        @endphp

                        @for ($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $data->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Botón de avance a la página siguiente --}}
                        @if ($data->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            @endif

            <div class="btn-group">
                @if (!$data->isEmpty())
                    <form action="{{ route('operario.edit', $item) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <svg width="20px" height="20px" viewBox="0 2 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" stroke="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                </g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M8.5 21H4C4 17.134 7.13401 14 11 14C11.1681 14 11.3348 14.0059 11.5 14.0176M15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7ZM12.5898 21L14.6148 20.595C14.7914 20.5597 14.8797 20.542 14.962 20.5097C15.0351 20.4811 15.1045 20.4439 15.1689 20.399C15.2414 20.3484 15.3051 20.2848 15.4324 20.1574L19.5898 16C20.1421 15.4477 20.1421 14.5523 19.5898 14C19.0376 13.4477 18.1421 13.4477 17.5898 14L13.4324 18.1574C13.3051 18.2848 13.2414 18.3484 13.1908 18.421C13.1459 18.4853 13.1088 18.5548 13.0801 18.6279C13.0478 18.7102 13.0302 18.7985 12.9948 18.975L12.5898 21Z"
                                        stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </g>
                            </svg>
                            Actualizar
                        </button>
                    </form>
                @endif

                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M19 23H11C10.4477 23 10 22.5523 10 22C10 21.4477 10.4477 21 11 21H19C19.5523 21 20 20.5523 20 20V4C20 3.44772 19.5523 3 19 3L11 3C10.4477 3 10 2.55229 10 2C10 1.44772 10.4477 1 11 1L19 1C20.6569 1 22 2.34315 22 4V20C22 21.6569 20.6569 23 19 23Z"
                                    fill="#ffffff"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M2.48861 13.3099C1.83712 12.5581 1.83712 11.4419 2.48862 10.6902L6.66532 5.87088C7.87786 4.47179 10.1767 5.32933 10.1767 7.18074L10.1767 9.00001H16.1767C17.2813 9.00001 18.1767 9.89544 18.1767 11V13C18.1767 14.1046 17.2813 15 16.1767 15L10.1767 15V16.8193C10.1767 18.6707 7.87786 19.5282 6.66532 18.1291L2.48861 13.3099ZM4.5676 11.3451C4.24185 11.7209 4.24185 12.2791 4.5676 12.6549L8.1767 16.8193V14.5C8.1767 13.6716 8.84827 13 9.6767 13L16.1767 13V11L9.6767 11C8.84827 11 8.1767 10.3284 8.1767 9.50001L8.1767 7.18074L4.5676 11.3451Z"
                                    fill="#ffffff"></path>
                            </g>
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
