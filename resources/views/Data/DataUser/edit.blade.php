@extends('layouts.app')

@section('titulo', 'Apptualiza')

@section('content')
    <link href="{{ asset('assets/css/edit.css') }}" rel="stylesheet">
    <div class="container">
        <div class="header-container">
            <a href="javascript:history.back()" class="btn-back">
                <svg width="50px" height="50px" fill="#ad0000" height="200px" width="200px" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" stroke="#ad0000"
                    transform="matrix(1, 0, 0, 1, 0, 0)rotate(0)">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC"
                        stroke-width="5.12"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <g>
                                <g>
                                    <path
                                        d="M256,0C114.618,0,0,114.618,0,256s114.618,256,256,256s256-114.618,256-256S397.382,0,256,0z M256,469.333 c-117.818,0-213.333-95.515-213.333-213.333S138.182,42.667,256,42.667S469.333,138.182,469.333,256S373.818,469.333,256,469.333 z">
                                    </path>
                                    <path
                                        d="M384,234.667H179.503l48.915-48.915c8.331-8.331,8.331-21.839,0-30.17s-21.839-8.331-30.17,0l-85.333,85.333 c-0.008,0.008-0.014,0.016-0.021,0.023c-0.488,0.49-0.952,1.004-1.392,1.54c-0.204,0.248-0.38,0.509-0.571,0.764 c-0.226,0.302-0.461,0.598-0.671,0.913c-0.204,0.304-0.38,0.62-0.566,0.932c-0.17,0.285-0.349,0.564-0.506,0.857 c-0.17,0.318-0.315,0.646-0.468,0.971c-0.145,0.306-0.297,0.607-0.428,0.921c-0.13,0.315-0.236,0.637-0.35,0.957 c-0.121,0.337-0.25,0.669-0.354,1.013c-0.097,0.32-0.168,0.646-0.249,0.969c-0.089,0.351-0.187,0.698-0.258,1.055 c-0.074,0.375-0.118,0.753-0.173,1.13c-0.044,0.311-0.104,0.617-0.135,0.933c-0.138,1.4-0.138,2.811,0,4.211 c0.031,0.315,0.09,0.621,0.135,0.933c0.054,0.377,0.098,0.756,0.173,1.13c0.071,0.358,0.169,0.704,0.258,1.055 c0.081,0.324,0.152,0.649,0.249,0.969c0.104,0.344,0.233,0.677,0.354,1.013c0.115,0.32,0.22,0.642,0.35,0.957 c0.13,0.314,0.283,0.615,0.428,0.921c0.153,0.325,0.297,0.653,0.468,0.971c0.157,0.293,0.336,0.572,0.506,0.857 c0.186,0.312,0.363,0.628,0.566,0.932c0.211,0.315,0.445,0.611,0.671,0.913c0.191,0.255,0.368,0.516,0.571,0.764 c0.439,0.535,0.903,1.05,1.392,1.54c0.007,0.008,0.014,0.016,0.021,0.023l85.333,85.333c8.331,8.331,21.839,8.331,30.17,0 c8.331-8.331,8.331-21.839,0-30.17l-48.915-48.915H384c11.782,0,21.333-9.551,21.333-21.333S395.782,234.667,384,234.667z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
            <h1 class="header-title"><strong>Actualizar</strong></h1>
        </div>

        <form id="signature-form" action="{{ route('operario.update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Campos del formulario -->
            <div class="form-group">
                <label for="ciclo">Ciclo:</label>
                <input type="text" name="ciclo" class="form-control" value="{{ old('ciclo', $data->ciclo) }}" disabled>
            </div>

            <div class="form-group">
                <label for="cuenta">Cuenta:</label>
                <input type="text" name="cuenta" class="form-control" value="{{ old('cuenta', $data->cuenta) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $data->direccion) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="medidor">Medidor:</label>
                <input type="text" name="medidor" class="form-control" value="{{ old('medidor', $data->medidor) }}"
                    disabled>
            </div>

            <div class="form-group">
                <label for="nombre_cliente">Nombre Cliente:</label>
                <input type="text" name="nombre_cliente" class="form-control"
                    value="{{ old('nombre_cliente', $data->nombre_cliente) }}">
                @error('nombre_cliente')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="form-control"
                    value="{{ old('telefono', $data->telefono) }}" maxlength="10">
                @error('telefono')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $data->email) }}">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <br><br>
            <!-- Campo de firma -->
            <div class="form-group">
                <div class="signature-label-container">
                    <label for="firma">Firma:</label>
                    <button type="button" id="clear-signature" class="btn-clear">
                        <svg width="40px" height="40px" viewBox="0 0 48 48" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g clip-path="url(#clip0)">
                                    <rect width="48" height="48" fill="white" fill-opacity="0.01"></rect>
                                    <path
                                        d="M44.7818 24.1702L31.918 7.09938L14.1348 20.5L27.5 37L30.8556 34.6644L44.7818 24.1702Z"
                                        fill="#ff2e2e" stroke="#000000" stroke-width="4.30201" stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M27.4998 37L23.6613 40.0748L13.0978 40.074L10.4973 36.6231L4.06543 28.0876L14.4998 20.2248"
                                        stroke="#000000" stroke-width="4.30201" stroke-linejoin="round"></path>
                                    <path d="M13.2056 40.0721L44.5653 40.072" stroke="#000000" stroke-width="4.5"
                                        stroke-linecap="round"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0">
                                        <rect width="48" height="48" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </g>
                        </svg>

                    </button>
                </div>
                <br>
                <div class="signature-container">
                    <canvas id="signature-pad" class="signature-pad" style="border: 1px solid #ccc;"></canvas>
                </div>
                <input type="file" id="signature-file" name="firma" style="display: none;">
            </div>



            <!-- Reemplaza el botón con un checkbox y un texto -->
            <div class="form-group checkbox-label">
                <input type="checkbox" id="save-signature-checkbox">
                <div class="terminos">
                    <label for="save-signature-checkbox" class="terminos">Autorizo a RIB Logísticas SAS a utilizar y
                        almacenar
                        sus datos personales, incluyendo su número de teléfono y correo electrónico, conforme a la Ley 1581
                        de
                        2012. Esta información será utilizada únicamente para fines relacionados con la prestación de
                        nuestros
                        servicios.</label>
                </div>
            </div>

            <!-- Contenedor para centrar el botón de actualización -->
            <div class="btn-container">
                <button type="submit" id="update-button" class="btn btn-primary"
                    style="display: none;">Actualizar</button>
            </div>
        </form>
    </div>

@section('scripts')
    <!-- Para manejar la firma electrónica y almacenarla en el input file -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('save-signature-checkbox').addEventListener('change', function() {
            if (this.checked) {
                if (!signaturePad.isEmpty()) {
                    var signatureData = signaturePad.toDataURL('image/png');
                    var blob = dataURLToBlob(signatureData);
                    var file = new File([blob], 'signature.png', {
                        type: 'image/png'
                    });
                    var input = document.getElementById('signature-file');
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;

                    // Mostrar el botón de actualizar después de confirmar la firma
                    document.getElementById('update-button').style.display = 'block';
                } else {
                    // Desmarcar el checkbox si la firma está vacía
                    this.checked = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Firma requerida',
                        text: 'Debe agregar una firma.',
                        confirmButtonColor: '#0072C6'
                    });
                }
            } else {
                // Ocultar el botón de actualizar si se desmarca el checkbox
                document.getElementById('update-button').style.display = 'none';
            }
        });
    </script>
    <script>
        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad(canvas);

        document.getElementById('save-signature-checkbox').addEventListener('change', function() {
            if (this.checked) {
                if (!signaturePad.isEmpty()) {
                    var signatureData = signaturePad.toDataURL('image/png');
                    var blob = dataURLToBlob(signatureData);
                    var file = new File([blob], 'signature.png', {
                        type: 'image/png'
                    });
                    var input = document.getElementById('signature-file');
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;

                    // Mostrar el botón de actualizar después de confirmar la firma
                    document.getElementById('update-button').style.display = 'block';
                } else {
                    // Desmarcar el checkbox si la firma está vacía
                    this.checked = false;
                    alert('Debe agregar una firma.');
                }
            } else {
                // Ocultar el botón de actualizar si se desmarca el checkbox
                document.getElementById('update-button').style.display = 'none';
            }
        });

        function dataURLToBlob(dataURL) {
            var byteString = atob(dataURL.split(',')[1]);
            var mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], {
                type: mimeString
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const telefonoInput = document.getElementById('telefono');

            telefonoInput.addEventListener('input', function() {
                let value = this.value;
                if (value.length > 10) {
                    this.value = value.slice(0, 10); // Limitar a 10 dígitos
                }
            });

            document.getElementById('signature-form').addEventListener('submit', function(event) {
                const telefonoValue = telefonoInput.value;
                if (telefonoValue.length !== 10) {
                    event.preventDefault();
                    alert('El número de teléfono debe tener exactamente 10 dígitos.');
                }
            });
        });
        document.getElementById('clear-signature').addEventListener('click', function() {
            signaturePad.clear();
        });


        document.getElementById('save-signature-checkbox').addEventListener('change', function() {
            if (this.checked) {
                if (!signaturePad.isEmpty()) {
                    var signatureData = signaturePad.toDataURL('image/png');
                    var blob = dataURLToBlob(signatureData);
                    var file = new File([blob], 'signature.png', {
                        type: 'image/png'
                    });
                    var input = document.getElementById('signature-file');
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;

                    document.getElementById('update-button').style.display = 'block';
                } else {
                    this.checked = false;
                    alert('Debe agregar una firma.');
                }
            } else {
                document.getElementById('update-button').style.display = 'none';
            }
        });
    </script>
@endsection
@endsection
