<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Actualizar</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Datas/editDataUser.css') }}" rel="stylesheet">


    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>




    <div class="container">
        <h2> Actualizar datos</h2>

        <form id="signature-form" action="{{ route('asignados.update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Campos del formulario -->
            <div class="form-group">
                <label for="ciclo">Ciclo:</label>
                <input type="text" name="ciclo" class="form-control" value="{{ old('ciclo', $data->ciclo) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="cuenta">Cuenta:</label>
                <input type="text" name="cuenta" class="form-control" value="{{ old('cuenta', $data->cuenta) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" class="form-control"
                    value="{{ old('direccion', $data->direccion) }}" disabled>
            </div>

            <div class="form-group">
                <label for="medidor">Medidor:</label>
                <input type="text" name="medidor" class="form-control" value="{{ old('medidor', $data->medidor) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="nombre_cliente">Nombre Cliente:</label>
                <input type="text" name="nombre_cliente" class="form-control"
                    value="{{ old('nombre_cliente', $data->nombre_cliente) }}" disabled>
                @error('nombre_cliente')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="lectura">Lectura:</label>
                <input type="text" name="lectura" id="lectura" class="form-control"
                    value="{{ old('lectura', $data->lectura) }}" maxlength="10">
                @error('lectura')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="novedad">Novedad:</label>
                <input type="novedad" name="novedad" class="form-control" value="{{ old('novedad', $data->novedad) }}">
                @error('novedad')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="comentario">Comentario:</label>
                <input type="comentario" name="comentario" class="form-control" value="{{ old('comentario', $data->comentario) }}">
                @error('comentario')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Reemplaza el botón con un checkbox y un texto -->
            <div class="form-group checkbox-label">
                <input type="checkbox">
                <div class="terminos">
                    <label for="save-signature-checkbox" class="terminos">Autoriza a RIB Logísticas SAS a utilizar y
                        almacenar
                        sus datos personales, incluyendo su número de teléfono y correo electrónico, conforme a la Ley
                        1581
                        de
                        2012. Esta información será utilizada únicamente para fines relacionados con la prestación de
                        nuestros
                        servicios.</label>
                </div>
            </div>

            <!-- Contenedor para centrar el botón de actualización -->
            <div class="btn-group">
                <button onclick="window.location.href='javascript:history.back()'"
                    type="button" class="btn btn-tertiary">
                    volver
                </button>

                <button type="submit" id="update-button" class="btn btn-primary">
                    Actualizar
                </button>
            </div>
        </form>
    </div>


    <script>
        const checkbox = document.querySelector('.dark-mode-switch input[type="checkbox"]');
        const modeText = document.querySelector('.dark-mode-switch .mode-text');

        // Check for existing dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }
    </script>

</body>

</html>
