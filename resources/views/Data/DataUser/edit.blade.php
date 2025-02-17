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

            {{-- CAMPOS DE LA AGENDA --}}

            <div class="form-group">
                <label for="orden">Orden:</label>
                <input type="text" name="orden" id="orden" class="form-control"
                value="{{ old('nombre_cliente', $data->orden) }}" disabled>
            </div>

            <div class="form-group">
                <label for="nombre_cliente">Nombre Cliente:</label>
                <input type="text" name="nombre_cliente" class="form-control"
                    value="{{ old('nombre_cliente', $data->nombres) }}" disabled>
            </div>

            {{-- <div class="form-group">
                <label for="ciclo">Ciclo:</label>
                <input type="text" name="ciclo" class="form-control"
                    disabled>
            </div> --}}

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="form-control"
                  value="{{$data->direccion}}"  disabled>
            </div>
            
            <div class="form-group">
                <label for="barrio">Barrio:</label>
                <input type="text" id="barrio" name="barrio" class="form-control"
                value="{{$data->barrio}}" disabled>
            </div>
          
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" class="form-control"
                  value="{{$data->telefono}}" disabled>
            </div>
           
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="text" id="correo" name="correo" class="form-control"
                value="{{$data->correo}}"  disabled>
            </div>


            {{-- CAMPOS DE LA VISITA --}}
            <div class="form-group">
                <label for="numeroPersonas">Número de personas:</label>
                <input type="text" name="numeroPersonas" id="numeroPersonas" class="form-control"
                    value="{{ old('numeroPersonas') }}" maxlength="10">
                @error('numeroPersonas')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" name="categoria" id="categoria" class="form-control"
                    value="{{ old('categoria') }}">
                @error('categoria')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="puntoHidraulico">Punto hidráulico:</label>
                <input type="text" name="puntoHidraulico" id="puntoHidraulico" class="form-control"
                    value="{{ old('puntoHidraulico') }}" >
                @error('puntoHidraulico')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="medidor">Medidor:</label>
                <input type="text" name="medidor" id="medidor" class="form-control"
                    value="{{ old('medidor') }}" >
                @error('medidor')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group">
                <label for="lectura">Lectura:</label>
                <input type="text" name="lectura" id="lectura" class="form-control"
                    value="{{ old('lectura') }}" maxlength="10">
                @error('lectura')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="aforo">Aforo:</label>
                <input type="text" name="aforo" id="aforo" class="form-control"
                    value="{{ old('aforo') }}">
                @error('aforo')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="resultado">Resultado:</label>
                <input type="text" name="resultado" id="resultado" class="form-control"
                    value="{{ old('resultado') }}">
                @error('resultado')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

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
                <label for="foto">Evidencia:</label>
                <input type="file" name="foto" id="foto" class="form-control">
                @error('foto')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>


           {{-- FIRMA DEL USUARIO --}}
            <div class="form-group firma-container">
                <label for="firmaUsuario">Firma del usuario</label>
                <canvas id="signature-pad-usuario" class="firma" width="550" height="170"></canvas>
                <input type="hidden" id="firmaUsuario" name="firmaUsuario" class="form-control">
                @error('firmaUsuario')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="firma-actions">
                <button type="button" id="clear-usuario">Limpiar firma del usuario</button>
            </div>

            {{-- FIRMA DEL TÉCNICO --}}
            <div class="form-group firma-container">
                <label for="firmaTecnico">Firma del técnico</label>
                <canvas id="signature-pad-tecnico" class="firma" width="550" height="170"></canvas>
                <input type="hidden" id="firmaTecnico" name="firmaTecnico" class="form-control">
                @error('firmaTecnico')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="firma-actions">
                <button type="button" id="clear-tecnico">Limpiar firma del técnico</button>
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
    function initializeSignaturePad(canvasId, clearButtonId, hiddenInputId) {
        const canvas = document.getElementById(canvasId);
        const clearButton = document.getElementById(clearButtonId);
        const hiddenInput = document.getElementById(hiddenInputId);
        const form = document.querySelector('form');

        const signaturePad = new SignaturePad(canvas, {
            minWidth: 1,
            maxWidth: 3,
            penColor: "black",
        });

        clearButton.addEventListener('click', function () {
            signaturePad.clear();
        });

        form.addEventListener('submit', function (event) {
            if (signaturePad.isEmpty()) {
                alert(`Por favor, asegúrate de firmar en el campo ${canvasId} antes de enviar.`);
                event.preventDefault();
            } else {
                hiddenInput.value = signaturePad.toDataURL();
            }
        });
    }

    initializeSignaturePad('signature-pad-usuario', 'clear-usuario', 'firmaUsuario');
    initializeSignaturePad('signature-pad-tecnico', 'clear-tecnico', 'firmaTecnico');
});
</script>

</body>

</html>
