<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Registrar Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
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
    <h2 class="st">Registrar Usuario</h2>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="inventory-wrapper">
        <form action="{{ route('usuarios.store') }}" method="POST" class="form-container">
            @csrf

            <div class="form-group">
                <label for="tipo_documento">Tipo de documento</label>
                <select name="tipo_documento" id="tipo_documento" required>
                    <option value="">Seleccione...</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="CE">Cédula de Extranjería</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="PA">Pasaporte</option>
                </select>
            </div>

            <div class="form-group">
                <label for="numero_documento">Número de documento</label>
                <input type="text" name="numero_documento" id="numero_documento" required>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" name="nombre" id="nombre" placeholder="Primer Nombre Segundo Nombre Primer Apellido Segundo Apellido" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol</label>
                <select name="rol" id="rol" required>
                    <option value="">Seleccione...</option>
                    <option value="U1">Administrador</option>
                    <option value="U2">Empleado</option>
                    <option value="U3">Proveedor</option>
                </select>
            </div>

            <div class="action-container">
                <button type="submit" class="btn-submit">Registrar</button>
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