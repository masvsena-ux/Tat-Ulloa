<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Mis Compras</title>
    <link rel="stylesheet" href="{{ asset('css/Index.css') }}">
    <style>
        :root {
            --bg:        #0d0d0d;
            --surface:   #161616;
            --card:      #1c1c1c;
            --border:    rgba(255,255,255,0.08);
            --accent1:   #0833a2;
            --accent2:   #842593;
            --grad:      linear-gradient(135deg, var(--accent1), var(--accent2));
            --text:      #f0f0f0;
            --muted:     #888;
            --danger:    #ff5555;
            --success:   #4caf7d;
            --warning:   #f0a500;
            --radius:    12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body { background: var(--bg); color: var(--text); font-family: 'Segoe UI', sans-serif; }

        /* ── Navbar (mismo diseño que la página de inicio) ── */
        .nav-saludo {
            color: var(--muted);
            font-size: 0.85rem;
            margin-right: 4px;
        }

        .nav-btn.activo {
            background: var(--grad);
            border-color: transparent;
            color: #fff;
        }

        .compras-main {
            padding: 40px;
            min-height: 100vh;
            max-width: 900px;
            margin: 0 auto;
        }

        .compras-header { margin-bottom: 28px; }

        .compras-header h2 {
            font-size: 1.7rem;
            font-weight: 700;
            background: var(--grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .compras-lista { display: flex; flex-direction: column; gap: 18px; }

        .compra-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 20px;
            transition: border-color 0.2s;
        }

        .compra-card:hover { border-color: rgba(132,37,147,0.3); }

        .compra-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-bottom: 14px;
            margin-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .compra-fecha { font-size: 0.95rem; font-weight: 600; }

        .compra-item-img {
            width: 34px;
            height: 34px;
            object-fit: cover;
            border-radius: 6px;
            background: #111;
        }

        .compra-estado {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 999px;
            text-transform: capitalize;
        }

        .estado-pendiente   { background: rgba(240,165,0,0.15);  color: var(--warning); }
        .estado-completado  { background: rgba(76,175,125,0.15); color: var(--success); }
        .estado-cancelado    { background: rgba(255,85,85,0.15);  color: var(--danger); }

        .compra-total { font-size: 1.1rem; font-weight: 700; }

        .compra-items { display: flex; flex-direction: column; gap: 8px; }

        .compra-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            gap: 10px;
        }

        .compra-item-nombre { flex: 1; }
        .compra-item-qty { color: var(--muted); }
        .compra-item-precio { font-weight: 600; white-space: nowrap; }

        .compra-obs {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            font-size: 0.82rem;
            color: var(--muted);
        }

        .empty-state {
            text-align: center;
            padding: 70px 20px;
            color: var(--muted);
        }
    </style>
</head>
<body>

{{-- ══ Navbar (mismo diseño que la página de inicio) ══ --}}
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-left">
            <img src="{{ asset('images/logo.png') }}" class="logo-img">
            <a href="{{ route('cliente') }}" class="nav-btn">Tienda</a>
            <a href="{{ route('citas.cliente') }}" class="nav-btn">Mis Citas</a>
            <a href="{{ route('compras.cliente') }}" class="nav-btn activo">Mis Compras</a>
        </div>
        <div class="nav-right">
            <span class="nav-saludo">Hola, {{ session('nombre') }}</span>
            <a href="{{ route('logout') }}" class="nav-btn">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="compras-main">

    <div class="compras-header">
        <h2>Mis Compras</h2>
    </div>

    <div class="compras-lista">
        @forelse($compras as $compra)
        <div class="compra-card">
            <div class="compra-card-header">
                <div class="compra-fecha">{{ \Carbon\Carbon::parse($compra->Fecha_Hora)->format('d/m/Y H:i') }}</div>
                <div class="compra-estado estado-{{ strtolower($compra->Estado) }}">
                    {{ $compra->Estado }}
                </div>
                <div class="compra-total">${{ number_format($compra->total, 0, ',', '.') }}</div>
            </div>

            <div class="compra-items">
                @forelse($compra->productos as $prod)
                <div class="compra-item">
                    @if($prod->foto)
                        <img class="compra-item-img" src="/{{ $prod->foto }}" alt="{{ $prod->Nombre }}">
                    @endif
                    <span class="compra-item-nombre">{{ $prod->Nombre }}</span>
                    <span class="compra-item-precio">${{ number_format($prod->Precio, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="compra-item">
                    <span class="compra-item-nombre" style="color:var(--muted);">Sin productos registrados</span>
                </div>
                @endforelse
            </div>

            @if($compra->Observacion)
            <div class="compra-obs">📝 {{ $compra->Observacion }}</div>
            @endif
        </div>
        @empty
        <div class="empty-state">
            <p style="font-size:1.1rem; margin-bottom:8px;">Aún no tienes compras</p>
            <p style="font-size:0.85rem;">Cuando realices un pedido, aparecerá aquí.</p>
        </div>
        @endforelse
    </div>

</div>

</body>
</html>