<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Registro Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/FRU.css') }}?v=2">
</head>
<body>
<div class="contenedor">
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
        <h2 class="st">Registro de Usuario</h2>

        @if(session('error'))
            <p style="color:red; margin-left:100px;">{{ session('error') }}</p>
        @endif

        <div class="form-wrapper">
            <form class="user-form" action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="nombre" placeholder="Ingrese el nombre completo" required>
                </div>
                <div class="form-group">
                    <label>Número de documento</label>
                    <input type="number" name="numero_documento" placeholder="Ingrese el número de documento" required>
                </div>
                <div class="form-group">
                    <label>Tipo de documento</label>
                    <select name="tipo_documento" required>
                        <option value="CC">Cédula de Ciudadanía</option>
                        <option value="CE">Cédula de Extranjería</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" placeholder="Ingrese el teléfono" required>
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="correo" placeholder="Ingrese el correo" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="contrasena" placeholder="Ingrese la contraseña" required>
                </div>
                <div class="form-group">
                    <label>Tipo de Usuario</label>
                    <select name="rol" required>
                        <option value="U1">Administrador</option>
                        <option value="U2">Empleado</option>
                        <option value="U3">Proveedor</option>
                        <option value="U4">Cliente</option>
                    </select>
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