<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Editar Venta</title>
    <link rel="stylesheet" href="{{ asset('css/FRU.css') }}?v=2">
</head>
<body>
<div class="contenedor">
    <div class="sidebar">
        <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
        <ul class="menu">
            <li><a href="{{ route('gestion.ventas') }}">← Volver</a></li>
        </ul>
    </div>

    <div class="main">
        <h2 class="st">Editar Venta #{{ $venta->Id }}</h2>

        @if(session('error'))
            <p style="color:red; margin-left:100px;">{{ session('error') }}</p>
        @endif

        <div class="form-wrapper">
            <form action="{{ route('gestion.ventas.update', $venta->Id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Observación</label>
                    <textarea name="observacion" rows="3"
                        style="padding:12px; border-radius:8px; border:1px solid #333; background-color:#1a1a1a; color:white; font-size:14px; outline:none; resize:vertical; width:100%; box-sizing:border-box;">{{ $venta->Observacion }}</textarea>
                </div>

                <div class="form-group">
                    <label>Empleado</label>
                    <select name="empleado_id" required>
                        @foreach($empleados as $e)
                            <option value="{{ $e->Id }}"
                                {{ $venta->Empleado_Usuarios_Id == $e->Id ? 'selected' : '' }}>
                                {{ $e->P_Nombre }} {{ $e->P_Apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Cliente</label>
                    <select name="cliente_id" required>
                        @foreach($clientes as $c)
                            <option value="{{ $c->Id }}"
                                {{ $venta->Clientes_Usuarios_Id == $c->Id ? 'selected' : '' }}>
                                {{ $c->P_Nombre }} {{ $c->P_Apellido }}
                            </option>
                        @endforeach
                    </select>
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