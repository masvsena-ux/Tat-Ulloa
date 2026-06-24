<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Editar Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/FRU.css') }}?v=2">
    <style>
        .form-container {
            max-width: 480px;
            width: 100%;
            margin-left: 100px;
        }

        .form-group {
            margin-bottom: 12px;
            color: white;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 7px 10px;
            font-size: 13px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            height: 36px;
        }

        .form-group small {
            font-size: 11px;
            color: #888;
        }

        .action-container {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .btn-submit {
            padding: 8px 20px;
            font-size: 13px;
        }

        .menu-tab {
    position: fixed;
    left: 70px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(to bottom, #0833a2, #842593);
    color: white;
    font-size: 11px;
    letter-spacing: 3px;
    padding: 10px 6px;
    border-radius: 0 8px 8px 0;
    writing-mode: vertical-rl;
    z-index: 999;
    opacity: 1;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.menu-tab.oculto {
    opacity: 0;
}

.alert-error {
    color: white;
    margin-left: 100px;
}
        
    </style>
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('admin') }}">Perfil</a></li>
        <li><a href="{{ route('inventario') }}">Inventario</a></li>
        <li><a href="{{ route('citas') }}">Citas</a></li>
        <li><a href="{{ route('ventas') }}">Ventas</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">
    <h2 class="st">Editar Usuario</h2>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="inventory-wrapper">
        <form action="{{ route('usuarios.update', $usuario->Id) }}" method="POST" class="form-container">
            @csrf

            <div class="form-group">
                <label for="tipo_documento">Tipo de documento</label>
                <select name="tipo_documento" id="tipo_documento" required>
                    <option value="CC" @selected($usuario->Id_tdoc == 'CC')>Cédula de Ciudadanía</option>
                    <option value="CE" @selected($usuario->Id_tdoc == 'CE')>Cédula de Extranjería</option>
                    <option value="TI" @selected($usuario->Id_tdoc == 'TI')>Tarjeta de Identidad</option>
                    <option value="PA" @selected($usuario->Id_tdoc == 'PA')>Pasaporte</option>
                </select>
            </div>

            <div class="form-group">
                <label>Número de documento</label>
                <input type="text" value="{{ $usuario->Id }}" disabled>
                <small>El número de documento no se puede modificar.</small>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" name="nombre" id="nombre" value="{{ $nombreCompleto }}" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" value="{{ $usuario->Correo }}" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono" value="{{ $usuario->Telefono }}" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Nueva contraseña</label>
                <input type="password" name="contrasena" id="contrasena" placeholder="Dejar en blanco para no cambiarla">
            </div>

            <div class="form-group">
                <label for="rol">Rol</label>
                <select name="rol" id="rol" required>
                    <option value="U1" @selected($usuario->Rol_Id_Rol == 'U1')>Administrador</option>
                    <option value="U2" @selected($usuario->Rol_Id_Rol == 'U2')>Empleado</option>
                    <option value="U3" @selected($usuario->Rol_Id_Rol == 'U3')>Proveedor</option>
                </select>
            </div>

            <div class="action-container">
                <button type="submit" class="btn-submit">Guardar Cambios</button>
                <button type="button" class="btn-submit" onclick="window.location.href='{{ route('usuarios') }}'">Cancelar</button>
            </div>
        </form>
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