@extends('layouts.app') {{-- Usa la plantilla base si la tienes --}}

{{-- Uso de css --}}
@section('style')
    <link rel="stylesheet" href="{{ asset('css/Agendar/nuevo_registro.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
@endsection

@section('content')
    <div class="container">
        <h2 class="my-4">Agendar Nuevo Registro</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario --}}
        <div class="section">
            <form action="{{ isset($data) ? route('schedule.update', $data->id) : route('schedule.store') }}" method="POST" id="Agendar_inputs">
                @csrf
                @if (isset($data))
                    @method('PUT') {{-- Para actualizar si existe un ID --}}
                @endif

                {{-- Campo Nombre --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombres" name="nombres"
                        value="{{ $data->nombres ?? old('nombres') }}" placeholder="">
                    @error('nombres')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Cédula --}}
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula"
                        value="{{ $data->cedula ?? old('cedula') }}" placeholder="">
                    @error('cedula')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Dirección --}}
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion"
                        value="{{ $data->direccion ?? old('direccion') }}" placeholder="">
                    @error('direccion')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Barrio --}}
                <div class="mb-3">
                    <label for="barrio" class="form-label">Barrio</label>
                    <input type="text" class="form-control" id="barrio" name="barrio"
                        value="{{ $data->barrio ?? old('barrio') }}" placeholder="">
                    @error('barrio')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Teléfono --}}
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono"
                        value="{{ $data->telefono ?? old('telefono') }}" placeholder="">
                    @error('telefono')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Correo --}}
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo"
                        value="{{ $data->correo ?? old('correo') }}" placeholder="">
                    @error('correo')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Ciclo --}}
                <div class="mb-3">
                    <label for="ciclo" class="form-label">Ciclo</label>
                    <select name="ciclo" id="ciclo" class="form-control">
                        @foreach ($ciclos as $item)
                        <option value="" selected>Seleccione un ciclo</option>
                            <option value="{{ $item }}" {{ old('ciclo')}} placeholder="Ciclo">
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                    @error('ciclo')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ajustar el botón de registrar para que no se vea pegado --}}
                <br>

                {{-- Botón de envío --}}
                <button type="submit" class="btn btn-primary">
                    {{ isset($data) ? 'Actualizar' : 'Registrar' }}
                </button>
            </form>
        </div>
        {{-- fin de la section --}}
    </div>


    <script>
        $(document).ready(function() {
            $('#ciclo').select2({
                placeholder: "Selecciona o escribe un ciclo",
                allowClear: true,
                width: '100%',
                // Matcher personalizado para búsqueda sin distinguir mayúsculas
                matcher: function(params, data) {
                    // Si no hay término de búsqueda, devolver todos los elementos
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    
                    // Si no hay texto, no mostrar
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
                    
                    // Convertir a minúsculas para comparación
                    var termLower = params.term.toLowerCase();
                    var textLower = data.text.toLowerCase();
                    
                    // Verificar si contiene el término
                    if (textLower.indexOf(termLower) > -1) {
                        return data;
                    }
                    
                    return null;
                }
            });
        });
    </script>

    <!-- CSS para aplicar exactamente tus estilos -->
    <style>
        /* Aplicar los estilos exactos de .form-control al contenedor de Select2 */
        .select2-container .select2-selection--single {
            width: 100%;
            padding: 10px 12px;
            font-size: 0.9rem;
            border: 1px solid var(--primary-color-light);
            border-radius: var(--border-radius-small);
            background-color: var(--sidebar-color);
            color: var(--text-color);
            height: auto;
        }

        /* Ajustar el texto renderizado */
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            padding-right: 20px;
            color: var(--text-color);
            line-height: normal;
        }

        /* Ajustar posición de la flecha del dropdown */
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 100%;
            top: 0;
        }

        /* Estilo del dropdown */
        .select2-dropdown {
            border: 1px solid var(--primary-color-light);
            border-radius: var(--border-radius-small);
            background-color: var(--sidebar-color);
        }

        /* Estilo de las opciones */
        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            color: var(--text-color);
        }

        /* Estilo hover en las opciones */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-color-light);
            color: var(--text-color);
        }

        /* Estilo del input de búsqueda */
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--primary-color-light);
            background-color: var(--sidebar-color);
            color: var(--text-color);
            padding: 8px 10px;
            border-radius: var(--border-radius-small);
        }
    </style>
@endsection
