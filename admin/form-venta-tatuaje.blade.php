<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Registrar Venta</title>
    <link rel="stylesheet" href="{{ asset('css/FRU.css') }}?v=2">
</head>
<body>
<div class="contenedor">
    <div class="sidebar">
        <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
        <ul class="menu">
            @if(session('rol') == 'U1')
                <li><a href="{{ route('admin') }}">Perfil</a></li>
                <li><a href="{{ route('usuarios') }}">Usuarios</a></li>
                <li><a href="{{ route('inventario') }}">Inventario</a></li>
                <li><a href="{{ route('citas') }}">Citas</a></li>
            @elseif(session('rol') == 'U2')
                <li><a href="{{ route('empleado') }}">Perfil</a></li>
                <li><a href="{{ route('inventario.empleado') }}">Inventario</a></li>
                <li><a href="{{ route('citas.empleado') }}">Citas</a></li>
                <li><a href="{{ route('ventas.empleado') }}">Ventas</a></li>
            @endif
            <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main">
        <h2 class="st">Registrar Venta</h2>

        @if(session('error'))
            <p style="color:red; margin-left:100px;">{{ session('error') }}</p>
        @endif

        <div class="form-wrapper">
            <form class="user-form" action="{{ route('gestion.ventas.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Empleado</label>
                    <select name="empleado_id" required>
                        <option value="">Seleccione un empleado...</option>
                        @foreach($empleados as $e)
                            <option value="{{ $e->Id }}">{{ $e->P_Nombre }} {{ $e->P_Apellido }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Cliente</label>
                    <select name="cliente_id" required>
                        <option value="">Seleccione un cliente...</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->Id }}">{{ $c->P_Nombre }} {{ $c->P_Apellido }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Observación</label>
                    <textarea
                        name="observacion"
                        rows="4"
                        placeholder="Descripción del servicio, detalles del tatuaje, etc."
                        style="width:100%; padding:12px; background:#1d1d1d; color:#fff; border:1px solid #2d2c2c; border-radius:8px; font-size:14px; resize:vertical; box-sizing:border-box;"
                    ></textarea>
                </div>

                <button type="submit" class="btn-submit">Registrar Venta</button>
            </form>
        </div>
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