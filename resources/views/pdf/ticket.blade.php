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
    <p class="order-number">{{ $data->orden }}</p> <!-- Número de orden en la parte superior derecha -->
    <div class="header">
        @php
            $path = storage_path('app/public/img/LogoRib.png');
            $logoRib = file_get_contents($path);

            $LogoRibBase64 = base64_encode($logoRib); 

            $logoRib = 'data:image/png;base64,' . $LogoRibBase64;


            $path = storage_path('app/public/img/LogoAqualert.png');
            $logoAqualert = file_get_contents($path);

            $LogoAqualertBase64 = base64_encode($logoAqualert); 

            $logoAqualert = 'data:image/png;base64,' . $LogoAqualertBase64;
        @endphp


        <div class="logo-container">
            <img class="logo" src="{{ $logoRib }}" alt="Logo RIB">
            <img class="logo" src="{{ $logoAqualert }}" alt="Logo Aqualert" style="transform: translateY(25px); height: 100px;"  >
        </div>

        <h3>GEOFONIZA</h3>
        <ul>
            <li>Dirección: Calle Principal 456</li>
            <li>Teléfono: 987-654-3210</li>
        </ul>
    </div>

    <div class="details">
        <ul>
            <li>Hora de inicio:{{ $data->created_at }}</li>
            <li>Hora final: {{ $data->updated_at }}</li>
            <li>Cliente: {{ $data->nombres }}</li>
            <li>Dirección: {{ $data->direccion }}</li>
            <li>Barrio: {{ $data->barrio }}</li>
            <li>Teléfono: {{ $data->telefono }}</li>
            <li>Correo: {{ $data->correo }}</li>
        </ul>

        <p>Visita:</p>
        <ul>
            <li>No. Medidor:{{ $data->medidor }}</li>
            <li>Lectura: {{ $data->lectura }}</li>
            <li>Aforo: {{ $data->aforo }}</li>
            <li class="parrafo">Resultado: {{ $data->resultado }}</li>
            <li>Observación: {{ $data->observacion_inspeccion }}</li>
            <li>Ciclo: {{ $data->ciclo }}</li>
            <li>Punto Hidraulico: {{ $data->puntoHidraulico }}</li>
            <li>Número Personas: {{ $data->numeroPersonas }}</li>
            <li>Categoría: {{ $data->categoria }}</li>
        </ul>
    </div>

    <div class="signatures">
        <p>
            Firma Usuario:
            <img class="firma" src="{{ $data->firmaUsuario }}" alt="Firma Usuario" width="100" height="50">
        </p>
        <p>
            Firma Técnico:
            <img class="firma" src="{{ $data->firmaTecnico }}" alt="Firma Técnico" width="100" height="50">
        </p>
    </div>

    <div class="footer">
        <p>Gracias por su visita</p>
        <p>Visítenos nuevamente</p>
    </div>
</body>
</html>