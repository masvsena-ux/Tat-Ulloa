<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Citas</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('admin') }}">Perfil</a></li>
        <li><a href="{{ route('usuarios') }}">Usuarios</a></li>
        <li><a href="{{ route('inventario') }}">Inventario</a></li>
        <li><a href="{{ route('ventas') }}">Ventas</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">

    @if(session('success'))
        <div style="background:rgba(0,255,150,0.1); border:1px solid rgba(0,255,150,0.3); color:#00ff96; padding:10px 15px; border-radius:8px; margin:20px 0 0 100px; display:inline-block;">
            {{ session('success') }}
        </div>
    @endif

    <br>

    <h2 class="st">Citas</h2>

    <div class="action-container" style="display:flex; margin-left: 95px;">
        <button onclick="window.location.href='{{ route('citas.form') }}'" class="btn-submit" style="margin:10px 5px;">
            Registrar Cita
        </button>
        <button onclick="window.location.href='{{ route('citas.inactivas') }}'" class="btn-submit" style="margin:10px 5px;">
            Ver Canceladas
        </button>
    </div>

    <div class="inventory-wrapper">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha/Hora</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                    <th>Tipo Tatuaje</th>
                    <th>Diseño</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($citas as $c)
                <tr>
                    <td>{{ $c->Id }}</td>
                    <td>{{ $c->Fecha_Hora }}</td>
                    <td>
                        @if($c->Estado == 1)
                            <span style="color:#00ff96;">Activa</span>
                        @elseif($c->Estado == 2)
                            <span style="color:#a78bfa;">Completada</span>
                        @else
                            <span style="color:#ff5555;">Cancelada</span>
                        @endif
                    </td>
                    <td>{{ $c->Observaciones }}</td>
                    <td>
    @if($c->Tipo_Tatuaje)
        {{ $c->Tipo_Tatuaje }}
    @else
        <span style="opacity:0.4; font-size:12px;">N/A</span>
    @endif
</td>
                    <td>
                        @if($c->{'Diseño'})
                            <a href="{{ asset($c->{'Diseño'}) }}" target="_blank">
                                <img src="{{ asset($c->{'Diseño'}) }}"
                                     style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                            </a>
                        @else
                            <span style="opacity:0.4; font-size:12px;">Sin diseño</span>
                        @endif
                    </td>
                    <td style="display:flex; gap:8px;">
                        @if($c->Estado == 1)
                            <a href="{{ route('citas.edit', $c->Id) }}"
                               style="padding:6px 12px; background:linear-gradient(#111,#111) padding-box, linear-gradient(to right,#0833a2,#842593) border-box; border:2px solid transparent; border-radius:8px; color:white; text-decoration:none; font-size:13px;">
                                Editar
                            </a>

                            <form action="{{ route('citas.completar', $c->Id) }}" method="POST"
                                  onsubmit="return confirm('¿Marcar esta cita como completada?')">
                                @csrf
                                <button type="submit"
                                    style="padding:6px 12px; background:rgba(0,255,150,0.15); border:1px solid rgba(0,255,150,0.4); border-radius:8px; color:#00ff96; cursor:pointer; font-size:13px;">
                                    Terminar
                                </button>
                            </form>

                            <form action="{{ route('citas.inactivar', $c->Id) }}" method="POST"
                                  onsubmit="return confirm('¿Cancelar esta cita?')">
                                @csrf
                                <button type="submit"
                                    style="padding:6px 12px; background:rgba(255,50,50,0.15); border:1px solid rgba(255,50,50,0.4); border-radius:8px; color:#ff5555; cursor:pointer; font-size:13px;">
                                    Cancelar
                                </button>
                            </form>
                        @else
                            <span style="opacity:0.4; font-size:12px;">Sin acciones</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
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
</html>