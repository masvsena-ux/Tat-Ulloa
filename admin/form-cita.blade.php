<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Registro Cita</title>
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
                <li><a href="{{ route('inventario') }}">Inventario</a></li>
                <li><a href="{{ route('ventas') }}">Ventas</a></li>
            @elseif(session('rol') == 'U2')
                <li><a href="{{ route('empleado') }}">Perfil</a></li>
                <li><a href="{{ route('inventario.empleado') }}">Inventario</a></li>
                <li><a href="{{ route('citas') }}">Citas</a></li>
                <li><a href="{{ route('ventas.empleado') }}">Ventas</a></li>
            @endif
            <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main">
        <h2 class="st">Registro de Cita</h2>

        @if(session('error'))
            <p style="color:red; margin-left:100px;">{{ session('error') }}</p>
        @endif
        @if(session('success'))
            <p style="color:#00ff96; margin-left:100px;">{{ session('success') }}</p>
        @endif

        <div class="form-wrapper">
            <form class="user-form" action="{{ route('citas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Fecha y Hora</label>
                    <input type="datetime-local" name="fecha_hora" id="fecha_hora" min="" max="" required>
                </div>

                <div class="form-group">
                    <label>Observaciones</label>
                    <textarea name="observaciones" rows="4"
                        placeholder="Ingrese detalles de la cita"
                        style="padding:12px; border-radius:8px; border:1px solid #333; background-color:#1a1a1a; color:white; font-size:14px; outline:none; resize:vertical; width:100%; box-sizing:border-box;"></textarea>
                </div>

                <div class="form-group">
                    <label>¿Es para tatuaje? (opcional)</label>
                    <select name="tipo_tatuaje" id="tipo_tatuaje"
                        style="padding:12px; border-radius:8px; border:1px solid #333; background-color:#1a1a1a; color:white; font-size:14px; width:100%; box-sizing:border-box;">
                        <option value="">-- No aplica --</option>
                        <option value="libre">Diseño libre del tatuador</option>
                        <option value="imagen">Basado en imagen de referencia</option>
                        <option value="mixto">Mixto (imagen + criterio del tatuador)</option>
                    </select>
                </div>

                <div class="form-group" id="grupo_diseno">
                    <label>Diseño de referencia (opcional)</label>
                    <input type="file" name="diseño" accept=".jpg,.jpeg,.png"
                        style="padding:12px; border-radius:8px; border:1px solid #333; background-color:#1a1a1a; color:white; font-size:14px; width:100%; box-sizing:border-box;">
                    <small style="opacity:0.5; font-size:12px;">Solo imágenes JPG o PNG</small>
                </div>

                <button type="submit" class="btn-submit">Registrar Cita</button>
            </form>
        </div>
    </div>
</div>

<script>
    const input = document.getElementById('fecha_hora');
    const ahora = new Date();
    const hoy = ahora.toISOString().slice(0, 10);
    input.min = hoy + 'T09:00';
    input.max = '2099-12-31T20:00';

    input.addEventListener('change', function () {
        const valor = this.value;
        if (!valor) return;
        const fecha = new Date(valor);
        const hora  = fecha.getHours();
        const min   = fecha.getMinutes();
        if (hora < 9) {
            alert('No se pueden agendar citas antes de las 9:00 AM.');
            this.value = '';
        } else if (hora > 20 || (hora === 20 && min > 0)) {
            alert('No se pueden agendar citas después de las 8:00 PM.');
            this.value = '';
        }
    });

    // Mostrar/ocultar campo de imagen según selección
    const tipoTatuaje = document.getElementById('tipo_tatuaje');
    const grupoDiseño = document.getElementById('grupo_diseno');

    function toggleDiseño() {
        const val = tipoTatuaje.value;
        if (val === 'libre') {
            grupoDiseño.style.display = 'none';
        } else {
            grupoDiseño.style.display = 'block';
        }
    }

    tipoTatuaje.addEventListener('change', toggleDiseño);
    toggleDiseño(); // ejecutar al cargar
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