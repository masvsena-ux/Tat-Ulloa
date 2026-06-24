<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Productos Inactivos</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('admin') }}">Perfil</a></li>
        <li><a href="{{ route('usuarios') }}">Usuarios</a></li>
        <li><a href="{{ route('citas') }}">Citas</a></li>
        <li><a href="{{ route('ventas') }}">Ventas</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">

    @if(session('success'))
    <div style="background:rgba(0,255,150,0.1); border:1px solid rgba(0,255,150,0.3); color:#00ff96; padding:10px 15px; border-radius:8px; margin:10px 0 10px 100px;">
        {{ session('success') }}
    </div>
    @endif

    <h2 class="st">Productos Inactivos</h2>

<div class="action-container">
    <button onclick="window.location.href='{{ route('inventario') }}'" class="btn-submit">
        ← Volver a Activos
    </button>
</div>

    <div class="inventory-wrapper">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $p)
                <tr>
                    <td>{{ $p->Nombre }}</td>
                    <td>{{ $p->Precio }}</td>
                    <td>{{ $p->Categoria }}</td>
                    <td>{{ $p->Stock }}</td>
                    <td>
                        <form action="{{ route('inventario.reactivar', $p->Id) }}" method="POST"
                              onsubmit="return confirm('¿Reactivar este producto?')">
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
                        <td colspan="5" style="text-align:center; opacity:0.5;">No hay productos inactivos</td>
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