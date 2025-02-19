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
        @php
            $path = storage_path('app/public/img/LogoRib.png');
            $logo = file_get_contents($path);

            $LogoBase64 = base64_encode($logo); 

            $logo = 'data:image/png;base64,' . $LogoBase64;
        @endphp
        <img class="logo" src="{{ $logo }}" alt="Logo" width="100" style="background-color: black"> <!-- Logo de la empresa -->
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
        <p>
            <strong>Firma Usuario:</strong>
            <img class="firma" src="{{ $data->firmaUsuario }}" alt="Firma Usuario" width="100" height="50">
        </p>
        <p>
            <strong>Firma Técnico:</strong>
            <img class="firma" src="{{ $data->firmaTecnico }}" alt="Firma Técnico" width="100" height="50">
        </p>
    </div>

    <div class="footer">
        <p>Gracias por su visita</p>
        <p>Visítenos nuevamente</p>
    </div>
</body>
</html>