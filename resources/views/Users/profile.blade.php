@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('styles')
    @vite('resources/css/Users/profile.css')
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
            <img src="{{ asset('img/avatar_usu.png') }}" alt="Foto de perfil">
        </div>
        <div class="profile-info">
            <h2>{{ Auth::user()->name }}</h2>
            <p class="username">{{ Auth::user()->username }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="profile-details">
        <div class="detail-section">
            <h3>Información Personal</h3>
            <div class="detail-item">
                <span class="label">Nombre:</span>
                <span class="value">{{ Auth::user()->name }}</span>
            </div>
            <div class="detail-item">
                <span class="label">Usuario:</span>
                <span class="value">{{ Auth::user()->username }}</span>
            </div>
            <div class="detail-item">
                <span class="label">Fecha de registro:</span>
                <span class="value">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        {{-- <div class="profile-actions">
            <a href="{{ route('users.edit', Auth::id()) }}" class="btn btn-primary">
                {{-- <span class="material-symbols-outlined">edit</span> --}
                Editar Perfil
            </a>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                {{-- <span class="material-symbols-outlined">delete</span> --}
                Eliminar Cuenta
            </button>
        </div> --}}
    </div>

    <!-- Modal de confirmación para eliminar cuenta -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Confirmar Eliminación</h3>
            <p>¿Está seguro que desea eliminar su cuenta? Esta acción no se puede deshacer.</p>
            <div class="modal-actions">
                <form action="{{ route('users.destroy', Auth::id()) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sí, eliminar cuenta</button>
                </form>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Cerrar modal al hacer clic fuera de él
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection
