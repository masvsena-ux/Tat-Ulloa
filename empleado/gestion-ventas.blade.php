<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Gestión Ventas</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('empleado') }}">Perfil</a></li>
        <li><a href="{{ route('inventario.empleado') }}">Inventario</a></li>
        <li><a href="{{ route('citas.empleado') }}">Citas</a></li>
        <li><a href="{{ route('ventas.empleado') }}">Ventas</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">

    @if(session('success'))
        <div style="background:rgba(0,255,150,0.1); border:1px solid rgba(0,255,150,0.3); color:#00ff96; padding:10px 15px; border-radius:8px; margin:10px 20px;">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="st">Gestión de Ventas</h2>

    <div class="action-container" style="display:flex; margin-left: 95px;">
        <button onclick="window.location.href='{{ route('ventas.empleado') }}'" class="btn-submit" style="margin:10px 5px;">
            ← Volver a Tienda
        </button>
        <button onclick="window.location.href='{{ route('gestion.ventas.form') }}'" class="btn-submit" style="margin:10px 5px;">
            Registrar Venta
        </button>
    </div>

    <div class="inventory-wrapper">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha/Hora</th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th>Empleado ID</th>
                    <th>Cliente ID</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $v)
                <tr>
                    <td>{{ $v->Id }}</td>
                    <td>{{ $v->Fecha_Hora }}</td>
                    <td>{{ $v->Estado }}</td>
                    <td>{{ $v->Observacion }}</td>
                    <td>{{ $v->Empleado_Usuarios_Id }}</td>
                    <td>{{ $v->Clientes_Usuarios_Id }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; opacity:0.5;">No hay ventas registradas</td>
                </tr>
                @endforelse
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
        sidebar.addEventListener('mouseenter', function () { tab.classList.add('oculto'); });
        sidebar.addEventListener('mouseleave', function () { tab.classList.remove('oculto'); });
    });
</script>
</body>
</html>