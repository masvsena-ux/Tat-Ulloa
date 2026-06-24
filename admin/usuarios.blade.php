<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Usuarios</title>
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
    <h2 class="st">Usuarios</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="action-container">
        <button onclick="window.location.href='{{ route('usuarios.form') }}'" class="btn-submit">
            Registrar Usuario
        </button>
        <button onclick="window.location.href='{{ route('usuarios.inactivos') }}'" class="btn-submit" style="margin-left: 5px;">
            Ver Usuarios Inactivos
        </button>
    </div>

    <div class="inventory-wrapper">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $u)
                <tr>
                    <td>{{ $u->Id }}</td>
                    <td>{{ $u->Id_tdoc }}</td>
                    <td>{{ $u->P_Nombre }} {{ $u->P_Apellido }}</td>
                    <td>{{ $u->Correo }}</td>
                    <td>{{ $u->Telefono }}</td>
                    <td>{{ $u->Rol }}</td>
                    <td>{{ $u->Direccion ?: 'No registrada' }}</td>
                    <td>
                        {{-- Botón editar: solo si NO es cliente --}}
                        @if($u->Rol_Id_Rol !== 'U4')
                            <button onclick="window.location.href='{{ route('usuarios.edit', $u->Id) }}'" class="btn-submit">
                                Editar
                            </button>
                        @endif

                        {{-- Botón inactivar: solo si NO es el admin logueado --}}
                        @if($u->Id != $adminId && $u->Rol_Id_Rol !== 'U4')
    <form action="{{ route('usuarios.inactivar', $u->Id) }}" method="POST"
          style="display:inline"
          onsubmit="return confirm('¿Inactivar este usuario?');">
        @csrf
        <button type="submit" class="btn-submit">Inactivar</button>
    </form>
@endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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