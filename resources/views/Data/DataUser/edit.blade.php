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
                <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control"
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
                    <select name="categoria" id="categoria" class="form-control">
                        <option value="">Seleccione la categoria de la inspección</option>
                            <option value="residencial" {{ old('categoria') == 'residencial' ? 'selected' : '' }}>Residencial</option>
                            <option value="comercial" {{ old('categoria') == 'comercial' ? 'selected' : '' }}>Comercial</option>
                            <option value="industrial" {{ old('categoria') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                    </select>                
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
                <label for="observacion_inspeccion">Observación:</label>
                <input type="text" name="observacion_inspeccion" id="observacion_inspeccion" class="form-control"
                    value="{{ old('observacion_inspeccion') }}">
                
                @error('observacion_inspeccion')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="resultado">Resultado:</label>
                <select name="resultado" id="resultado" class="form-control">
                    <option value="">Seleccione el resultado de la inspección</option>
                    @foreach ($data->resultado as $item)
                        <option value="{{ $item }}" {{ old('resultado') == $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
                
                @error('resultado')
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
    <label>Firma del usuario</label>
    <canvas id="signature-pad-usuario" class="firma" width="550" height="170"></canvas>
    <input type="hidden" id="firmaUsuario" name="firmaUsuario" class="form-control">
    @error('firmaUsuario')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>

<div class="firma-actions">
    <button type="button" id="clear-usuario" class="btn-clear">Limpiar firma del usuario</button>
</div>

{{-- FIRMA DEL TÉCNICO --}}
<div class="form-group firma-container">
    <label>Firma del técnico</label>
    
    <div class="firma-tecnico-options">
        <div class="option-buttons">
            <button type="button" id="option-dibujar" class="option-btn active">Dibujar firma</button>
            <button type="button" id="option-adjuntar" class="option-btn">Adjuntar imagen</button>
        </div>
        
        <div id="dibujar-container" class="firma-option-container">
            <canvas id="signature-pad-tecnico" class="firma" width="550" height="170"></canvas>
            <div class="firma-actions">
                <button type="button" id="clear-tecnico" class="btn-clear">Limpiar firma del técnico</button>
            </div>
        </div>
        
        <div id="adjuntar-container" class="firma-option-container" style="display: none;">
            <div class="file-upload-wrapper">
                <label for="imagen-firma" class="file-upload-label">
                    <span class="upload-icon">📎</span>
                    <span class="upload-text">Seleccionar imagen de firma</span>
                </label>
                <input type="file" id="imagen-firma" accept="image/*" class="form-control file-upload-input">
            </div>
            <div class="preview-container" style="display: none;">
                <img id="preview-firma" src="" alt="Vista previa de firma">
            </div>
            <div class="firma-actions">
                <button type="button" id="clear-imagen" class="btn-clear">Eliminar imagen</button>
            </div>
        </div>
    </div>
    
    <input type="hidden" id="firmaTecnico" name="firmaTecnico" class="form-control">
    <input type="hidden" id="metodoFirma" name="metodoFirma" value="dibujar">
    @error('firmaTecnico')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Reemplaza el botón con un checkbox y un texto -->
<div class="form-group checkbox-label">
    <input type="checkbox" name="yes" required {{ old('yes') ? 'checked' : '' }}>
    <div class="terminos">
        <label class="terminos">Autoriza a RIB Logísticas SAS a utilizar y
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
        Continuar
    </button>
</div>
</form>
</div>

<style>

</style>

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
    // Inicializar firma del usuario
    const canvasUsuario = document.getElementById('signature-pad-usuario');
    const clearButtonUsuario = document.getElementById('clear-usuario');
    const inputUsuario = document.getElementById('firmaUsuario');
    const signaturePadUsuario = new SignaturePad(canvasUsuario, {
        minWidth: 1,
        maxWidth: 3,
        penColor: "black",
    });

    clearButtonUsuario.addEventListener('click', function () {
        signaturePadUsuario.clear();
    });

    // Inicializar firma del técnico con signature pad
    const canvasTecnico = document.getElementById('signature-pad-tecnico');
    const clearButtonTecnico = document.getElementById('clear-tecnico');
    const signaturePadTecnico = new SignaturePad(canvasTecnico, {
        minWidth: 1,
        maxWidth: 3,
        penColor: "black",
    });

    clearButtonTecnico.addEventListener('click', function () {
        signaturePadTecnico.clear();
    });

    // Elementos para adjuntar imagen
    const optionDibujar = document.getElementById('option-dibujar');
    const optionAdjuntar = document.getElementById('option-adjuntar');
    const dibujarContainer = document.getElementById('dibujar-container');
    const adjuntarContainer = document.getElementById('adjuntar-container');
    const imagenFirma = document.getElementById('imagen-firma');
    const previewFirma = document.getElementById('preview-firma');
    const previewContainer = document.querySelector('.preview-container');
    const clearImagen = document.getElementById('clear-imagen');
    const inputFirmaTecnico = document.getElementById('firmaTecnico');
    const metodoFirma = document.getElementById('metodoFirma');
    const form = document.querySelector('form');

    // Añadir efecto visual al área de soltar archivo
    const fileUploadLabel = document.querySelector('.file-upload-label');
    
    // Cambiar entre opciones de firma con animación
    optionDibujar.addEventListener('click', function() {
        optionDibujar.classList.add('active');
        optionAdjuntar.classList.remove('active');
        
        adjuntarContainer.style.opacity = '0';
        setTimeout(() => {
            adjuntarContainer.style.display = 'none';
            dibujarContainer.style.display = 'block';
            setTimeout(() => {
                dibujarContainer.style.opacity = '1';
            }, 50);
        }, 300);
        
        metodoFirma.value = 'dibujar';
    });

    optionAdjuntar.addEventListener('click', function() {
        optionAdjuntar.classList.add('active');
        optionDibujar.classList.remove('active');
        
        dibujarContainer.style.opacity = '0';
        setTimeout(() => {
            dibujarContainer.style.display = 'none';
            adjuntarContainer.style.display = 'block';
            setTimeout(() => {
                adjuntarContainer.style.opacity = '1';
            }, 50);
        }, 300);
        
        metodoFirma.value = 'adjuntar';
    });

    // Inicialmente establece la opacidad
    dibujarContainer.style.transition = 'opacity 0.3s ease';
    adjuntarContainer.style.transition = 'opacity 0.3s ease';
    dibujarContainer.style.opacity = '1';
    adjuntarContainer.style.opacity = '0';

    // Previsualizar imagen seleccionada
    imagenFirma.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            
            // Actualizar texto en la etiqueta
            const fileName = e.target.files[0].name;
            const uploadText = document.querySelector('.upload-text');
            uploadText.textContent = fileName.length > 25 ? fileName.substring(0, 22) + '...' : fileName;
            
            reader.onload = function(e) {
                previewFirma.src = e.target.result;
                previewContainer.style.display = 'block';
                
                // Animar la aparición
                previewContainer.style.opacity = '0';
                setTimeout(() => {
                    previewContainer.style.opacity = '1';
                }, 50);
            }
            
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Transición para previewContainer
    previewContainer.style.transition = 'opacity 0.3s ease';

    // Limpiar imagen adjuntada
    clearImagen.addEventListener('click', function() {
        imagenFirma.value = '';
        
        // Resetear texto del botón
        const uploadText = document.querySelector('.upload-text');
        uploadText.textContent = 'Seleccionar imagen de firma';
        
        // Animar la desaparición
        previewContainer.style.opacity = '0';
        setTimeout(() => {
            previewFirma.src = '';
            previewContainer.style.display = 'none';
        }, 300);
    });

    // Efectos visuales para drag and drop
    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, function(e) {
            e.preventDefault();
            fileUploadLabel.classList.add('dragging');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, function(e) {
            e.preventDefault();
            fileUploadLabel.classList.remove('dragging');
        }, false);
    });

    // Manejar envío del formulario
    form.addEventListener('submit', function (event) {
        // Validar firma del usuario
        if (signaturePadUsuario.isEmpty()) {
            alert('Por favor, asegúrate de firmar en el campo de usuario antes de enviar.');
            event.preventDefault();
            return;
        } else {
            inputUsuario.value = signaturePadUsuario.toDataURL();
        }

        // Validar firma del técnico según el método seleccionado
        if (metodoFirma.value === 'dibujar') {
            if (signaturePadTecnico.isEmpty()) {
                alert('Por favor, asegúrate de firmar en el campo del técnico antes de enviar.');
                event.preventDefault();
                return;
            } else {
                inputFirmaTecnico.value = signaturePadTecnico.toDataURL();
            }
        } else if (metodoFirma.value === 'adjuntar') {
            if (!imagenFirma.files || imagenFirma.files.length === 0) {
                alert('Por favor, adjunta una imagen de firma del técnico antes de enviar.');
                event.preventDefault();
                return;
            } else {
                // La imagen ya se convirtió a base64 durante la previsualización
                inputFirmaTecnico.value = previewFirma.src;
            }
        }
    });
});
</script>

</body>

</html>
