@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/Users/create_edit.css') }}">
@endsection

@section('content')
<div class="user-form-container">
    <div class="form-header">
        <h2>Crear Nuevo Usuario</h2>
    </div>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                id="password" name="password" required>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" class="form-control"
                id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <select class="form-control form-select @error('rol') is-invalid @enderror"
                id="rol" name="rol" required>
                <option value="">Seleccionar rol</option>
                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="user" {{ old('rol') == 'user' ? 'selected' : '' }}>Usuario</option>
            </select>
            @error('rol')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('users.index') }}'"">
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
                <span>Crear Usuario</span>
            </button>

        </div>
    </form>
</div>
@endsection
