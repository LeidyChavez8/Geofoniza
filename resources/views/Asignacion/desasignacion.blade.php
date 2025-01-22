@extends('layouts.app')

@section('title', 'Desasignar')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Datas/asignar.css') }}">
@endsection

@section('content')
    <div class="assignment-container">
        <form action="{{ route('desasignar.filtrar') }}" method="GET" class="mb-4">
            @csrf
            <div class="filters-section">
                <input type="text" name="buscador-nombre" class="filter-input" placeholder=" nombre..." value="{{ request('buscador-nombre') }}">
                <input type="text" name="buscador-cuenta" class="filter-input" placeholder=" cuenta..." value="{{ request('buscador-cuenta') }}">
                <input type="text" name="buscador-medidor" class="filter-input" placeholder=" medidor..." value="{{ request('buscador-medidor') }}">
                <input type="text" name="buscador-ciclo" class="filter-input" placeholder=" ciclo..." value="{{ request('buscador-ciclo') }}">

                <button type="submit" class="btn btn-tertiary">
                    <i class='bx bx-filter-alt' style="margin-right: 0.2rem;"></i>
                    <span>Filtrar</span>
                </button>

                <button class="btn btn-primary" id="abrirModal" type="button">
                    Desasignar
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

        <form action="{{ route('desasignar.operario') }}" method="post">
            @csrf
            <div class="table-wrapper">
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" id="seleccionarTodo">
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <th>ID</th>
                            <th>Ciclo</th>
                            <th>Nombre Cliente</th>
                            <th>Cuenta</th>
                            <th>Dirección</th>
                            <th>Recorrido</th>
                            <th>Medidor</th>
                            <th>Año</th>
                            <th>Mes</th>
                            <th>Período</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $programacion)
                            <tr>
                                <td>
                                    <label class="checkbox-wrapper">
                                        <input type="checkbox" name="Programacion[]" value="{{ $programacion->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>{{ $programacion->id }}</td>
                                <td>{{ $programacion->ciclo }}</td>
                                <td>{{ $programacion->nombre_cliente }}</td>
                                <td>{{ $programacion->cuenta }}</td>
                                <td>{{ $programacion->direccion }}</td>
                                <td>{{ $programacion->recorrido }}</td>
                                <td>{{ $programacion->medidor }}</td>
                                <td>{{ $programacion->año }}</td>
                                <td>{{ $programacion->mes }}</td>
                                <td>{{ $programacion->periodo }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal de Desasignación -->
            <div id="miModal" class="modal-assignment">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Desasignar Operario</h2>
                        <button type="button" class="modal-close" title="Cerrar">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>¿Está seguro de que desea desasignar los registros seleccionados?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-trash' style="margin-right: 0.2rem"></i>
                            <span>Confirmar Desasignación</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>


        <!-- Paginación -->
        <div class="pagination-container">
            {{-- @if ($data->hasPages()) --}}
                <div class="pagination-info">
                    Mostrando {{ $data->firstItem() }} a {{ $data->lastItem() }} de {{ $data->total() }} registros
                </div>
                <ul class="pagination">
                    {{-- Botón Previous --}}
                    @if ($data->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class='bx bx-chevron-left'></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $data->appends(request()->except('page'))->previousPageUrl() }}" rel="prev">
                                <i class='bx bx-chevron-left'></i>
                            </a>
                        </li>
                    @endif

                    @php
                        $start = $data->currentPage() - 2;
                        $end = $data->currentPage() + 2;
                        if ($start < 1) {
                            $start = 1;
                            $end = min(5, $data->lastPage());
                        }
                        if ($end > $data->lastPage()) {
                            $end = $data->lastPage();
                            $start = max(1, $end - 4);
                        }
                    @endphp

                    @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $data->appends(request()->except('page'))->url(1) }}">1</a>
                        </li>
                        @if ($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $data->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link"
                                href="{{ $data->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($end < $data->lastPage())
                        @if ($end < $data->lastPage() - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $data->appends(request()->except('page'))->url($data->lastPage()) }}">
                                {{ $data->lastPage() }}
                            </a>
                        </li>
                    @endif

                    {{-- Botón Next --}}
                    @if ($data->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $data->appends(request()->except('page'))->nextPageUrl() }}"
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

@section('scripts')
    <script>
        // ------------------------ CHECKBOX ------------------------
        document.getElementById('seleccionarTodo').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('input[name="Programacion[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Selección por zona con Shift
        document.addEventListener('DOMContentLoaded', function() {
            let lastChecked = null;
            const checkboxes = document.querySelectorAll('input[name="Programacion[]"]');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('click', function(e) {
                    if (!lastChecked) {
                        lastChecked = this;
                        return;
                    }

                    if (e.shiftKey) {
                        let inBetween = false;
                        checkboxes.forEach(currentCheckbox => {
                            if (currentCheckbox === this || currentCheckbox ===
                                lastChecked) {
                                inBetween = !inBetween;
                            }
                            if (inBetween) {
                                currentCheckbox.checked = lastChecked.checked;
                            }
                        });
                    }
                    lastChecked = this;
                });
            });
        });

        // ------------------------ MODAL ------------------------
        const modal = document.getElementById('miModal');
        const btnAbrir = document.getElementById('abrirModal');
        const btnCerrar = document.querySelector('.modal-close');

        btnAbrir.onclick = function() {
            modal.style.display = "flex";
        }

        btnCerrar.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection
