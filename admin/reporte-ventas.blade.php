<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Reporte de Ventas</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .main {
            margin-left: 70px;
            overflow-x: hidden;
            min-width: 0;
            box-sizing: border-box;
        }

        .reporte-container {
            padding: 8px 14px;
            box-sizing: border-box;
            width: 100%;
            max-width: calc(100vw - 70px);
            overflow-x: hidden;
        }

        .filtros {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 8px 0 12px 0;
            flex-wrap: wrap;
        }

        .filtros label {
            color: #aaa;
            font-size: 11px;
        }

        .filtros select {
            padding: 4px 8px;
            background: #1d1d1d;
            border: 1px solid #333;
            border-radius: 5px;
            color: #fff;
            font-size: 11px;
            cursor: pointer;
            outline: none;
        }

        .filtros select:focus { border-color: #842593; }

        .btn-filtrar {
            padding: 4px 10px;
            background: linear-gradient(to right, #0833a2, #842593);
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-filtrar:hover { opacity: 0.85; }

        .cards-resumen {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .card-stat {
            background: #1c1c1c;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 6px;
            padding: 8px 10px;
        }

        .card-stat span {
            display: block;
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 3px;
        }

        .card-stat strong {
            font-size: 1.1rem;
            font-weight: 700;
            background: linear-gradient(to right, #0833a2, #842593);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .grafica-box {
            background: #1c1c1c;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 12px;
            width: 100%;
            box-sizing: border-box;
        }

        .grafica-box h3 {
            font-size: 11px;
            font-weight: 700;
            color: #f0f0f0;
            margin-bottom: 8px;
        }

        .detalle-box {
            background: #1c1c1c;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 6px;
            padding: 10px;
            box-sizing: border-box;
            max-width: calc(100vw - 70px - 28px);
            overflow-x: auto;
        }

        .detalle-box h3 {
            font-size: 11px;
            font-weight: 700;
            color: #f0f0f0;
            margin-bottom: 8px;
        }

        .detalle-box table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            table-layout: fixed;
        }

        .detalle-box table th:nth-child(1),
        .detalle-box table td:nth-child(1) { width: 3%; }
        .detalle-box table th:nth-child(2),
        .detalle-box table td:nth-child(2) { width: 15%; }
        .detalle-box table th:nth-child(3),
        .detalle-box table td:nth-child(3) { width: 9%; }
        .detalle-box table th:nth-child(4),
        .detalle-box table td:nth-child(4) { width: 16%; }
        .detalle-box table th:nth-child(5),
        .detalle-box table td:nth-child(5) { width: 14%; }
        .detalle-box table th:nth-child(6),
        .detalle-box table td:nth-child(6) { width: 14%; }
        .detalle-box table th:nth-child(7),
        .detalle-box table td:nth-child(7) { width: 19%; }

        .detalle-box th {
            text-align: left;
            padding: 5px 1px;
            color: #888;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .detalle-box td {
            padding: 5px 1px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            color: #e0e0e0;
            vertical-align: top;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .detalle-box td:nth-child(4),
        .detalle-box td:nth-child(7) {
            white-space: normal;
        }

        .detalle-box tr:last-child td { border-bottom: none; }
        .detalle-box tr:hover td { background: rgba(132,37,147,0.05); }

        .badge-completada {
            display: inline-block;
            padding: 1px 6px;
            background: rgba(76,175,125,0.15);
            border: 1px solid rgba(76,175,125,0.3);
            border-radius: 999px;
            color: #4caf7d;
            font-size: 12px;
        }

        .productos-lista {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .productos-lista span {
            font-size: 12px;
            color: #aaa;
        }

        .empty-reporte {
            text-align: center;
            padding: 16px;
            color: #555;
            font-size: 11px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('admin') }}">Perfil</a></li>
        <li><a href="{{ route('usuarios') }}">Usuarios</a></li>
        <li><a href="{{ route('citas') }}">Citas</a></li>
        <li><a href="{{ route('inventario') }}">Inventario</a></li>
        <li><a href="{{ route('ventas') }}">Ventas</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="main">
<div class="reporte-container">

    <h2 class="st" style="font-size: 14px; margin: 10px 0 10px 0;">Reporte de Ventas</h2>

    {{-- ── Filtros ── --}}
    <form method="GET" action="{{ route('reporte.ventas') }}" class="filtros">
        <label>Año:</label>
        <select name="anio">
            @foreach($anios as $a)
                <option value="{{ $a }}" {{ $anioSel == $a ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>

        <label>Mes:</label>
        <select name="mes">
            <option value="0" {{ $mesSel == 0 ? 'selected' : '' }}>Todos</option>
            @foreach([1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
                      7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'] as $n => $nombre)
                <option value="{{ $n }}" {{ $mesSel == $n ? 'selected' : '' }}>{{ $nombre }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn-filtrar">Filtrar</button>
        <a href="{{ route('reporte.ventas') }}"
           style="padding:4px 10px; border:1px solid #444; border-radius:5px; color:#aaa; font-size:11px; text-decoration:none;">
            Limpiar
        </a>
    </form>

    {{-- ── Tarjetas resumen ── --}}
    <div class="cards-resumen">
        <div class="card-stat">
            <span>Total ventas</span>
            <strong>{{ $totalVentas }}</strong>
        </div>
        <div class="card-stat">
            <span>Productos vendidos</span>
            <strong>{{ $totalProductos }}</strong>
        </div>
        <div class="card-stat">
            <span>Clientes atendidos</span>
            <strong>{{ $totalClientes }}</strong>
        </div>
    </div>

    {{-- ── Gráfica ── --}}
    <div class="grafica-box">
        <h3>Ventas por mes {{ $anioSel }}</h3>
        <canvas id="graficaVentas" height="70"></canvas>
    </div>

    {{-- ── Tabla detalle ── --}}
    <div class="detalle-box">
        <h3>Detalle de ventas</h3>

        @if(count($ventas) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha/Hora</th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th>Empleado</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $v)
                <tr>
                    <td>{{ $v->Id }}</td>
                    <td>{{ $v->Fecha_Hora }}</td>
                    <td><span class="badge-completada">{{ $v->Estado }}</span></td>
                    <td>{{ $v->Observacion ?? '—' }}</td>
                    <td>{{ $v->NombreEmpleado ?? $v->Empleado_Usuarios_Id }}</td>
                    <td>{{ $v->NombreCliente ?? $v->Clientes_Usuarios_Id }}</td>
                    <td>
                        <div class="productos-lista">
                            @if(isset($v->productos) && count($v->productos) > 0)
                                @foreach($v->productos as $prod)
                                    <span>• {{ $prod->Nombre }}</span>
                                @endforeach
                            @else
                                <span>Servicio de tatuaje</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-reporte">No hay ventas registradas para el período seleccionado.</div>
        @endif
    </div>

</div>
</div>

<script>
const meses   = @json($graficaMeses);
const totales = @json($graficaTotales);

const ctx = document.getElementById('graficaVentas').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: meses,
        datasets: [{
            label: 'Ventas',
            data: totales,
            backgroundColor: 'rgba(132, 37, 147, 0.5)',
            borderColor: '#842593',
            borderWidth: 2,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.parsed.y} ventas`
                }
            }
        },
        scales: {
            x: {
                ticks: { color: '#888', font: { size: 10 } },
                grid:  { color: 'rgba(255,255,255,0.04)' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#888', stepSize: 1, font: { size: 10 } },
                grid:  { color: 'rgba(255,255,255,0.04)' }
            }
        }
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tab = document.createElement('div');
    tab.classList.add('menu-tab');
    tab.innerText = 'MENÚ';
    document.body.appendChild(tab);

    const sidebar = document.querySelector('.sidebar');
    sidebar.addEventListener('mouseenter', () => tab.classList.add('oculto'));
    sidebar.addEventListener('mouseleave', () => tab.classList.remove('oculto'));
});
</script>

</body>
</html>