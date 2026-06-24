<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Inventario</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('empleado') }}">Perfil</a></li>
        <li><a href="{{ route('citas.empleado') }}">Citas</a></li>
        <li><a href="{{ route('ventas.empleado') }}">Ventas</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">
    <h2 class="st">Inventario</h2>

    <div class="action-container">
        <button onclick="window.location.href='{{ route('inventario.form') }}'" class="btn-submit">
            Registrar Producto
        </button>
    </div>

    <div class="inventory-wrapper">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Mín</th>
                    <th>Máx</th>
                    <th>Foto</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $p)
                <tr>
                    <td>{{ $p->Id }}</td>
                    <td>{{ $p->Nombre }}</td>
                    <td>{{ $p->Precio }}</td>
                    <td>{{ $p->Categoria }}</td>
                    <td>{{ $p->Stock }}</td>
                    <td>{{ $p->Cantidad_Minima }}</td>
                    <td>{{ $p->Cantidad_Maxima }}</td>
                    <td>
                        @if($p->foto)
                            <img src="/{{ $p->foto }}"
                                 alt="{{ $p->Nombre }}"
                                 style="width:55px; height:55px; object-fit:cover; border-radius:6px; border:1px solid rgba(255,255,255,0.15);">
                        @else
                            <span style="color:#888; font-size:12px;">Sin foto</span>
                        @endif
                    </td>
                    <td>{{ $p->Estado ? 'Activo' : 'Inactivo' }}</td>
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