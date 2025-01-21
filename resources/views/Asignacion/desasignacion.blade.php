@php
    use App\Models\User;
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Programaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/Asignacion.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/Modal.js') }}">

    <link rel="icon" href="{{ asset('img/FLATICON_RIB.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('img/FLATICON_RIB.jpg') }}" type="image/jpeg" sizes="48x48">
</head>

<body>
    <div class="main-container">
        <form action="{{ route('filtrar.desasignacion') }}" method="post">
            @csrf
            <div class="mb-3">
                <input type="text" name="buscador-nombre" class="input" placeholder="Nombre...">
                <input type="text" name="buscador-cuenta" class="input" placeholder="Cuenta...">
                <input type="text" name="buscador-medidor" class="input" placeholder="Medidor...">
                <input type="text" name="buscador-ciclo" class="input" placeholder="Ciclo...">
            </div>
            <div class="mb-3">
                <button type="submit">
                    <span class="shadow"></span>
                    <span class="edge"></span>
                    <span class="front text">Aplicar filtros</span>
                </button>

                <a href="{{ route('operario.asignacion') }}">

                    <button class="custom-button"
                        style="--btn-background-color: hsl(220deg 100% 47%); --btn-edge-color: hsl(220deg 100% 32%);"
                        type="button">
                        <span class="shadow"></span>
                        <span class="edge"></span>
                        <span class="front text">Volver</span>
                    </button>

                </a>



            </div>
        </form>

        <form action="{{ route('desasignar.operario') }}" method="post">
            @csrf
            <div class="table-container">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr class="text-center">
                                <th class="text-center">
                                    <label class="container">
                                        <input type="checkbox" id="seleccionarTodo">
                                        <div class="checkmark"></div>
                                    </label>
                                </th>

                                <th>ID</th>
                                <th>Ciclo</th>
                                <th>Nombre Cliente</th>
                                <th>Operario</th>
                                <th>Cuenta</th>
                                <th>Dirección</th>
                                <th>Recorrido</th>
                                <th>Medidor</th>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Período</th>

                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            @foreach ($data as $programacion)
                                <tr>
                                    <td style='text-align: center; white-space: nowrap;'>
                                        <label class='container'>
                                            <input type='checkbox' class='form-check-input' name='Programacion[]'
                                                value='{{ $programacion->id }}'>
                                            <div class="checkmark"></div>
                                        </label>
                                    </td>
                                    <td>{{ $programacion->id }}</td>
                                    <td style="padding: 8px 2px;">{{ $programacion->ciclo }}</td>
                                    <td>{{ $programacion->nombre_cliente }}</td>

                                    @php
                                        $nombre = User::find($programacion->id_operario);
                                    @endphp

                                    <td>{{ $nombre->nombre }}</td>
                                    <td>{{ $programacion->cuenta }}</td>
                                    <td>{{ $programacion->direccion }}</td>
                                    <td>{{ $programacion->recorrido }}</td>
                                    <td>{{ $programacion->medidor }}</td>
                                    <td style="text-align: center;">{{ $programacion->año }}</td>
                                    <td style="text-align: center;">{{ $programacion->mes }}</td>
                                    <td style="text-align: center;">{{ $programacion->periodo }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <section class="footer py-100">
                <br>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        @if ($paginaActual > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ route('desasignacion', ['page' => $paginaActual - 1]) }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        @php
                            $rangoMostrar = 3;
                            $inicioRango = max(1, $paginaActual - $rangoMostrar);
                            $finRango = min($totalPaginas, $paginaActual + $rangoMostrar);

                            if ($inicioRango > 1) {
                                echo '<li class="page-item"><a class="page-link" href="' .
                                    route('desasignacion', ['page' => 1]) .
                                    '">1</a></li>';
                                if ($inicioRango > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }

                            for ($i = $inicioRango; $i <= $finRango; $i++) {
                                $activeClass = $i == $paginaActual ? 'active' : '';
                                echo '<li class="page-item ' .
                                    $activeClass .
                                    '"><a class="page-link" href="' .
                                    route('desasignacion', ['page' => $i]) .
                                    '">' .
                                    $i .
                                    '</a></li>';
                            }

                            if ($finRango < $totalPaginas) {
                                if ($finRango < $totalPaginas - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="' .
                                    route('desasignacion', ['page' => $totalPaginas]) .
                                    '">' .
                                    $totalPaginas .
                                    '</a></li>';
                            }
                        @endphp

                        @if ($paginaActual < $totalPaginas)
                            <li class="page-item">
                                <a class="page-link" href="{{ route('desasignacion', ['page' => $paginaActual + 1]) }}"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @endif
                        <button class="custom-button"
                            style="--btn-background-color: hsl(120deg 100% 47%); --btn-edge-color: hsl(120deg 100% 32%);"
                            type="submit">
                            <span class="shadow"></span>
                            <span class="edge"></span>
                            <span class="front text">Desasignar</span>
                        </button>
                    </ul>
                </nav>

            </section>

        </form>
        <script src="{{ asset('assets/js/Modal.js') }}"></script>
        <script>
            document.getElementById('seleccionarTodo').addEventListener('change', function() {
                var checkboxes = document.querySelectorAll('input[name="Programacion[]"]');
                for (var checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            });
            document.addEventListener('DOMContentLoaded', adjustFooter);
            window.addEventListener('scroll', adjustFooter);
            window.addEventListener('resize', adjustFooter);

            function adjustFooter() {
                const footer = document.querySelector('.footer');
                const contentHeight = document.querySelector('.content').offsetHeight;
                const windowHeight = window.innerHeight;

                if (contentHeight > windowHeight) {
                    footer.classList.remove('footer-fixed');
                    footer.classList.add('footer-static');
                } else {
                    footer.classList.remove('footer-static');
                    footer.classList.add('footer-fixed');
                }
            }
        </script>
        {{-- SELECCION POR ZONA CON LA TECLA SHIFT --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let lastChecked = null;

                const checkboxes = document.querySelectorAll('input[name="Programacion[]"]');

                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('click', function(event) {
                        if (!lastChecked) {
                            lastChecked = this;
                            return;
                        }

                        if (event.shiftKey) {
                            let inBetween = false;
                            checkboxes.forEach((currentCheckbox) => {
                                if (currentCheckbox === this || currentCheckbox ===
                                    lastChecked) {
                                    inBetween = !inBetween;
                                }

                                if (inBetween) {
                                    currentCheckbox.checked = lastChecked.checked;
                                }
                            });
                        }

                        lastChecked = this;
                    });
                });
            });
        </script>
</body>

</html>
