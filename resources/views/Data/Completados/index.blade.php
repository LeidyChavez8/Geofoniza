@extends('layouts.app')

@section('title', 'Completados')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Datas/asignar.css') }}">
@endsection

@section('content')
    <div class="assignment-container">

        <form action="{{ route('completados.filtrar') }}" method="GET" class="mb-4">
            @csrf
            <div class="filters-section">
                <input type="text" name="buscador-ciclo" class="filter-input" placeholder=" ciclo..."
                    value="{{ request('buscador-ciclo') }}">
                <input type="text" name="buscador-direccion" class="filter-input" placeholder=" direccion..."
                    value="{{ request('buscador-direccion') }}">
                <input type="text" name="buscador-recorrido" class="filter-input" placeholder=" recorrido..."
                    value="{{ request('buscador-recorrido')}}">

                <!-- Campos ocultos para conservar los parámetros de orden -->
                <input type="hidden" name="sortBy" value="{{ $sortBy }}">
                <input type="hidden" name="direction" value="{{ $direction }}">

                <button type="submit" class="btn btn-tertiary">
                    <i class='bx bx-filter-alt' style="margin-right: 0.2rem;"></i>
                    <span>Filtrar</span>
                </button>
            </div>
        </form>


        <!-- Mensajes de éxito y error -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                <i class="bx bx-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                <i class="bx bx-error"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="bx bx-error"></i> <strong>Se encontraron los siguientes errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-wrapper">
            <table class="assignment-table">
                <thead>
                    <tr>
                        <th>
                            @php
                                $queryParams = request()->query();
                                $queryParams['sortBy'] = 'contrato';
                                $queryParams['direction'] =
                                    request('sortBy') == 'contrato' && request('direction') == 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Contrato
                                @if (request('sortBy') == 'contrato')
                                    <i
                                        class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                @endif
                            </a>
                        </th>

                        <th>
                            @php
                                $queryParams = request()->query();
                                $queryParams['sortBy'] = 'ciclo';
                                $queryParams['direction'] =
                                    request('sortBy') == 'ciclo' && request('direction') == 'asc' ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Ciclo
                                @if (request('sortBy') == 'ciclo')
                                    <i
                                        class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                @endif
                            </a>
                        </th>

                        <th>
                            @php
                                $queryParams = request()->query();
                                $queryParams['sortBy'] = 'direccion';
                                $queryParams['direction'] =
                                    request('sortBy') == 'direccion' && request('direction') == 'asc'
                                        ? 'desc'
                                        : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Dirección
                                @if (request('sortBy') == 'direccion')
                                    <i
                                        class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                @endif
                            </a>
                        </th>

                        <th>
                            @php
                                $queryParams = request()->query();
                                $queryParams['sortBy'] = 'medidor';
                                $queryParams['direction'] =
                                    request('sortBy') == 'medidor' && request('direction') == 'asc'
                                        ? 'desc'
                                        : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Medidor
                                @if (request('sortBy') == 'medidor')
                                <i
                                class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                @endif
                            </a>
                        </th>

                        <th>
                            @php
                                $queryParams = request()->query();
                                $queryParams['sortBy'] = 'recorrido';
                                $queryParams['direction'] =
                                    request('sortBy') == 'recorrido' && request('direction') == 'asc'
                                        ? 'desc'
                                        : 'asc';
                            @endphp
                            <a href="{{ route(Route::currentRouteName(), $queryParams) }}">
                                Recorrido
                                @if (request('sortBy') == 'recorrido')
                                    <i
                                        class="bx {{ request('direction') == 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i>
                                @endif
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td>{{ $data->contrato }}</td>
                            <td>{{ $data->ciclo }}</td>
                            <td class="table-cell-truncate">{{ $data->direccion }}</td>
                            <td class="table-cell-truncate">{{ $data->medidor }}</td>
                            <td>{{ $data->recorrido }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{-- @if ($data->hasPages()) --}}
            <div class="pagination-info">
                Mostrando {{ $datas->firstItem() }} a {{ $datas->lastItem() }} de {{ $datas->total() }} registros
            </div>
            <ul class="pagination">
                {{-- Botón Previous --}}
                @if ($datas->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class='bx bx-chevron-left'></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $datas->appends(request()->except('page'))->previousPageUrl() }}"
                            rel="prev">
                            <i class='bx bx-chevron-left'></i>
                        </a>
                    </li>
                @endif

                @php
                    $start = $datas->currentPage() - 2;
                    $end = $datas->currentPage() + 2;
                    if ($start < 1) {
                        $start = 1;
                        $end = min(5, $datas->lastPage());
                    }
                    if ($end > $datas->lastPage()) {
                        $end = $datas->lastPage();
                        $start = max(1, $end - 4);
                    }
                @endphp

                @if ($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $datas->appends(request()->except('page'))->url(1) }}">1</a>
                    </li>
                    @if ($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $datas->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ $datas->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if ($end < $datas->lastPage())
                    @if ($end < $datas->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $datas->appends(request()->except('page'))->url($datas->lastPage()) }}">
                            {{ $datas->lastPage() }}
                        </a>
                    </li>
                @endif

                {{-- Botón Next --}}
                @if ($datas->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $datas->appends(request()->except('page'))->nextPageUrl() }}"
                            rel="next">
                            <i class='bx bx-chevron-right'></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class='bx bx-chevron-right'></i>
                        </span>
                    </li>
                @endif
            </ul>
            {{-- @endif --}}
        </div>
    </div>
@endsection
