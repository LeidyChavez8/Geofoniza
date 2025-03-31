@extends('layouts.app') {{-- Usa la plantilla base si la tienes --}}

{{-- Uso de css --}}
@section('style')
    <link rel="stylesheet" href="{{ asset('css/Agendar/nuevo_registro.css') }}">
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
                    <input type="text" class="form-control" id="ciclo" name="ciclo"
                        value="{{ $data->ciclo ?? old('ciclo') }}" placeholder="">
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
@endsection
