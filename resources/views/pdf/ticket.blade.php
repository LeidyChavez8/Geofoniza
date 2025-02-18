<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ticket</title>
    <style>
         <?php echo file_get_contents(public_path('css/Pdf/ticket.css')); ?>
    </style>
</head>
<body>
    {{-- {{dd(file_exists(public_path('img/LOGO_RIB_R.png')));}} --}}
    {{-- <img class="logo" src="{{ public_path('img/LOGO_RIB_R.png') }}" alt="Logo" width="100"> <!-- Logo de la empresa --> --}}
    <p class="order-number">{{ $data->orden }}</p> <!-- Número de orden en la parte superior derecha -->
    <div class="header">
        <h3>GEOFONIZA</h3>
        <ul>
            <li><strong>Dirección:</strong> Calle Principal 456</li>
            <li><strong>Teléfono:</strong> 987-654-3210</li>
        </ul>
    </div>

    <div class="details">
        <ul>
            <li><strong>Hora de inicio:</strong> {{ $data->created_at }}</li>
            <li><strong>Hora final:</strong> {{ $data->updated_at }}</li>
            <li><strong>Cliente:</strong> {{ $data->nombres }}</li>
            <li><strong>Dirección:</strong> {{ $data->direccion }}</li>
            <li><strong>Barrio:</strong> {{ $data->barrio }}</li>
            <li><strong>Teléfono:</strong> {{ $data->telefono }}</li>
            <li><strong>Correo:</strong> {{ $data->correo }}</li>
        </ul>

        <p><strong>Visita:</strong></p>
        <ul>
            <li><strong>No. Medidor:</strong> {{ $data->medidor }}</li>
            <li><strong>Lectura:</strong> {{ $data->lectura }}</li>
            <li><strong>Aforo:</strong> {{ $data->aforo }}</li>
            <li><strong>Resultado:</strong> {{ $data->resultado }}</li>
            <li><strong>Observación:</strong> {{ $data->observacion_inspeccion }}</li>
            <li><strong>Ciclo:</strong> {{ $data->ciclo }}</li>
            <li><strong>Punto Hidraulico:</strong> {{ $data->puntoHidraulico }}</li>
            <li><strong>Número Personas:</strong> {{ $data->numeroPersonas }}</li>
            <li><strong>Categoría:</strong> {{ $data->categoria }}</li>
        </ul>
    </div>

    <div class="signatures">
        @php
            $firmaUsuario = Storage::disk('google')->path($data->firmaUsuario);
            $firmaTecnico = Storage::disk('google')->path($data->firmaTecnico);
        @endphp
        <p>
            <strong>Firma Usuario:</strong>
            <img class="firma" src="{{ $firmaUsuario }}" alt="Firma Usuario" width="100">
        </p>
        <p>
            <strong>Firma Técnico:</strong>
            <img class="firma" src="{{ $firmaTecnico }}" alt="Firma Técnico" width="100">
        </p>
    </div>

    <div class="footer">
        <p>Gracias por su visita</p>
        <p>Visítenos nuevamente</p>
    </div>
</body>
</html>