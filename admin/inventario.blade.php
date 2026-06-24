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
        <li><a href="{{ route('admin') }}">Perfil</a></li>
        <li><a href="{{ route('usuarios') }}">Usuarios</a></li>
        <li><a href="{{ route('citas') }}">Citas</a></li>
        <li><a href="{{ route('ventas') }}">Ventas</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">
    <h2 class="st">Inventario</h2>

    <div class="action-container">
    <button onclick="window.location.href='{{ route('inventario.form') }}'" class="btn-submit">
        Registrar Producto
    </button>
    <button onclick="window.location.href='{{ route('inventario.inactivos') }}'" class="btn-submit" style="margin-left: 10px">
        Ver Inactivos
    </button>
</div>

    <div class="inventory-wrapper">
        <table class="inventory-table">
            <thead>
    <thead>
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Categoría</th>
        <th>Stock</th>
        <th>Mín</th>
        <th>Máx</th>
         <th>foto</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
</thead>
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
        <td style="display:flex; gap:8px;">
            <a href="{{ route('inventario.edit', $p->Id) }}"
               style="padding:6px 12px; background:linear-gradient(#111,#111) padding-box, linear-gradient(to right,#0833a2,#842593) border-box; border:2px solid transparent; border-radius:8px; color:white; text-decoration:none; font-size:13px;">
                Editar
            </a>
            <form action="{{ route('inventario.destroy', $p->Id) }}" method="POST"
                  onsubmit="return confirm('¿Seguro que quieres desactivar este producto?')">
                @csrf
                <button type="submit"
                    style="padding:6px 12px; background:rgba(255,50,50,0.15); border:1px solid rgba(255,50,50,0.4); border-radius:8px; color:#ff5555; cursor:pointer; font-size:13px;">
                    Desactivar
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>
        </table>
    </div>
</div>
</body>
</html>