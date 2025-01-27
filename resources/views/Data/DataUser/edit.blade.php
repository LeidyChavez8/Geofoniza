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
                <label for="cuenta">Cuenta:</label>
                <input type="text" name="cuenta" class="form-control" value="{{ old('cuenta', $data->contrato) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="ciclo">Ciclo:</label>
                <input type="text" name="ciclo" class="form-control" value="{{ old('ciclo', $data->ciclo) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="medidor" name="direccion" class="form-control"
                    value="{{ old('direccion', $data->direccion) }}" disabled>
            </div>

            <div class="form-group">
                <label for="medidor">Medidor:</label>
                <input type="text" id="medidor" name="medidor" class="form-control" value="{{ old('medidor', $data->medidor) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="recorrido">Recorrido:</label>
                <input type="text" id="recorrido" name="recorrido" class="form-control" value="{{ old('recorrido', $data->recorrido) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="nombre_cliente">Nombre Cliente:</label>
                <input type="text" name="nombre_cliente" class="form-control"
                    value="{{ old('nombre_cliente', $data->nombres) }}" disabled>
                @error('nombre_cliente')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- @dd($data->attributesToArray()); --}}
    
            <div class="form-group">
                <label for="lectura_anterior">Lectura anterior:</label>
                <input type="text" id="lectura_anterior" name="lectura_anterior" class="form-control"
                    value="{{ old('lectura_anterior', $data->lectura_anterior) }}" maxlength="10" disabled>
            </div>

            <div class="form-group">
                <label for="lectura">Lectura:</label>
                <input type="text" name="lectura" id="lectura" class="form-control"
                    value="{{ old('lectura') }}" maxlength="10">
                @error('lectura')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- @dd($data->observacion_inspeccion[0]) --}}

            <div class="form-group">
                <label for="observacion_inspeccion">Observación:</label>
                <select name="observacion_inspeccion" id="observacion_inspeccion" class="form-control">
                    <option value="">Seleccione observación</option>
                    @foreach ($data->observacion_inspeccion as $item)
                        <option value="{{ $item }}" {{ old('observacion_inspeccion') == $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
                
                @error('observacion_inspeccion')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" name="foto" id="foto" class="form-control"
                    value="{{ old('lectura') }}" maxlength="10">
                @error('foto')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group firma-container">
                <label for="firma">Firma</label>
                <canvas id="signature-pad" class="firma" width="550" height="170"></canvas>
                <input type="hidden" id="firma" name="firma" class="form-control">
                @error('firma')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="firma-actions">
                <button type="button" id="clear" >Limpiar firma</button>
            </div>

            <!-- Reemplaza el botón con un checkbox y un texto -->
            <div class="form-group checkbox-label">
                <input type="checkbox" name="yes"  required {{ old('yes') ? 'checked' : '' }}>
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

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signature-pad');
        const clearButton = document.getElementById('clear');
        const hiddenInput = document.getElementById('firma');
        const form = document.querySelector('form');

        // Inicializar SignaturePad
        const signaturePad = new SignaturePad(canvas,{
            minWidth: 1, // Opcional: grosor mínimo del trazo
            maxWidth: 3, // Opcional: grosor máximo del trazo
            penColor: "black", // Color del trazo   
        });

        // Limpiar la firma
        clearButton.addEventListener('click', function () {
            signaturePad.clear();
        });

        // Guardar la firma en el campo oculto antes de enviar el formulario
        form.addEventListener('submit', function (event) {
            if (signaturePad.isEmpty()) {
                alert('Por favor, asegúrate de firmar antes de enviar.');
                event.preventDefault();
            } else {
                hiddenInput.value = signaturePad.toDataURL();
            }
        });
    });
</script>

</body>

</html>
