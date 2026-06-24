<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú</title>
    <link rel="stylesheet" href="{{ asset('css/Index.css') }}">
    <style>
        :root {
            --bg:      #0d0d0d;
            --surface: #161616;
            --card:    #1c1c1c;
            --border:  rgba(255,255,255,0.08);
            --accent1: #0833a2;
            --accent2: #842593;
            --grad:    linear-gradient(135deg, var(--accent1), var(--accent2));
            --text:    #f0f0f0;
            --muted:   #888;
            --radius:  12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: 'Segoe UI', sans-serif; }

        /* ── Sección empresa ── */
        .company-section {
            padding: 60px 40px 40px;
            max-width: 860px;
            margin: 0 auto;
        }

        .company-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 36px 40px;
        }

        .company-title {
            font-size: 2.2rem;
            font-weight: 800;
            background: var(--grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 16px;
        }

        .company-description {
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .company-info-row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .info-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 20px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            min-width: 200px;
        }

        .info-label {
            font-size: 0.72rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text);
        }

        /* ── Sección productos ── */
        .productos-section {
            padding: 0 40px 60px;
            max-width: 860px;
            margin: 0 auto;
        }

        .productos-section h2 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 22px;
            background: var(--grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 22px;
        }

        /* ── Tarjeta producto (igual al carrito) ── */
        .prod-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .prod-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(132,37,147,0.2);
        }

        .prod-card-img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            background: #111;
        }

        .prod-card-img-placeholder {
            width: 100%;
            aspect-ratio: 1/1;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 0.75rem;
        }

        .prod-card-body {
            padding: 14px 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .prod-card-nombre {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .prod-card-cat {
            font-size: 0.75rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .prod-card-precio {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            margin-top: 4px;
        }

        .prod-card-stock {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .prod-card-stock.bajo    { color: #f0a500; }
        .prod-card-stock.agotado { color: #ff5555; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-left">
            <img src="{{ asset('images/logo.png') }}" class="logo-img">
            <a href="{{ route('login') }}" class="nav-btn">Citas</a>
            <a href="{{ route('login') }}" class="nav-btn">Compras</a>
        </div>
        <div class="nav-right">
            <a href="{{ route('login') }}" class="nav-btn">Iniciar Sesión</a>
            <a href="{{ route('registro') }}" class="nav-btn">Registrarse</a>
        </div>
    </div>
</nav>

<div class="company-section">
    <div class="company-card">
        <h1 class="company-title">Ulloa Tatú</h1>
        <p class="company-description">
            Local especializado en productos de parafernalia,
            tatuajes artísticos y perforaciones profesionales.
            Trabajamos bajo estrictos estándares de higiene y calidad,
            brindando una experiencia segura y personalizada.
        </p>
        <div class="company-info-row">
            <div class="info-box">
                <span class="info-label">Contacto</span>
                <span class="info-value">+57 318 843 5490</span>
            </div>
            <div class="info-box">
                <span class="info-label">Dirección</span>
                <span class="info-value">Calle 57B Sur #66-68, Villa Del Rio</span>
            </div>
        </div>
    </div>
</div>

<div class="productos-section">
    <h2>Nuestros productos</h2>
    <div class="productos-grid">
        @forelse($productos as $p)
        <div class="prod-card">
            @if($p->foto)
                <img class="prod-card-img" src="/{{ $p->foto }}" alt="{{ $p->Nombre }}">
            @else
                <div class="prod-card-img-placeholder">Sin imagen</div>
            @endif

            <div class="prod-card-body">
                <div class="prod-card-nombre">{{ $p->Nombre }}</div>
                <div class="prod-card-cat">{{ $p->Categoria }}</div>
                <div class="prod-card-precio">${{ number_format($p->Precio, 0, ',', '.') }}</div>
                <div class="prod-card-stock {{ $p->Stock == 0 ? 'agotado' : ($p->Stock <= $p->Cantidad_Minima ? 'bajo' : '') }}">
                    @if($p->Stock == 0)
                        Agotado
                    @elseif($p->Stock <= $p->Cantidad_Minima)
                        Stock bajo · {{ $p->Stock }} uds.
                    @else
                        {{ $p->Stock }} disponibles
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p style="color:var(--muted); font-size:0.9rem;">No hay productos disponibles.</p>
        @endforelse
    </div>
</div>

</body>
</html>