<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Apptualiza</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/LoginStyle.css">
</head>
<body>
    <div class="login-container">
        <div class="login-brand-section2">
            <img class="brand-logo" src="{{ asset('img/LOGO_RIB_R.png') }}" alt="Logo RIB Logísticas">
        </div>
        <div class="login-form-section">
            <div class="form-header">
                <h2>Iniciar Sesión</h2>
                <p>Bienvenido a Apptualiza</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input
                        id="cedula"
                        type="text"
                        class="form-control @error('cedula') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Correo Electrónico"
                        required
                        autocomplete="off"
                        autofocus
                    >
                    @error('cedula')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input
                        id="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        placeholder="Contraseña"
                        required
                        autocomplete="off"
                    >
                    @error('password')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    Ingresar
                </button>
            </form>
        </div>

        <div class="login-brand-section">
            <img class="brand-logo" src="{{ asset('img/LOGO_RIB_R.png') }}" alt="Logo RIB Logísticas">
            {{-- <img class="brand-logo" src="{{ asset('img/LOGOTIC1.png') }}" alt="Logo TIC ltda"> --}}
            {{-- <div class="brand-text">
                Sistema de Gestión Empresarial
            </div> --}}
        </div>
    </div>
</body>
</html>
