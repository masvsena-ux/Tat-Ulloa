<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Citas</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('empleado') }}">Perfil</a></li>
        <li><a href="{{ route('inventario.empleado') }}">Inventario</a></li>
        <li><a href="{{ route('ventas.empleado') }}">Ventas</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>
<div class="main">
    <h2 class="st">Citas</h2>
    <div class="action-container">
        <button onclick="window.location.href='{{ route('citas.form') }}'" class="btn-submit">Registrar Cita</button>
    </div>
    <div class="inventory-wrapper">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha/Hora</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($citas as $c)
                <tr>
                    <td>{{ $c->Id }}</td>
                    <td>{{ $c->Fecha_Hora }}</td>
                    <td>{{ $c->Estado }}</td>
                    <td>{{ $c->Observaciones }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tab = document.createElement('div');
        tab.classList.add('menu-tab');
        tab.innerText = 'MENÚ';
        document.body.appendChild(tab);

        const sidebar = document.querySelector('.sidebar');

        sidebar.addEventListener('mouseenter', function () {
            tab.classList.add('oculto');
        });

        sidebar.addEventListener('mouseleave', function () {
            tab.classList.remove('oculto');
        });
    });
</script>
</body>
</html>