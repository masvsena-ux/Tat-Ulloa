<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Registro Producto</title>
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
                <li><a href="{{ route('citas') }}">Citas</a></li>
                <li><a href="{{ route('ventas') }}">Ventas</a></li>
            @elseif(session('rol') == 'U2')
                <li><a href="{{ route('empleado') }}">Perfil</a></li>
                <li><a href="{{ route('inventario.empleado') }}">Inventario</a></li>
                <li><a href="{{ route('citas.empleado') }}">Citas</a></li>
                <li><a href="{{ route('ventas.empleado') }}">Ventas</a></li>
            @elseif(session('rol') == 'U3')
                <li><a href="{{ route('proveedor') }}">Perfil</a></li>
                <li><a href="{{ route('inventario.proveedor') }}">Inventario</a></li>
            @endif
            <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main">
        <h2 class="st">Registro de Producto</h2>

        @if(session('error'))
            <p style="color:red; margin-left:100px;">{{ session('error') }}</p>
        @endif

        <div class="form-wrapper">
            <form class="user-form" action="{{ route('inventario.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Código del Producto</label>
                    <input type="text" name="codigo" maxlength="50" placeholder="Ingrese el código" required>
                </div>
                <div class="form-group">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre" placeholder="Ingrese el nombre del producto" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea
                        name="descripcion"
                        rows="4"
                        placeholder="Ingrese la descripción del producto"
                        required
                        style="width:100%; padding:12px; background:#1d1d1d; color:#fff; border:1px solid #2d2c2c; border-radius:8px; font-size:14px; resize:vertical; box-sizing:border-box;"
                    ></textarea>
                </div>
                <div class="form-group">
                    <label>Cantidad</label>
                    <input type="number" name="cantidad" min="1" max="100" placeholder="Ingrese la cantidad disponible" required>
                </div>
                <div class="form-group">
                    <label>Precio</label>
                    <input type="number" name="precio" min="0" step="0.01" placeholder="Ingrese el precio" required>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria" required>
                        <option value="">Seleccione...</option>
                        <option value="Joyeria">Joyeria</option>
                        <option value="Parafernalia">Parafernalia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Foto del Producto</label>
                    <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"
                        style="padding:12px; border-radius:8px; border:1px solid #333; background-color:#1a1a1a; color:white; font-size:14px; width:100%; box-sizing:border-box;">
                    <small style="opacity:0.5; font-size:12px;">JPG, PNG o WEBP</small>
                </div>
                <button type="submit" class="btn-submit">Registrar</button>
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