<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Proveedor</title>
    <link rel="stylesheet" href="{{ asset('css/PPA.css') }}?v=2">
</head>
<body>
    <div class="contenedor">

        @if(session('mostrar_bienvenida'))
        <div id="welcomeOverlay" class="overlay">
            <div class="welcome-box">
                <img src="{{ asset('images/logo.png') }}" class="logo1">
                <h2>Bienvenido Proveedor</h2>
            </div>
        </div>
        @php session()->forget('mostrar_bienvenida') @endphp
        @endif

        <div class="sidebar">
            <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
            <ul class="menu">
                <li><a href="{{ route('inventario.proveedor') }}">Inventario</a></li>
                <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="main">
            <div class="profile-wrapper">
                <div class="profile-circle">
                    <img src="{{ asset('images/UP.png') }}">
                </div>
                <div class="profile-info">
                    <div class="info-item">
                        <label>Nombre</label>
                        <p>{{ session('nombre') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Teléfono</label>
                        <p>{{ session('telefono') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Correo</label>
                        <p>{{ session('usuario') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Dirección</label>
                        <p>{{ session('direccion') ?? 'No registrada' }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
    const overlay = document.getElementById('welcomeOverlay');
    if (overlay) {
        setTimeout(function() {
            overlay.style.opacity = '0';
            overlay.style.transition = 'opacity 0.5s ease';
            setTimeout(function() {
                overlay.style.display = 'none';
            }, 500);
        }, 2000);
    }
    </script>

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