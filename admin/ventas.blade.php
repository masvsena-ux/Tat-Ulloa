<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Tienda</title>
    <link rel="stylesheet" href="{{ asset('css/PI.css') }}">
    <style>
        /* ── Variables ── */
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
            --radius:    12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body { background: var(--bg); color: var(--text); font-family: 'Segoe UI', sans-serif; }

        /* ── Secciones (vistas) ── */
        .vista { display: none; }
        .vista.activa { display: block; }

        /* ── Layout main ── */
        .tienda-main {
            margin-left: 220px;
            padding: 32px 40px;
            min-height: 100vh;
        }

        /* ── Header de sección ── */
        .tienda-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .tienda-header h2 {
            font-size: 1.7rem;
            font-weight: 700;
            background: var(--grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ── Búsqueda ── */
        .search-wrap {
            position: relative;
            flex: 1;
            max-width: 380px;
        }

        .search-wrap input {
            width: 100%;
            padding: 10px 16px 10px 42px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 999px;
            color: var(--text);
            font-size: 0.9rem;
            outline: none;
            transition: border 0.2s;
        }

        .search-wrap input:focus { border-color: var(--accent2); }

        .search-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.4;
        }

        /* ── Botón carrito flotante ── */
        .btn-carrito {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--grad);
            border: none;
            border-radius: 999px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            white-space: nowrap;
        }

        .btn-carrito:hover { opacity: 0.9; transform: scale(1.03); }

        .carrito-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #fff;
            color: var(--accent2);
            border-radius: 999px;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Grid de productos ── */
        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 22px;
        }

        /* ── Tarjeta de producto ── */
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
            padding: 14px 16px;
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

        .prod-card-stock.bajo { color: #f0a500; }
        .prod-card-stock.agotado { color: var(--danger); }

        .btn-agregar {
            margin: 0 16px 16px;
            padding: 9px;
            background: var(--grad);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-agregar:hover { opacity: 0.85; }
        .btn-agregar:disabled { opacity: 0.35; cursor: not-allowed; }

        /* ── Sin resultados ── */
        .empty-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        /* ══════════════════════════════
           CARRITO
        ══════════════════════════════ */
        .carrito-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }

        .btn-volver {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.85rem;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .btn-volver:hover { border-color: var(--accent2); }

        .carrito-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .carrito-layout { grid-template-columns: 1fr; }
        }

        /* ── Items del carrito ── */
        .carrito-items { display: flex; flex-direction: column; gap: 14px; }

        .carrito-item {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
            transition: border-color 0.2s;
        }

        .carrito-item:hover { border-color: rgba(132,37,147,0.3); }

        .carrito-item-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
            background: #111;
        }

        .carrito-item-info { flex: 1; }

        .carrito-item-info h4 { font-size: 0.95rem; font-weight: 600; margin-bottom: 4px; }

        .carrito-item-info span { font-size: 0.8rem; color: var(--muted); }

        .carrito-item-precio { font-size: 1rem; font-weight: 700; white-space: nowrap; }

        .qty-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.2s;
        }

        .qty-btn:hover { border-color: var(--accent2); }

        .qty-num { font-weight: 600; min-width: 20px; text-align: center; }

        .btn-quitar {
            background: none;
            border: none;
            color: var(--danger);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .btn-quitar:hover { background: rgba(255,85,85,0.1); }

        /* ── Resumen ── */
        .carrito-resumen {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 22px;
            position: sticky;
            top: 20px;
        }

        .carrito-resumen h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .resumen-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.88rem;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .resumen-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
            padding-top: 14px;
            border-top: 1px solid var(--border);
            margin-top: 8px;
        }

        .btn-finalizar {
            width: 100%;
            margin-top: 18px;
            padding: 12px;
            background: var(--grad);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-finalizar:hover { opacity: 0.88; }
        .btn-finalizar:disabled { opacity: 0.35; cursor: not-allowed; }

        .carrito-vacio {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        /* ══════════════════════════════
           PAGO
        ══════════════════════════════ */
        .pago-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .pago-layout { grid-template-columns: 1fr; }
        }

        .pago-form {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
        }

        .pago-form h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 22px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            color: var(--muted);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 14px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus { border-color: var(--accent2); }

        .form-group select option { background: var(--surface); }

        /* Métodos de pago como tarjetas */
        .metodos-pago {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .metodo-card {
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: 14px;
            cursor: pointer;
            text-align: center;
            transition: border-color 0.2s, background 0.2s;
            user-select: none;
        }

        .metodo-card:hover { border-color: var(--accent2); }

        .metodo-card.seleccionado {
            border-color: var(--accent2);
            background: rgba(132,37,147,0.12);
        }

        .metodo-card input[type="radio"] { display: none; }

        .metodo-label { font-size: 0.85rem; font-weight: 600; }

        .metodo-bloqueado {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
        }

        .metodo-pronto {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ── Buscador cliente ── */
        .cliente-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            z-index: 100;
            max-height: 220px;
            overflow-y: auto;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }

        .cliente-opcion {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        .cliente-opcion:last-child { border-bottom: none; }
        .cliente-opcion:hover { background: rgba(132,37,147,0.12); }

        .cliente-opcion strong { display: block; font-size: 0.9rem; }
        .cliente-opcion span { font-size: 0.78rem; color: var(--muted); }

        .cliente-opcion-vacio {
            padding: 14px 16px;
            color: var(--muted);
            font-size: 0.85rem;
            text-align: center;
        }

        /* ── Card cliente seleccionado ── */
        .cliente-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(8,51,162,0.12);
            border: 1px solid rgba(8,51,162,0.35);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }

        .cliente-card-info { display: flex; flex-direction: column; gap: 3px; }
        .cliente-card-info strong { font-size: 0.95rem; }
        .cliente-card-info span { font-size: 0.78rem; color: var(--muted); }

        .cliente-card-quitar {
            background: none;
            border: none;
            color: var(--danger);
            cursor: pointer;
            font-size: 1rem;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .cliente-card-quitar:hover { background: rgba(255,85,85,0.1); }

        /* ── Botón registrar cliente ── */
        .btn-registrar-cliente {
            width: 100%;
            padding: 10px;
            background: none;
            border: 1px dashed rgba(132,37,147,0.5);
            border-radius: 8px;
            color: var(--accent2);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
            margin-bottom: 4px;
        }

        .btn-registrar-cliente:hover {
            background: rgba(132,37,147,0.08);
            border-color: var(--accent2);
        }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-box {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .modal-header h3 { font-size: 1.05rem; font-weight: 700; }

        .modal-cerrar {
            background: none;
            border: none;
            color: var(--muted);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: color 0.2s;
        }

        .modal-cerrar:hover { color: var(--text); }

        .btn-confirmar {
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            background: var(--grad);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-confirmar:hover { opacity: 0.88; }

        /* ── Resumen pago ── */
        .pago-resumen {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 22px;
            position: sticky;
            top: 20px;
        }

        .pago-resumen h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .pago-item-mini {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .pago-item-mini img {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 6px;
            background: #111;
        }

        .pago-item-mini-info { flex: 1; font-size: 0.82rem; }
        .pago-item-mini-info strong { display: block; }
        .pago-item-mini-info span { color: var(--muted); }
        .pago-item-mini-precio { font-size: 0.85rem; font-weight: 700; }

        /* ── Éxito ── */
        .exito-wrap {
            text-align: center;
            padding: 80px 20px;
        }

        .exito-icono {
            font-size: 4rem;
            margin-bottom: 16px;
        }

        .exito-wrap h2 { font-size: 1.6rem; font-weight: 700; margin-bottom: 10px; }
        .exito-wrap p { color: var(--muted); margin-bottom: 28px; }

        .btn-seguir {
            padding: 12px 28px;
            background: var(--grad);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
        }

        /* ── Toast ── */
        .toast {
            position: fixed;
            bottom: 28px;
            right: 28px;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #fff;
            z-index: 9999;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s, transform 0.3s;
            pointer-events: none;
        }

        .toast.show { opacity: 1; transform: translateY(0); }
        .toast.ok { background: var(--success); }
        .toast.err { background: var(--danger); }

    </style>
</head>
<body>

{{-- ══ Sidebar ══ --}}
<div class="sidebar">
    <img src="{{ asset('images/logo.png') }}" class="logo2 mt">
    <ul class="menu">
        <li><a href="{{ route('admin') }}">Perfil</a></li>
        <li><a href="{{ route('usuarios') }}">Usuarios</a></li>
        <li><a href="{{ route('citas') }}">Citas</a></li>
        <li><a href="{{ route('inventario') }}">Inventario</a></li>
        <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
    </ul>
</div>

<div class="tienda-main">

    {{-- ════════════════════════════════
         VISTA 1 — TIENDA
    ════════════════════════════════ --}}
    <div id="vista-tienda" class="vista activa">

        <div class="tienda-header">
            <h2>Tienda</h2>

            <div class="search-wrap">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="buscador" placeholder="Buscar producto..." oninput="filtrarProductos()">
            </div>

            <button class="btn-carrito" onclick="irACarrito()">
                🛒 Carrito
                <span class="carrito-badge" id="badge">0</span>
            </button>
            <a href="{{ route('gestion.ventas') }}"
   style="display:flex; align-items:center; gap:8px; padding:10px 20px; background:linear-gradient(#111,#111) padding-box, linear-gradient(to right,#0833a2,#842593) border-box; border:2px solid transparent; border-radius:999px; color:white; font-size:0.9rem; font-weight:600; text-decoration:none; white-space:nowrap;">
    📋 Gestión
</a>
        </div>

        <div class="productos-grid" id="grid-productos">
            @forelse($productos as $p)
            <div class="prod-card"
                 data-nombre="{{ strtolower($p->Nombre) }}"
                 data-cat="{{ strtolower($p->Categoria) }}">

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

                <button class="btn-agregar"
                    {{ $p->Stock == 0 ? 'disabled' : '' }}
                    onclick='agregarAlCarrito({
                        id: "{{ $p->Id }}",
                        nombre: @json($p->Nombre),
                        precio: {{ $p->Precio }},
                        stock: {{ $p->Stock }},
                        foto: "{{ $p->foto ? '/'.$p->foto : '' }}",
                        categoria: @json($p->Categoria)
                    })'>
                    {{ $p->Stock == 0 ? 'Sin stock' : '+ Agregar al carrito' }}
                </button>
            </div>
            @empty
            <div class="empty-state">
                <p style="font-size:1.1rem; margin-bottom:8px;">No hay productos disponibles</p>
                <p style="font-size:0.85rem;">Registra productos en el inventario primero.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ════════════════════════════════
         VISTA 2 — CARRITO
    ════════════════════════════════ --}}
    <div id="vista-carrito" class="vista">

        <div class="carrito-header">
            <button class="btn-volver" onclick="irATienda()">
                ← Seguir comprando
            </button>
            <h2 style="font-size:1.4rem; font-weight:700;">Tu carrito</h2>
        </div>

        <div class="carrito-layout">
            <div id="carrito-items" class="carrito-items"></div>

            <div class="carrito-resumen">
                <h3>Resumen del pedido</h3>
                <div class="resumen-row"><span>Subtotal</span><span id="res-subtotal">$0</span></div>
                <div class="resumen-row"><span>Envío</span><span>A calcular</span></div>
                <div class="resumen-total"><span>Total</span><span id="res-total">$0</span></div>
                <button class="btn-finalizar" id="btn-finalizar" onclick="irAPago()">
                    Finalizar compra →
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════
         VISTA 3 — PAGO
    ════════════════════════════════ --}}
    <div id="vista-pago" class="vista">

        <div class="carrito-header">
            <button class="btn-volver" onclick="irACarrito()">
                ← Volver al carrito
            </button>
            <h2 style="font-size:1.4rem; font-weight:700;">Datos de pago</h2>
        </div>


        <div class="pago-layout">
            <div class="pago-form">
                <h3>Cliente</h3>

                {{-- Buscador de cliente --}}
                <div class="form-group" style="position:relative;">
                    <label>Buscar cliente por nombre o teléfono</label>
                    <input type="text" id="cliente-buscar"
                           placeholder="Escribe nombre o teléfono..."
                           oninput="buscarCliente(this.value)"
                           autocomplete="off">
                    <div id="cliente-dropdown" class="cliente-dropdown" style="display:none;"></div>
                </div>

                {{-- Card del cliente seleccionado --}}
                <div id="cliente-seleccionado" class="cliente-card" style="display:none;">
                    <div class="cliente-card-info">
                        <strong id="cli-nombre"></strong>
                        <span id="cli-telefono"></span>
                        <span id="cli-puntos"></span>
                    </div>
                    <button class="cliente-card-quitar" onclick="limpiarCliente()">✕</button>
                </div>

                {{-- Botón registrar cliente --}}
                <button class="btn-registrar-cliente" onclick="abrirModalCliente()">
                    + Registrar nuevo cliente
                </button>

                <div style="border-top:1px solid var(--border); margin: 22px 0;"></div>
                <h3 style="margin-bottom:18px; padding-bottom:12px; border-bottom:1px solid var(--border); font-size:1rem; font-weight:700;">Método de pago</h3>

                <div class="metodos-pago">
                    <label class="metodo-card" id="card-efectivo" onclick="seleccionarMetodo('efectivo')">
                        <input type="radio" name="metodo" value="efectivo">
                        <div class="metodo-label">Efectivo</div>
                    </label>
                    <label class="metodo-card metodo-bloqueado" title="Próximamente">
                        <div class="metodo-label">Tarjeta</div>
                        <div class="metodo-pronto">Próximamente</div>
                    </label>
                    <label class="metodo-card metodo-bloqueado" title="Próximamente">
                        <div class="metodo-label">Nequi / Daviplata</div>
                        <div class="metodo-pronto">Próximamente</div>
                    </label>
                    <label class="metodo-card metodo-bloqueado" title="Próximamente">
                        <div class="metodo-label">Contraentrega</div>
                        <div class="metodo-pronto">Próximamente</div>
                    </label>
                </div>

                <div class="form-group" style="margin-top:18px;">
                    <label>Observaciones (opcional)</label>
                    <textarea id="pago-obs" rows="3" placeholder="Instrucciones especiales..."></textarea>
                </div>

                <button class="btn-confirmar" onclick="confirmarCompra()">
                    Confirmar pedido ✓
                </button>
            </div>

            {{-- Resumen lateral en pago --}}
            <div class="pago-resumen">
                <h3>Tu pedido</h3>
                <div id="pago-items-mini"></div>
                <div class="resumen-total" style="margin-top:16px;">
                    <span>Total</span>
                    <span id="pago-total">$0</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════
         MODAL — REGISTRAR CLIENTE
    ════════════════════════════════ --}}
    <div id="modal-cliente" class="modal-overlay" style="display:none;" onclick="cerrarModalSiAfuera(event)">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Registrar cliente</h3>
                <button class="modal-cerrar" onclick="cerrarModalCliente()">✕</button>
            </div>

            <div class="form-group">
                <label>Número de documento</label>
                <input type="text" id="nc-id" placeholder="Ej: 1023456789">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-group">
                    <label>Primer nombre</label>
                    <input type="text" id="nc-p-nombre" placeholder="Laura">
                </div>
                <div class="form-group">
                    <label>Segundo nombre</label>
                    <input type="text" id="nc-s-nombre" placeholder="Sofía">
                </div>
                <div class="form-group">
                    <label>Primer apellido</label>
                    <input type="text" id="nc-p-apellido" placeholder="Martínez">
                </div>
                <div class="form-group">
                    <label>Segundo apellido</label>
                    <input type="text" id="nc-s-apellido" placeholder="Rojas">
                </div>
            </div>
            <div class="form-group">
                <label>Correo</label>
                <input type="email" id="nc-correo" placeholder="correo@ejemplo.com">
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" id="nc-telefono" placeholder="3100000000">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" id="nc-direccion" placeholder="Calle, número, barrio, ciudad">
            </div>
            <div class="form-group">
                <label>Contraseña temporal</label>
                <input type="text" id="nc-pass" placeholder="Mínimo 4 caracteres">
            </div>

            <button class="btn-confirmar" id="btn-guardar-cliente" onclick="registrarCliente()">
                Guardar cliente
            </button>
        </div>
    </div>


    {{-- ════════════════════════════════
         VISTA 4 — ÉXITO
    ════════════════════════════════ --}}
    <div id="vista-exito" class="vista">
        <div class="exito-wrap">
            <div class="exito-icono">🎉</div>
            <h2>¡Pedido confirmado!</h2>
            <p>Tu pedido ha sido registrado correctamente.<br>Nos pondremos en contacto contigo pronto.</p>
            <button class="btn-seguir" onclick="reiniciar()">Seguir comprando</button>
        </div>
    </div>

</div>

{{-- Toast --}}
<div class="toast" id="toast"></div>

<script>
// ════════════════════════════════
//  ESTADO
// ════════════════════════════════
let carrito = [];          // [{id, nombre, precio, stock, foto, categoria, qty}]
let metodoSeleccionado = null;

// ════════════════════════════════
//  NAVEGACIÓN
// ════════════════════════════════
function mostrarVista(id) {
    document.querySelectorAll('.vista').forEach(v => v.classList.remove('activa'));
    document.getElementById(id).classList.add('activa');
    window.scrollTo(0, 0);
}

function irATienda()   { mostrarVista('vista-tienda'); }
function irACarrito()  { renderCarrito(); mostrarVista('vista-carrito'); }
function irAPago()     {
    if (carrito.length === 0) { toast('El carrito está vacío', 'err'); return; }
    renderPagoResumen();
    mostrarVista('vista-pago');
}

// ════════════════════════════════
//  CARRITO — LÓGICA
// ════════════════════════════════
function agregarAlCarrito(prod) {
    const idx = carrito.findIndex(p => p.id === prod.id);
    if (idx >= 0) {
        if (carrito[idx].qty >= prod.stock) {
            toast('Stock máximo alcanzado', 'err');
            return;
        }
        carrito[idx].qty++;
    } else {
        carrito.push({ ...prod, qty: 1 });
    }
    actualizarBadge();
    toast(`${prod.nombre} agregado al carrito`, 'ok');
}

function quitarDelCarrito(id) {
    carrito = carrito.filter(p => p.id !== id);
    actualizarBadge();
    renderCarrito();
}

function cambiarQty(id, delta) {
    const idx = carrito.findIndex(p => p.id === id);
    if (idx < 0) return;
    const nuevo = carrito[idx].qty + delta;
    if (nuevo < 1) { quitarDelCarrito(id); return; }
    if (nuevo > carrito[idx].stock) { toast('Stock máximo alcanzado', 'err'); return; }
    carrito[idx].qty = nuevo;
    actualizarBadge();
    renderCarrito();
}

function actualizarBadge() {
    const total = carrito.reduce((s, p) => s + p.qty, 0);
    document.getElementById('badge').textContent = total;
}

function calcularTotal() {
    return carrito.reduce((s, p) => s + p.precio * p.qty, 0);
}

function fmtPrecio(n) {
    return '$' + n.toLocaleString('es-CO');
}

// ════════════════════════════════
//  RENDER CARRITO
// ════════════════════════════════
function renderCarrito() {
    const cont = document.getElementById('carrito-items');
    const total = calcularTotal();

    if (carrito.length === 0) {
        cont.innerHTML = `
            <div class="carrito-vacio">
                <p style="font-size:2rem; margin-bottom:12px;">🛒</p>
                <p style="font-size:1rem; margin-bottom:6px;">Tu carrito está vacío</p>
                <p style="font-size:0.85rem; color:var(--muted);">Agrega productos desde la tienda.</p>
            </div>`;
        document.getElementById('btn-finalizar').disabled = true;
    } else {
        cont.innerHTML = carrito.map(p => `
            <div class="carrito-item">
                ${p.foto
                    ? `<img class="carrito-item-img" src="${p.foto}" alt="${p.nombre}">`
                    : `<div class="carrito-item-img" style="display:flex;align-items:center;justify-content:center;color:#555;font-size:0.75rem;">Sin foto</div>`
                }
                <div class="carrito-item-info">
                    <h4>${p.nombre}</h4>
                    <span>${p.categoria}</span>
                </div>
                <div class="qty-control">
                    <button class="qty-btn" onclick="cambiarQty('${p.id}', -1)">−</button>
                    <span class="qty-num">${p.qty}</span>
                    <button class="qty-btn" onclick="cambiarQty('${p.id}', 1)">+</button>
                </div>
                <div class="carrito-item-precio">${fmtPrecio(p.precio * p.qty)}</div>
                <button class="btn-quitar" onclick="quitarDelCarrito('${p.id}')" title="Quitar">✕</button>
            </div>
        `).join('');
        document.getElementById('btn-finalizar').disabled = false;
    }

    document.getElementById('res-subtotal').textContent = fmtPrecio(total);
    document.getElementById('res-total').textContent = fmtPrecio(total);
}

// ════════════════════════════════
//  RENDER PAGO RESUMEN
// ════════════════════════════════
function renderPagoResumen() {
    const cont = document.getElementById('pago-items-mini');
    cont.innerHTML = carrito.map(p => `
        <div class="pago-item-mini">
            ${p.foto
                ? `<img src="${p.foto}" alt="${p.nombre}">`
                : `<div style="width:44px;height:44px;border-radius:6px;background:#111;"></div>`
            }
            <div class="pago-item-mini-info">
                <strong>${p.nombre}</strong>
                <span>x${p.qty}</span>
            </div>
            <div class="pago-item-mini-precio">${fmtPrecio(p.precio * p.qty)}</div>
        </div>
    `).join('');
    document.getElementById('pago-total').textContent = fmtPrecio(calcularTotal());
}

// ════════════════════════════════
//  CLIENTE SELECCIONADO
// ════════════════════════════════
let clienteId = null;
let buscarTimer = null;

function buscarCliente(q) {
    clearTimeout(buscarTimer);
    const dd = document.getElementById('cliente-dropdown');

    if (q.trim().length < 2) { dd.style.display = 'none'; return; }

    buscarTimer = setTimeout(async () => {
        try {
            const res  = await fetch(`{{ route('clientes.buscar') }}?q=${encodeURIComponent(q)}`, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();

            if (!data.length) {
                dd.innerHTML = `<div class="cliente-opcion-vacio">Sin resultados — registra al cliente abajo</div>`;
            } else {
                dd.innerHTML = data.map(c => `
                    <div class="cliente-opcion" onclick='seleccionarCliente(${JSON.stringify(c)})'>
                        <strong>${c.nombre}</strong>
                        <span>${c.telefono} · ${c.puntos} puntos</span>
                    </div>
                `).join('');
            }
            dd.style.display = 'block';
        } catch(e) {
            dd.style.display = 'none';
        }
    }, 300);
}

function seleccionarCliente(c) {
    clienteId = c.id;
    document.getElementById('cliente-buscar').value = '';
    document.getElementById('cliente-dropdown').style.display = 'none';
    document.getElementById('cli-nombre').textContent   = c.nombre;
    document.getElementById('cli-telefono').textContent = c.telefono;
    document.getElementById('cli-puntos').textContent   = c.puntos + ' puntos';
    document.getElementById('cliente-seleccionado').style.display = 'flex';
}

function limpiarCliente() {
    clienteId = null;
    document.getElementById('cliente-seleccionado').style.display = 'none';
    document.getElementById('cliente-buscar').value = '';
}

// ════════════════════════════════
//  MODAL REGISTRAR CLIENTE
// ════════════════════════════════
function abrirModalCliente() {
    document.getElementById('modal-cliente').style.display = 'flex';
}

function cerrarModalCliente() {
    document.getElementById('modal-cliente').style.display = 'none';
}

function cerrarModalSiAfuera(e) {
    if (e.target.id === 'modal-cliente') cerrarModalCliente();
}

async function registrarCliente() {
    const id        = document.getElementById('nc-id').value.trim();
    const pNombre   = document.getElementById('nc-p-nombre').value.trim();
    const sNombre   = document.getElementById('nc-s-nombre').value.trim();
    const pApellido = document.getElementById('nc-p-apellido').value.trim();
    const sApellido = document.getElementById('nc-s-apellido').value.trim();
    const correo    = document.getElementById('nc-correo').value.trim();
    const telefono  = document.getElementById('nc-telefono').value.trim();
    const direccion = document.getElementById('nc-direccion').value.trim();
    const pass      = document.getElementById('nc-pass').value.trim();

    if (!id || !pNombre || !pApellido || !correo || !telefono || !pass) {
        toast('Completa los campos obligatorios', 'err'); return;
    }

    const btn = document.getElementById('btn-guardar-cliente');
    btn.disabled = true;
    btn.textContent = 'Guardando...';

    try {
        const res  = await fetch('{{ route("clientes.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id, p_nombre: pNombre, s_nombre: sNombre, p_apellido: pApellido, s_apellido: sApellido, correo, telefono, direccion, contrasena: pass })
        });
        const data = await res.json();

        if (data.ok) {
            seleccionarCliente({ id: data.id, nombre: pNombre + ' ' + pApellido, telefono, puntos: 0 });
            cerrarModalCliente();
            toast('Cliente registrado y seleccionado', 'ok');
            // Limpiar modal
            ['nc-id','nc-p-nombre','nc-s-nombre','nc-p-apellido','nc-s-apellido','nc-correo','nc-telefono','nc-direccion','nc-pass']
                .forEach(f => document.getElementById(f).value = '');
        } else {
            toast(data.error || 'Error al registrar', 'err');
        }
    } catch(e) {
        toast('Error de conexión', 'err');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Guardar cliente';
    }
}

// ════════════════════════════════
//  MÉTODO DE PAGO
// ════════════════════════════════
function seleccionarMetodo(m) {
    metodoSeleccionado = m;
    document.getElementById('card-efectivo').classList.toggle('seleccionado', m === 'efectivo');
}

// ════════════════════════════════
//  CONFIRMAR COMPRA
// ════════════════════════════════
async function confirmarCompra() {
    if (!clienteId)             { toast('Selecciona un cliente', 'err'); return; }
    if (!metodoSeleccionado)    { toast('Selecciona un método de pago', 'err'); return; }

    const obs = document.getElementById('pago-obs').value.trim();
    const btn = document.querySelector('.btn-confirmar');
    btn.disabled = true;
    btn.textContent = 'Procesando...';

    try {
        const res = await fetch('{{ route("ventas.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                cliente_id:  clienteId,
                metodo_pago: metodoSeleccionado,
                observacion: obs,
                productos:   carrito.map(p => ({ id: p.id, qty: p.qty, precio: p.precio }))
            })
        });

        const data = await res.json();

        if (data.ok) {
            mostrarVista('vista-exito');
        } else {
            toast(data.error || 'Error al procesar el pedido', 'err');
            btn.disabled = false;
            btn.textContent = 'Confirmar pedido ✓';
        }
    } catch (e) {
        toast('Error de conexión', 'err');
        btn.disabled = false;
        btn.textContent = 'Confirmar pedido ✓';
    }
}

// ════════════════════════════════
//  REINICIAR
// ════════════════════════════════
function reiniciar() {
    carrito = [];
    clienteId = null;
    metodoSeleccionado = null;
    actualizarBadge();
    limpiarCliente();
    document.getElementById('pago-obs').value = '';
    document.getElementById('card-efectivo').classList.remove('seleccionado');
    const btn = document.querySelector('.btn-confirmar');
    btn.disabled = false;
    btn.textContent = 'Confirmar pedido ✓';
    irATienda();
}

// ════════════════════════════════
//  BÚSQUEDA
// ════════════════════════════════
function filtrarProductos() {
    const q = document.getElementById('buscador').value.toLowerCase().trim();
    document.querySelectorAll('#grid-productos .prod-card').forEach(card => {
        const nombre = card.dataset.nombre || '';
        const cat    = card.dataset.cat    || '';
        card.style.display = (nombre.includes(q) || cat.includes(q)) ? '' : 'none';
    });
}

// ════════════════════════════════
//  TOAST
// ════════════════════════════════
let toastTimer;
function toast(msg, tipo = 'ok') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className   = `toast ${tipo} show`;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => el.classList.remove('show'), 2800);
}
</script>
</body>
</html>