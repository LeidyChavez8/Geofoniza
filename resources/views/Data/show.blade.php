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

    .container2 {
        width: 100%;
        max-width: 42rem;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 0.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        padding: 1.5rem;
    }

    .header-container {
        display: flex;
        align-items: center;
        padding-bottom: 1rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        background-color: rgba(59, 130, 246, 0.5);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        text-decoration: none;
        margin-right: 1rem;
        font-size: 1.25rem;
        transition: background-color 0.3s ease;
    }

    .btn-back:hover {
        background-color: rgba(59, 130, 246, 0.8);
    }

    .header-title {
        font-size: 1.5rem;
        color: white;
    }

    p {
        font-size: 1rem;
        color: white;
        margin-bottom: 1rem;
    }

    img {
        max-width: 100%;
        height: auto;
        border-radius: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container2">
    <div class="header-container">
        <a href="{{ route('data.index') }}" class="btn-back">
            <span class="icon">&#8592;</span>
        </a>
        <h2 class="header-title"><strong>Datos de la Órden</strong></h2>
    </div>

    <p><strong>Nombre del Cliente:</strong> {{ $data->nombre_cliente }}</p>
    <p><strong>Email:</strong> {{ $data->email }}</p>
    <p><strong>Teléfono:</strong> {{ $data->telefono }}</p>

    <p><strong>Firma:</strong></p>
    @if($data->firma)
        <img src="data:image/png;base64,{{ $data->firma }}" alt="Firma del Cliente">
    @else
    
        <p>No hay firma disponible.</p>
    @endif
</div>
@endsection
