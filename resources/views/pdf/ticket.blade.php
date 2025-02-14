<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ticket</title>
    <link rel="stylesheet" href="{{ asset('css/Pdf/ticket.css') }}">
</head>
<body>
    <div class="header">
        <h3>Tienda XYZ</h3>
        <p>Dirección: Calle Principal 456</p>
        <p>Teléfono: 987-654-3210</p>
        <p class="order-number">Orden: {{ $data->orden }}</p> <!-- Número de orden en la parte superior derecha -->
    </div>

    <div class="details">
        <p><strong>Agenda:</strong></p>
        <ul>
            <li><strong>Nombre:</strong> {{ $data->nombres }}</li>
            <li><strong>Dirección:</strong> {{ $data->direccion }}</li>
            <li><strong>Barrio:</strong> {{ $data->barrio }}</li>
            <li><strong>Teléfono:</strong> {{ $data->telefono }}</li>
            <li><strong>Correo:</strong> {{ $data->correo }}</li>
        </ul>

        <p><strong>Visita:</strong></p>
        <ul>
            <li><strong>Medidor:</strong> {{ $data->medidor }}</li>
            <li><strong>Lectura:</strong> {{ $data->lectura }}</li>
            <li><strong>Aforo:</strong> {{ $data->aforo }}</li>
            <li><strong>Resultado:</strong> {{ $data->resultado }}</li>
            <li><strong>Observación:</strong> {{ $data->observacion_inspeccion }}</li>
        </ul>
    </div>

    <div class="signatures">
        <p><strong>Firma Usuario:</strong> <img src="{{ $data->firmaUsuario }}" alt="Firma Usuario" width="100"></p>
        <p><strong>Firma Técnico:</strong> <img src="{{ $data->firmaTecnico }}" alt="Firma Técnico" width="100"></p>
    </div>

    <div class="footer">
        <p>Gracias por su visita</p>
        <p>Visítenos nuevamente</p>
    </div>
</body>
</html>