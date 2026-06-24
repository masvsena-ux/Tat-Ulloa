<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulloa Tatú - Mis Citas</title>
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
            --danger:  #ff5555;
            --success: #4caf7d;
            --warning: #f0a500;
            --radius:  12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body { background: var(--bg); color: var(--text); font-family: 'Segoe UI', sans-serif; }

        .nav-saludo { color: var(--muted); font-size: 0.85rem; margin-right: 4px; }
        .nav-btn.activo { background: var(--grad); border-color: transparent; color: #fff; }

        /* ── Vistas ── */
        .vista { display: none; }
        .vista.activa { display: block; }

        /* ── Layout principal ── */
        .citas-main {
            padding: 40px;
            min-height: 100vh;
            max-width: 900px;
            margin: 0 auto;
        }

        /* ── Header ── */
        .citas-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .citas-header h2 {
            font-size: 1.7rem;
            font-weight: 700;
            background: var(--grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-nueva-cita {
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
            text-decoration: none;
        }

        .btn-nueva-cita:hover { opacity: 0.9; transform: scale(1.03); }

        /* ── Lista de citas ── */
        .citas-lista { display: flex; flex-direction: column; gap: 18px; }

        .cita-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 20px;
            transition: border-color 0.2s;
        }

        .cita-card:hover { border-color: rgba(132,37,147,0.3); }

        .cita-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-bottom: 14px;
            margin-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .cita-fecha { font-size: 0.95rem; font-weight: 600; }

        .cita-estado {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 999px;
            text-transform: capitalize;
        }

        .estado-activa    { background: rgba(76,175,125,0.15); color: var(--success); }
        .estado-cancelada { background: rgba(255,85,85,0.15);  color: var(--danger); }

        .cita-id { font-size: 0.78rem; color: var(--muted); }

        .cita-obs { font-size: 0.85rem; color: var(--muted); line-height: 1.5; }

        .cita-obs strong {
            color: var(--text);
            display: block;
            margin-bottom: 4px;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .empty-state { text-align: center; padding: 70px 20px; color: var(--muted); }

        /* ══════════════════════════════
           FORMULARIO REGISTRO CITA
        ══════════════════════════════ */
        .form-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }

        .form-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
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
            white-space: nowrap;
        }

        .btn-volver:hover { border-color: var(--accent2); }

        .cita-form-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
            max-width: 620px;
        }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            color: var(--muted);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-group input[type="datetime-local"],
        .form-group textarea,
        .form-group input[type="file"] {
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

        .form-group input[type="datetime-local"]:focus,
        .form-group textarea:focus { border-color: var(--accent2); }

        .form-group textarea { resize: vertical; }

        .form-group small { font-size: 0.75rem; color: var(--muted); margin-top: 5px; display: block; }

        /* alerts */
        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .alert-ok  { background: rgba(76,175,125,0.15); color: var(--success); border: 1px solid rgba(76,175,125,0.3); }
        .alert-err { background: rgba(255,85,85,0.15);  color: var(--danger);  border: 1px solid rgba(255,85,85,0.3); }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--grad);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-top: 6px;
        }

        .btn-submit:hover { opacity: 0.88; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-left">
            <img src="{{ asset('images/logo.png') }}" class="logo-img">
            <a href="{{ route('cliente') }}" class="nav-btn">Tienda</a>
            <a href="{{ route('citas.cliente') }}" class="nav-btn activo">Mis Citas</a>
            <a href="{{ route('compras.cliente') }}" class="nav-btn">Mis Compras</a>
        </div>
        <div class="nav-right">
            <span class="nav-saludo">Hola, {{ session('nombre') }}</span>
            <a href="{{ route('logout') }}" class="nav-btn">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="citas-main">

    {{-- ════════════════════════════════
         VISTA 1 — MIS CITAS
    ════════════════════════════════ --}}
    <div id="vista-citas" class="vista activa">

        <div class="citas-header">
            <h2>Mis Citas</h2>
            <button class="btn-nueva-cita" onclick="mostrarVista('vista-form')">
                + Registrar cita
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-ok" style="margin-bottom:20px;">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-err" style="margin-bottom:20px;">✕ {{ session('error') }}</div>
        @endif

        <div class="citas-lista">
            @forelse($citas as $c)
            <div class="cita-card">
                <div class="cita-card-header">
                    <div class="cita-fecha">
                        {{ \Carbon\Carbon::parse($c->Fecha_Hora)->format('d/m/Y H:i') }}
                    </div>
                    <div class="cita-estado estado-{{ strtolower($c->Estado) }}">
                        {{ $c->Estado }}
                    </div>
                    <div class="cita-id">Cita #{{ $c->Id }}</div>
                </div>

                @if($c->Observaciones)
                <div class="cita-obs">
                    <strong>Observaciones</strong>
                    {{ $c->Observaciones }}
                </div>
                @else
                <div class="cita-obs" style="font-style:italic;">Sin observaciones.</div>
                @endif
            </div>
            @empty
            <div class="empty-state">
                <p style="font-size:1.1rem; margin-bottom:8px;">No tienes citas registradas</p>
                <p style="font-size:0.85rem;">Presiona "Registrar cita" para agendar una.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ════════════════════════════════
         VISTA 2 — FORMULARIO
    ════════════════════════════════ --}}
    <div id="vista-form" class="vista">

        <div class="form-header">
            <button class="btn-volver" onclick="mostrarVista('vista-citas')">
                ← Volver
            </button>
            <h2>Registrar nueva cita</h2>
        </div>

        <div class="cita-form-card">
            <form action="{{ route('citas.cliente.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Fecha y hora</label>
                    <input type="datetime-local" name="fecha_hora" required>
                </div>

                <div class="form-group">
                    <label>Observaciones</label>
                    <textarea name="observaciones" rows="4" placeholder="Describe los detalles de tu cita..."></textarea>
                </div>

                <div class="form-group">
                    <label>Diseño del tatuaje (opcional)</label>
                    <input type="file" name="diseño" accept=".jpg,.jpeg,.png">
                    <small>Solo imágenes JPG o PNG</small>
                </div>

                <button type="submit" class="btn-submit">Registrar cita ✓</button>
            </form>
        </div>
    </div>

</div>

<script>
function mostrarVista(id) {
    document.querySelectorAll('.vista').forEach(v => v.classList.remove('activa'));
    document.getElementById(id).classList.add('activa');
    window.scrollTo(0, 0);
}

// Si hay errores de validación, volver al formulario automáticamente
@if($errors->any() || session('form_error'))
    document.addEventListener('DOMContentLoaded', () => mostrarVista('vista-form'));
@endif
</script>

</body>
</html>