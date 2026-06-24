<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ulloa Tatú - Editar Producto</title>
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
            @elseif(session('rol') == 'U3')
                <li><a href="{{ route('proveedor') }}">Perfil</a></li>
                <li><a href="{{ route('inventario.proveedor') }}">Inventario</a></li>
            @endif
            <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main">
        <h2 class="st">Editar Producto</h2>

        @if(session('error'))
            <p style="color:red; margin-left:100px;">{{ session('error') }}</p>
        @endif

        <div class="form-wrapper">
            <form action="{{ route('inventario.update', $producto->Id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="inventario_id" value="{{ $producto->Id_Inventario }}">

                <div class="form-group">
                    <label>Código del Producto</label>
                    <input type="text" value="{{ $producto->Id }}" readonly
                           style="opacity:0.5; cursor:not-allowed;">
                </div>

                <div class="form-group">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre" value="{{ $producto->Nombre }}" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="4" required
                        style="width:100%; padding:12px; background:#1d1d1d; color:#fff; border:1px solid #2d2c2c; border-radius:8px; font-size:14px; resize:vertical; box-sizing:border-box;"
                    >{{ $producto->Descripccion }}</textarea>
                </div>

                <div class="form-group">
                    <label>Precio</label>
                    <input type="number" name="precio" value="{{ $producto->Precio }}" required>
                </div>

                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria" required>
                        @foreach($categorias as $c)
                            <option value="{{ $c->Id }}"
                                {{ $producto->T_Producto_Id == $c->Id ? 'selected' : '' }}>
                                {{ $c->T_Producto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" value="{{ $producto->Stock }}" required>
                </div>

                <div class="form-group">
                    <label>Cantidad mínima</label>
                    <input type="number" name="cantidad_minima" value="{{ $producto->Cantidad_Minima }}" required>
                </div>

                <div class="form-group">
                    <label>Cantidad máxima</label>
                    <input type="number" name="cantidad_maxima" value="{{ $producto->Cantidad_Maxima }}" required>
                </div>

                <div class="form-group">
                    <label>Foto actual</label>
                    @if($producto->Foto)
                        <div style="margin-bottom:10px;">
                            <img src="{{ asset($producto->Foto) }}"
                                 style="max-width:200px; border-radius:8px; border:1px solid #333; display:block;">
                        </div>
                    @else
                        <p style="opacity:0.5; font-size:13px; margin-bottom:10px;">Sin foto registrada</p>
                    @endif
                </div>

                <div class="form-group">
                    <label>Cambiar foto (opcional)</label>
                    <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"
                        style="padding:12px; border-radius:8px; border:1px solid #333; background-color:#1a1a1a; color:white; font-size:14px; width:100%; box-sizing:border-box;">
                    <small style="opacity:0.5; font-size:12px;">Si no seleccionas ninguna se conserva la foto actual</small>
                </div>

                <button type="submit" class="btn-submit">Guardar Cambios</button>
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