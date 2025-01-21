<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <title>@yield('tittle')</title>

    @yield('style')
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="logo.png" alt="">
                </span>

                <div class="text logo-text">
                    <span class="name">Apptualiza</span>
                    {{-- <span class="profession"></span> --}}
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Search...">
                </li>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Inicio</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="{{ route('asignar.index') }}">
                            <i class='bx bxs-edit icon'></i>
                            <span class="text nav-text">Asignar</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="{{ route('desasignar.index') }}">
                            <i class='bx bxs-edit icon'></i>
                            <span class="text nav-text">Desasignar</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="{{ route('users.index') }}">
                            <i class='bx bx-user icon' ></i>
                            <span class="text nav-text">Usuarios</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="{{ route('import.import') }}">
                            <i class='bx bx-upload icon' ></i>
                            <span class="text nav-text">Cargar Excel</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-list-check icon' ></i>
                            <span class="text nav-text">Completados</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-download icon' ></i>
                            <span class="text nav-text">Generar Excel</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="#" id="logout-link">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Cerrar sesión</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>

            </div>
        </div>

    </nav>

    {{-- <section class="home">
        <div class="text">Dashboard Sidebar</div>
    </section> --}}

    <div class="content">
        @yield('content')
    </div>


    @yield('scripts')

    <script src="script.js"></script>

    <script>
        const body = document.querySelector('body'),
            sidebar = body.querySelector('nav'),
            toggle = body.querySelector(".toggle"),
            searchBtn = body.querySelector(".search-box"),
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");


        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            document.querySelector('.content').classList.toggle('content-adjusted');
        })

        searchBtn.addEventListener("click", () => {
            sidebar.classList.remove("close");
        })

        modeSwitch.addEventListener("click", () => {
            body.classList.toggle("dark");

            if (body.classList.contains("dark")) {
                modeText.innerText = "Light mode";
            } else {
                modeText.innerText = "Dark mode";

            }
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.getElementById('logout-link');

        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();  // Evita la acción por defecto del enlace

            // Crea el formulario de cierre de sesión
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}'; // Genera la URL de logout usando Blade

            // Agrega el token CSRF al formulario
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Agrega el formulario al cuerpo y envíalo
            document.body.appendChild(form);
            form.submit();  // Envia el formulario
        });
    });

    </script>
</body>
</html>
