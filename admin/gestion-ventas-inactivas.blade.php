<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Ventas Inactivas</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('admin') }}">Perfil</a></li>
        <li><a href="{{ route('usuarios') }}">Usuarios</a></li>
        <li><a href="{{ route('citas') }}">Citas</a></li>
        <li><a href="{{ route('inventario') }}">Inventario</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">

    @if(session('success'))
        <div style="background:rgba(0,255,150,0.1); border:1px solid rgba(0,255,150,0.3); color:#00ff96; padding:10px 15px; border-radius:8px; margin:10px 20px;">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="st">Ventas Inactivas</h2>

    <div class="action-container">
        <button onclick="window.location.href='{{ route('gestion.ventas') }}'" class="btn-submit">
            ← Volver a Activas
        </button>
    </div>

    <div class="inventory-wrapper">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha/Hora</th>
                    <th>Observación</th>
                    <th>Empleado ID</th>
                    <th>Cliente ID</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $v)
                <tr>
                    <td>{{ $v->Id }}</td>
                    <td>{{ $v->Fecha_Hora }}</td>
                    <td>{{ $v->Observacion }}</td>
                    <td>{{ $v->Empleado_Usuarios_Id }}</td>
                    <td>{{ $v->Clientes_Usuarios_Id }}</td>
                    <td>
                        <form action="{{ route('gestion.ventas.reactivar', $v->Id) }}" method="POST"
                              onsubmit="return confirm('¿Reactivar esta venta?')">
                            @csrf
                            <button type="submit"
                                style="padding:6px 12px; background:rgba(0,255,150,0.1); border:1px solid rgba(0,255,150,0.3); border-radius:8px; color:#00ff96; cursor:pointer; font-size:13px;">
                                Reactivar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; opacity:0.5;">No hay ventas inactivas</td>
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