<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    // ── Admin ────────────────────────────────────────────────

    public function usuarios()
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    $usuarios = DB::select(
        'SELECT u.Id, u.Id_tdoc, u.P_Nombre, u.S_Nombre, u.P_Apellido, u.S_Apellido,
                u.Correo, u.Telefono, u.Rol_Id_Rol, r.Rol,
                COALESCE(a.Direccion, e.Direccion, p.Direccion, \'\') as Direccion
         FROM Usuarios u
         JOIN Rol r ON r.Id_Rol = u.Rol_Id_Rol
         LEFT JOIN Administrador a ON a.Usuarios_Id = u.Id AND u.Rol_Id_Rol = \'U1\'
         LEFT JOIN Empleado e      ON e.Usuarios_Id = u.Id AND u.Rol_Id_Rol = \'U2\'
         LEFT JOIN Proveedor p     ON p.Usuarios_Id = u.Id AND u.Rol_Id_Rol = \'U3\''
    );

    $adminId = DB::selectOne('SELECT Id FROM Usuarios WHERE Correo = ?', [session('usuario')])->Id;
    return view('admin.usuarios', ['usuarios' => $usuarios, 'adminId' => $adminId]);
}

    public function editUsuario($id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    $usuario = DB::selectOne(
        'SELECT Id, Id_tdoc, P_Nombre, S_Nombre, P_Apellido, S_Apellido, Correo, Telefono, Rol_Id_Rol
         FROM Usuarios WHERE Id = ?',
        [$id]
    );

    if (!$usuario)
        return redirect()->route('usuarios')->with('error', 'Usuario no encontrado');

    // ← AQUÍ va el bloqueo
    if ($usuario->Rol_Id_Rol === 'U4')
        return redirect()->route('usuarios')->with('error', 'No puedes editar clientes desde aquí');

    $nombreCompleto = trim(implode(' ', array_filter([
        $usuario->P_Nombre, $usuario->S_Nombre, $usuario->P_Apellido, $usuario->S_Apellido
    ])));

    return view('admin.form-editar-usuario', compact('usuario', 'nombreCompleto'));
}

public function updateUsuario(Request $request, $id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    // El admin no puede cambiar su propio rol
    $usuarioActual = DB::selectOne('SELECT Id FROM Usuarios WHERE Correo = ?', [session('usuario')]);
    if ($usuarioActual && $usuarioActual->Id == $id && $request->rol !== 'U1')
        return redirect()->back()->with('error', 'No puedes cambiar tu propio rol');

    try {
        $partes    = explode(" ", trim($request->nombre));
        $pnombre   = $partes[0] ?? '';
        $snombre   = $partes[1] ?? '';
        $papellido = $partes[2] ?? '';
        $sapellido = $partes[3] ?? '';

        if ($request->filled('contrasena')) {
            DB::update(
                'UPDATE Usuarios
                 SET Id_tdoc = ?, P_Nombre = ?, S_Nombre = ?, P_Apellido = ?, S_Apellido = ?,
                     Correo = ?, Telefono = ?, Contraseña = ?, Rol_Id_Rol = ?
                 WHERE Id = ?',
                [$request->tipo_documento, $pnombre, $snombre, $papellido, $sapellido,
                 $request->correo, $request->telefono, $request->contrasena, $request->rol, $id]
            );
        } else {
            DB::update(
                'UPDATE Usuarios
                 SET Id_tdoc = ?, P_Nombre = ?, S_Nombre = ?, P_Apellido = ?, S_Apellido = ?,
                     Correo = ?, Telefono = ?, Rol_Id_Rol = ?
                 WHERE Id = ?',
                [$request->tipo_documento, $pnombre, $snombre, $papellido, $sapellido,
                 $request->correo, $request->telefono, $request->rol, $id]
            );
        }

        return redirect()->route('usuarios')->with('success', 'Usuario actualizado');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function inactivarUsuario($id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    // El admin no puede inactivarse a sí mismo
    $usuarioActual = DB::selectOne('SELECT Id FROM Usuarios WHERE Correo = ?', [session('usuario')]);
    if ($usuarioActual && $usuarioActual->Id == $id)
        return redirect()->route('usuarios')->with('error', 'No puedes inactivarte a ti mismo');

    // ← AGREGAR ESTO: no se pueden inactivar clientes desde aquí
    $target = DB::selectOne('SELECT Rol_Id_Rol FROM Usuarios WHERE Id = ?', [$id]);
    if ($target && $target->Rol_Id_Rol === 'U4')
        return redirect()->route('usuarios')->with('error', 'No puedes inactivar clientes desde aquí');

    try {
        DB::update('UPDATE Usuarios SET Estado = 0 WHERE Id = ?', [$id]);
        return redirect()->route('usuarios')->with('success', 'Usuario inactivado');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function usuariosInactivos()
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    $usuarios = DB::select(
        'SELECT u.Id, u.Id_tdoc, u.P_Nombre, u.S_Nombre, u.P_Apellido, u.S_Apellido,
                u.Correo, u.Telefono, r.Rol
         FROM Usuarios u
         JOIN Rol r ON r.Id_Rol = u.Rol_Id_Rol
         WHERE u.Estado = 0'
    );

    return view('admin.usuarios-inactivos', ['usuarios' => $usuarios]);
}

public function reactivarUsuario($id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    try {
        DB::update('UPDATE Usuarios SET Estado = 1 WHERE Id = ?', [$id]);
        return redirect()->route('usuarios.inactivos')->with('success', 'Usuario reactivado');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function inventario()
    {
        if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2', 'U3']))
            return redirect()->route('login');

        $productos = DB::select(
    'SELECT p.Id, p.Nombre, p.Precio, p.Estado, p.Fecha_Registro,
            p.foto,                          -- ← agregar esto
            i.Stock, i.Cantidad_Minima, i.Cantidad_Maxima,
            t.T_Producto as Categoria
     FROM Producto p
     JOIN T_Producto t ON t.Id = p.T_Producto_Id
     JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
     JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario
     WHERE p.Estado = 1'
);

        return view('admin.inventario', ['productos' => $productos]);
    }

    public function citas()
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    $citas = DB::select(
        'SELECT c.Id, c.Fecha_Hora, c.Observaciones, c.Diseño, c.Estado, c.Tipo_Tatuaje
 FROM Citas c
 ORDER BY c.Fecha_Hora DESC'
    );

    return view('admin.citas', ['citas' => $citas]);
}

    public function ventas()
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    $productos = DB::select(
        'SELECT p.Id, p.Nombre, p.Precio, p.foto, p.Estado,
                i.Stock, i.Cantidad_Minima, i.Cantidad_Maxima,
                t.T_Producto as Categoria
         FROM Producto p
         JOIN T_Producto t ON t.Id = p.T_Producto_Id
         JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
         JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario
         WHERE p.Estado = 1'
    );

    return view('admin.ventas', ['productos' => $productos]);
}

    // ── Formularios ──────────────────────────────────────────

    public function formUsuario()
    {
        if (!session('usuario') || session('rol') != 'U1')
            return redirect()->route('login');

        return view('admin.form-usuario');
    }

    public function storeUsuario(Request $request)
    {
        if (!session('usuario') || session('rol') != 'U1')
            return redirect()->route('login');

        $partes    = explode(" ", trim($request->nombre));
        $pnombre   = $partes[0] ?? '';
        $snombre   = $partes[1] ?? '';
        $papellido = $partes[2] ?? '';
        $sapellido = $partes[3] ?? '';

        try {
            DB::insert(
                'INSERT INTO Usuarios (Id, Id_tdoc, P_Nombre, S_Nombre, P_Apellido, S_Apellido, Correo, Telefono, Contraseña, Rol_Id_Rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [$request->numero_documento, $request->tipo_documento, $pnombre, $snombre, $papellido, $sapellido, $request->correo, $request->telefono, $request->contrasena, $request->rol]
            );
            return redirect()->route('usuarios')->with('success', 'Usuario registrado');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: el usuario ya existe o datos inválidos');
        }
    }

    public function formInventario()
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2', 'U3']))
        return redirect()->route('login');

    // Traer productos que NO están en inventario todavía
    $productos = DB::select(
        'SELECT Id, Nombre FROM Producto 
         WHERE Estado = 1 
         AND Id NOT IN (SELECT Producto_Id FROM Producto_Inventario)'
    );

    return view('admin.form-inventario', compact('productos'));
}

    public function storeInventario(Request $request)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2', 'U3']))
        return redirect()->route('login');

    try {
        $codigoExiste = DB::selectOne('SELECT Id FROM Producto WHERE Id = ?', [$request->codigo]);
        if ($codigoExiste)
            return redirect()->back()->with('error', 'Ya existe un producto con ese código');

        $nombreExiste = DB::selectOne('SELECT Id FROM Producto WHERE Nombre = ?', [$request->nombre]);
        if ($nombreExiste)
            return redirect()->back()->with('error', 'Ya existe un producto con ese nombre');

        if ($request->cantidad < 1 || $request->cantidad > 100)
            return redirect()->back()->with('error', 'La cantidad debe estar entre 1 y 100');

        $categoria = DB::selectOne('SELECT Id FROM T_Producto WHERE T_Producto = ? LIMIT 1', [$request->categoria]);
        if (!$categoria)
            return redirect()->back()->with('error', 'Categoría no válida');

        $usuarioId = DB::selectOne('SELECT Id FROM Usuarios WHERE Correo = ?', [session('usuario')]);
        $admin     = DB::selectOne('SELECT Usuarios_Id FROM Administrador WHERE Usuarios_Id = ?', [$usuarioId->Id]);
        $empleado  = DB::selectOne('SELECT Usuarios_Id FROM Empleado LIMIT 1');
        $cliente   = DB::selectOne('SELECT Usuarios_Id FROM Clientes LIMIT 1');

        // Manejar foto
        $foto = null;
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $archivo = $request->file('foto');
            $nombre  = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/inventario'), $nombre);
            $foto = 'uploads/inventario/' . $nombre;
        }

        DB::insert(
            'INSERT INTO Producto (Id, Nombre, Descripccion, Precio, Estado, Fecha_Registro, Foto, T_Producto_Id, Administrador_Usuarios_Id, Clientes_Usuarios_Id, Empleado_Usuarios_Id)
             VALUES (?, ?, ?, ?, 1, NOW(), ?, ?, ?, ?, ?)',
            [
                $request->codigo,
                $request->nombre,
                $request->descripcion,
                $request->precio,
                $foto,
                $categoria->Id,
                $admin->Usuarios_Id,
                $cliente->Usuarios_Id,
                $empleado->Usuarios_Id,
            ]
        );

        $maxInventarioId = DB::selectOne('SELECT COALESCE(MAX(Id_Inventario), 0) + 1 AS nextId FROM Inventario');

        DB::insert(
            'INSERT INTO Inventario (Id_Inventario, Stock, Cantidad_Minima, Cantidad_Maxima) VALUES (?, ?, 1, 100)',
            [$maxInventarioId->nextId, $request->cantidad]
        );

        DB::insert(
            'INSERT INTO Producto_Inventario (Producto_Id, Inventario_Id_Inventario) VALUES (?, ?)',
            [$request->codigo, $maxInventarioId->nextId]
        );

        return redirect()->route('inventario')->with('success', 'Producto registrado correctamente');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function formCita()
    {
        if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
            return redirect()->route('login');

        return view('admin.form-cita');
    }

    public function storeCita(Request $request)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    try {
        $fechaHora = new \DateTime($request->fecha_hora);
$hora = (int) $fechaHora->format('H');
$hoy  = new \DateTime('today');

if ($fechaHora < $hoy)
    return redirect()->back()->with('error', 'No se puede agendar en una fecha pasada.');

if ($hora < 9 || $hora >= 20)
    return redirect()->back()->with('error', 'Las citas deben ser entre las 9:00 AM y las 8:00 PM.');
        $maxId = DB::selectOne('SELECT COALESCE(MAX(Id), 0) + 1 as nextId FROM Citas');

        $diseño = null;
        if ($request->hasFile('diseño') && $request->file('diseño')->isValid()) {
            $archivo = $request->file('diseño');
            $nombre  = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/citas'), $nombre);
            $diseño  = 'uploads/citas/' . $nombre;
        }

        DB::insert(
    'INSERT INTO Citas (Id, Fecha_Hora, Estado, Observaciones, Diseño, Tipo_Tatuaje) VALUES (?, ?, 1, ?, ?, ?)',
    [$maxId->nextId, $request->fecha_hora, $request->observaciones, $diseño, $request->tipo_tatuaje]
);

        return redirect()->route('citas')->with('success', 'Cita registrada');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function formVenta()
    {
        if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
            return redirect()->route('login');

        $empleados = DB::select('SELECT u.Id, u.P_Nombre, u.P_Apellido FROM Usuarios u WHERE u.Rol_Id_Rol = "U2"');
        $clientes  = DB::select('SELECT u.Id, u.P_Nombre, u.P_Apellido FROM Usuarios u WHERE u.Rol_Id_Rol = "U4"');
        return view('admin.form-venta', compact('empleados', 'clientes'));
    }

    public function storeVenta(Request $request)
    {
        // Permitir admin, empleado y cliente
        if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2', 'U4']))
            return response()->json(['ok' => false, 'error' => 'Sin autorización'], 403);

        try {
            $datos     = $request->json()->all();
            $productos = $datos['productos'] ?? [];

            // Si viene cliente_id en el JSON (admin/empleado) lo usa
            // Si no viene (cliente comprando), lo toma de la sesión
            $clienteId = $datos['cliente_id'] ?? null;
            if (!$clienteId) {
                $usuario   = DB::selectOne('SELECT Id FROM Usuarios WHERE Correo = ?', [session('usuario')]);
                $clienteId = $usuario->Id ?? null;
            }

            if (empty($productos))
                return response()->json(['ok' => false, 'error' => 'El carrito está vacío']);

            if (!$clienteId)
                return response()->json(['ok' => false, 'error' => 'No se pudo identificar el cliente']);

            // Si el usuario logueado es empleado, usarlo; si no, tomar el primero disponible
            $empleado = DB::selectOne(
                'SELECT Usuarios_Id FROM Empleado WHERE Usuarios_Id IN
                 (SELECT Id FROM Usuarios WHERE Correo = ?)',
                [session('usuario')]
            );

            if (!$empleado)
                $empleado = DB::selectOne('SELECT Usuarios_Id FROM Empleado LIMIT 1');

            $observacion = trim(
                'Pago: ' . ($datos['metodo_pago'] ?? 'Efectivo') .
                (($datos['observacion'] ?? '') ? ' | ' . $datos['observacion'] : '')
            );

            DB::insert(
                'INSERT INTO Facturación (Fecha_Hora, Observacion, Estado, Empleado_Usuarios_Id, Clientes_Usuarios_Id)
                 VALUES (NOW(), ?, 1, ?, ?)',
                [$observacion, $empleado->Usuarios_Id, $clienteId]
            );

            $facturaId = DB::getPdo()->lastInsertId();

            foreach ($productos as $prod) {
                $inv = DB::selectOne(
                    'SELECT i.Id_Inventario, i.Stock
                     FROM Inventario i
                     JOIN Producto_Inventario pi ON pi.Inventario_Id_Inventario = i.Id_Inventario
                     WHERE pi.Producto_Id = ?',
                    [$prod['id']]
                );

                if (!$inv || $inv->Stock < $prod['qty'])
                    return response()->json(['ok' => false, 'error' => 'Stock insuficiente para uno de los productos']);

                DB::insert(
                    'INSERT INTO Producto_Facturación (Id_Producto, Id_Facturación) VALUES (?, ?)',
                    [$prod['id'], $facturaId]
                );

                DB::update(
                    'UPDATE Inventario SET Stock = Stock - ? WHERE Id_Inventario = ?',
                    [$prod['qty'], $inv->Id_Inventario]
                );
            }

            return response()->json(['ok' => true, 'factura_id' => $facturaId]);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }
    }


    // ── Empleado ─────────────────────────────────────────────

    public function citasEmpleado()
    {
        if (!session('usuario') || session('rol') != 'U2')
            return redirect()->route('login');

        $citas = DB::select(
            'SELECT Id, Fecha_Hora, Observaciones,
                    CASE WHEN Estado = 1 THEN "Activa" ELSE "Cancelada" END as Estado
             FROM Citas ORDER BY Fecha_Hora DESC'
        );
        return view('empleado.citas', ['citas' => $citas]);
    }

    public function inventarioEmpleado()
{
    if (!session('usuario') || session('rol') != 'U2')
        return redirect()->route('login');

    $productos = DB::select(
        'SELECT p.Id, p.Nombre, p.Precio, p.Estado, p.foto,
                i.Stock, i.Cantidad_Minima, i.Cantidad_Maxima,
                t.T_Producto as Categoria
         FROM Producto p
         JOIN T_Producto t ON t.Id = p.T_Producto_Id
         JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
         JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario'
    );

    return view('empleado.inventario', ['productos' => $productos]);
}

    public function ventasEmpleado()
{
    if (!session('usuario') || session('rol') != 'U2')
        return redirect()->route('login');

    $productos = DB::select(
        'SELECT p.Id, p.Nombre, p.Precio, p.foto, p.Estado,
                i.Stock, i.Cantidad_Minima, i.Cantidad_Maxima,
                t.T_Producto as Categoria
         FROM Producto p
         JOIN T_Producto t ON t.Id = p.T_Producto_Id
         JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
         JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario
         WHERE p.Estado = 1'
    );

    return view('empleado.ventas', compact('productos'));
}
    // ── Proveedor ────────────────────────────────────────────

    public function inventarioProveedor()
{
    if (!session('usuario') || session('rol') != 'U3')
        return redirect()->route('login');

    $productos = DB::select(
        'SELECT p.Id, p.Nombre, p.Precio, p.Estado, p.foto,
                i.Stock, i.Cantidad_Minima, i.Cantidad_Maxima,
                t.T_Producto as Categoria
         FROM Producto p
         JOIN T_Producto t ON t.Id = p.T_Producto_Id
         JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
         JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario'
    );

    return view('proveedor.inventario', ['productos' => $productos]);
}

    // ── Cliente ──────────────────────────────────────────────

    public function citasCliente()
{
    if (!session('usuario') || session('rol') != 'U4')
        return redirect()->route('login');

    $usuario = DB::selectOne(
        'SELECT Id FROM Usuarios WHERE Correo = ?',
        [session('usuario')]
    );

    $citas = DB::select(
        'SELECT c.Id, c.Fecha_Hora, c.Observaciones,
                CASE WHEN c.Estado = 1 THEN "Activa" ELSE "Cancelada" END as Estado
         FROM Citas c
         JOIN Facturación_Citas fc ON fc.Citas_Id = c.Id
         JOIN Facturación f        ON f.Id = fc.Facturacion_Id
         WHERE f.Clientes_Usuarios_Id = ?
         ORDER BY c.Fecha_Hora DESC',
        [$usuario->Id]
    );

    return view('cliente.citas', ['citas' => $citas]);
}

    public function comprasCliente()
{
    if (!session('usuario') || session('rol') != 'U4')
        return redirect()->route('login');

    $clienteId = DB::selectOne(
        'SELECT Id FROM Usuarios WHERE Correo = ?',
        [session('usuario')]
    );

    $compras = DB::select(
        'SELECT f.Id, f.Fecha_Hora, f.Observacion,
                CASE WHEN f.Estado = 1 THEN "Completada" ELSE "Pendiente" END as Estado
         FROM Facturación f
         WHERE f.Clientes_Usuarios_Id = ?
         ORDER BY f.Fecha_Hora DESC',
        [$clienteId->Id]
    );

    // Por cada factura, traemos los productos que incluyó
    foreach ($compras as $compra) {
        $compra->productos = DB::select(
            'SELECT p.Nombre, p.Precio, p.foto
             FROM Producto_Facturación pf
             JOIN Producto p ON p.Id = pf.Id_Producto
             WHERE pf.Id_Facturación = ?',
            [$compra->Id]
        );

        $compra->total = array_sum(array_column($compra->productos, 'Precio'));
    }

    return view('cliente.compras', ['compras' => $compras]);
}
    public function editInventario($id)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U3']))
        return redirect()->route('login');

    $producto = DB::selectOne(
        'SELECT
            p.Id,
            p.Nombre,
            p.Descripccion,
            p.Precio,
            p.Estado,
            p.Foto,
            p.T_Producto_Id,
            i.Id_Inventario,
            i.Stock,
            i.Cantidad_Minima,
            i.Cantidad_Maxima
         FROM Producto p
         JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
         JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario
         WHERE p.Id = ?',
        [$id]
    );

    $categorias = DB::select('SELECT * FROM T_Producto');

    return view('admin.form-editar-inventario', compact('producto', 'categorias'));
}

public function updateInventario(Request $request, $id)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U3']))
        return redirect()->route('login');

    try {
        // Manejar foto: si sube una nueva la reemplaza, si no conserva la anterior
        $producto = DB::selectOne('SELECT Foto FROM Producto WHERE Id = ?', [$id]);
        $foto = $producto->Foto;

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $archivo = $request->file('foto');
            $nombre  = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/inventario'), $nombre);
            $foto = 'uploads/inventario/' . $nombre;
        }

        DB::update(
            'UPDATE Producto
             SET Nombre = ?,
                 Descripccion = ?,
                 Precio = ?,
                 Foto = ?,
                 T_Producto_Id = ?,
                 Estado = ?
             WHERE Id = ?',
            [
                $request->nombre,
                $request->descripcion,
                $request->precio,
                $foto,
                $request->categoria,
                1,
                $id
            ]
        );

        DB::update(
            'UPDATE Inventario
             SET Stock = ?,
                 Cantidad_Minima = ?,
                 Cantidad_Maxima = ?
             WHERE Id_Inventario = ?',
            [
                $request->stock,
                $request->cantidad_minima,
                $request->cantidad_maxima,
                $request->inventario_id
            ]
        );

        return redirect()->route('inventario')->with('success', 'Producto actualizado');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function destroyInventario($id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    try {
        DB::update(
            'UPDATE Producto SET Estado = 0 WHERE Id = ?',
            [$id]
        );

        return redirect()->route('inventario')->with('success', 'Producto desactivado');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function inactivos()
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    $productos = DB::select(
        'SELECT p.Id, p.Nombre, p.Precio, p.Estado,
                i.Stock, i.Cantidad_Minima, i.Cantidad_Maxima,
                t.T_Producto as Categoria
         FROM Producto p
         JOIN T_Producto t ON t.Id = p.T_Producto_Id
         JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
         JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario
         WHERE p.Estado = 0'
    );

    return view('admin.inactivos', ['productos' => $productos]);
}

public function reactivar($id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    try {
        DB::update('UPDATE Producto SET Estado = 1 WHERE Id = ?', [$id]);
        return redirect()->route('inventario.inactivos')->with('success', 'Producto reactivado');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function editCita($id)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    $cita = DB::selectOne('SELECT * FROM Citas WHERE Id = ?', [$id]);
    return view('admin.form-editar-cita', compact('cita'));
}

public function updateCita(Request $request, $id)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    try {
        $fechaHora = new \DateTime($request->fecha_hora);
$hora = (int) $fechaHora->format('H');
$hoy  = new \DateTime('today');

if ($fechaHora < $hoy)
    return redirect()->back()->with('error', 'No se puede agendar en una fecha pasada.');

if ($hora < 9 || $hora >= 20)
    return redirect()->back()->with('error', 'Las citas deben ser entre las 9:00 AM y las 8:00 PM.');
        $cita   = DB::selectOne('SELECT * FROM Citas WHERE Id = ?', [$id]);
        $diseño = $cita->{'Diseño'};

        if ($request->hasFile('diseño') && $request->file('diseño')->isValid()) {
            $archivo = $request->file('diseño');
            $nombre  = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/citas'), $nombre);
            $diseño  = 'uploads/citas/' . $nombre;
        }

        DB::update(
    'UPDATE Citas SET Fecha_Hora = ?, Observaciones = ?, Diseño = ?, Tipo_Tatuaje = ? WHERE Id = ?',
    [$request->fecha_hora, $request->observaciones, $diseño, $request->tipo_tatuaje, $id]
);

        return redirect()->route('citas')->with('success', 'Cita actualizada');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function inactivarCita($id)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    try {
        DB::update('UPDATE Citas SET Estado = 0 WHERE Id = ?', [$id]);
        return redirect()->route('citas')->with('success', 'Cita inactivada');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function citasInactivas()
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    $citas = DB::select(
        'SELECT * FROM Citas WHERE Estado = 0 ORDER BY Fecha_Hora DESC'
    );
    return view('admin.citas-inactivas', compact('citas'));
}

public function reactivarCita($id)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    try {
        DB::update('UPDATE Citas SET Estado = 1 WHERE Id = ?', [$id]);
        return redirect()->route('citas.inactivas')->with('success', 'Cita reactivada');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function buscarCliente(Request $request)
{
    $q = '%' . $request->query('q', '') . '%';
 
    $clientes = DB::select(
        'SELECT u.Id as id,
                CONCAT(u.P_Nombre, " ", u.P_Apellido) as nombre,
                u.Telefono as telefono,
                c.Puntos as puntos
         FROM Usuarios u
         JOIN Clientes c ON c.Usuarios_Id = u.Id
         WHERE u.Rol_Id_Rol = "U4"
           AND (CONCAT(u.P_Nombre, " ", u.P_Apellido) LIKE ?
                OR u.Telefono LIKE ?)
         LIMIT 10',
        [$q, $q]
    );
 
    return response()->json($clientes);
}

public function storeCliente(Request $request)
{
    try {
        $datos = $request->json()->all();
 
        $existe = DB::selectOne('SELECT Id FROM Usuarios WHERE Id = ?', [$datos['id']]);
        if ($existe)
            return response()->json(['ok' => false, 'error' => 'Ya existe un usuario con ese documento']);
 
        DB::insert(
            'INSERT INTO Usuarios (Id, Id_tdoc, P_Nombre, S_Nombre, P_Apellido, S_Apellido, Correo, Telefono, Contraseña, Rol_Id_Rol)
             VALUES (?, "CC", ?, ?, ?, ?, ?, ?, ?, "U4")',
            [
                $datos['id'],
                $datos['p_nombre'],
                $datos['s_nombre'] ?? '',
                $datos['p_apellido'],
                $datos['s_apellido'] ?? '',
                $datos['correo'],
                $datos['telefono'],
                $datos['contrasena'],
            ]
        );
 
        DB::insert(
            'INSERT INTO Clientes (Puntos, fecha_nacimiento, Usuarios_Id, Direccion) VALUES (0, "2000-01-01", ?, ?)',
            [$datos['id'], $datos['direccion'] ?? '']
        );
 
        return response()->json(['ok' => true, 'id' => $datos['id']]);
 
    } catch (\Exception $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()]);
    }
}

// ── Gestión de ventas ─────────────────────────────────────

public function gestionVentas()
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    $ventas = DB::select(
        'SELECT f.Id, f.Fecha_Hora, f.Observacion,
                CASE WHEN f.Estado = 1 THEN "Completada" ELSE "Pendiente" END as Estado,
                f.Empleado_Usuarios_Id, f.Clientes_Usuarios_Id
         FROM Facturación f
         WHERE f.Estado = 1
         ORDER BY f.Fecha_Hora DESC'
    );

    $vista = session('rol') == 'U1' ? 'admin.gestion-ventas' : 'empleado.gestion-ventas';
    return view($vista, compact('ventas'));
}

public function gestionVentasInactivas()
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    $ventas = DB::select(
        'SELECT f.Id, f.Fecha_Hora, f.Observacion,
                CASE WHEN f.Estado = 1 THEN "Completada" ELSE "Pendiente" END as Estado,
                f.Empleado_Usuarios_Id, f.Clientes_Usuarios_Id
         FROM Facturación f
         WHERE f.Estado = 0
         ORDER BY f.Fecha_Hora DESC'
    );

    return view('admin.gestion-ventas-inactivas', compact('ventas'));
}

public function editVenta($id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    $venta = DB::selectOne('SELECT * FROM Facturación WHERE Id = ?', [$id]);
    $empleados = DB::select('SELECT u.Id, u.P_Nombre, u.P_Apellido FROM Usuarios u WHERE u.Rol_Id_Rol = "U2"');
    $clientes  = DB::select('SELECT u.Id, u.P_Nombre, u.P_Apellido FROM Usuarios u WHERE u.Rol_Id_Rol = "U4"');

    return view('admin.form-editar-venta', compact('venta', 'empleados', 'clientes'));
}

public function updateVenta(Request $request, $id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    try {
        DB::update(
            'UPDATE Facturación SET Observacion = ?, Empleado_Usuarios_Id = ?, Clientes_Usuarios_Id = ? WHERE Id = ?',
            [$request->observacion, $request->empleado_id, $request->cliente_id, $id]
        );

        return redirect()->route('gestion.ventas')->with('success', 'Venta actualizada');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function inactivarVenta($id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    try {
        DB::update('UPDATE Facturación SET Estado = 0 WHERE Id = ?', [$id]);
        return redirect()->route('gestion.ventas')->with('success', 'Venta inactivada');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function reactivarVenta($id)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    try {
        DB::update('UPDATE Facturación SET Estado = 1 WHERE Id = ?', [$id]);
        return redirect()->route('gestion.ventas.inactivas')->with('success', 'Venta reactivada');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function formVentaTatuaje()
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    $empleados = DB::select('SELECT u.Id, u.P_Nombre, u.P_Apellido FROM Usuarios u WHERE u.Rol_Id_Rol = "U2"');
    $clientes  = DB::select('SELECT u.Id, u.P_Nombre, u.P_Apellido FROM Usuarios u WHERE u.Rol_Id_Rol = "U4"');

    $vista = session('rol') == 'U1' ? 'admin.form-venta-tatuaje' : 'empleado.form-venta-tatuaje';
    return view($vista, compact('empleados', 'clientes'));
}

public function storeVentaTatuaje(Request $request)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    try {
        DB::insert(
            'INSERT INTO Facturación (Fecha_Hora, Observacion, Estado, Empleado_Usuarios_Id, Clientes_Usuarios_Id)
             VALUES (NOW(), ?, 1, ?, ?)',
            [$request->observacion, $request->empleado_id, $request->cliente_id]
        );

        return redirect()->route('gestion.ventas')->with('success', 'Venta de tatuaje registrada correctamente');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function clienteIndex()
{
    if (!session('usuario') || session('rol') != 'U4')
        return redirect()->route('login');

    $productos = DB::select(
        'SELECT p.Id, p.Nombre, p.Precio, p.foto, p.Estado,
                i.Stock, i.Cantidad_Minima, i.Cantidad_Maxima,
                t.T_Producto as Categoria
         FROM Producto p
         JOIN T_Producto t ON t.Id = p.T_Producto_Id
         JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
         JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario
         WHERE p.Estado = 1'
    );

    return view('cliente.principal', compact('productos'));
}

public function storeCitaCliente(Request $request)
{
    if (!session('usuario') || session('rol') != 'U4')
        return redirect()->route('login');

    try {
        $maxId = DB::selectOne('SELECT COALESCE(MAX(Id), 0) + 1 as nextId FROM Citas');

        $diseño = null;
        if ($request->hasFile('diseño') && $request->file('diseño')->isValid()) {
            $archivo = $request->file('diseño');
            $nombre  = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('uploads/citas'), $nombre);
            $diseño  = 'uploads/citas/' . $nombre;
        }

        DB::insert(
            'INSERT INTO Citas (Id, Fecha_Hora, Estado, Observaciones, Diseño) VALUES (?, ?, 1, ?, ?)',
            [$maxId->nextId, $request->fecha_hora, $request->observaciones, $diseño]
        );

        // Vincular la cita a una facturación del cliente
        $usuario = DB::selectOne('SELECT Id FROM Usuarios WHERE Correo = ?', [session('usuario')]);
        $empleado = DB::selectOne('SELECT Usuarios_Id FROM Empleado LIMIT 1');

        DB::insert(
            'INSERT INTO Facturación (Fecha_Hora, Observacion, Estado, Empleado_Usuarios_Id, Clientes_Usuarios_Id)
             VALUES (NOW(), ?, 1, ?, ?)',
            ['Cita agendada por cliente', $empleado->Usuarios_Id, $usuario->Id]
        );

        $facturaId = DB::getPdo()->lastInsertId();

        DB::insert(
            'INSERT INTO Facturación_Citas (Facturacion_Id, Citas_Id) VALUES (?, ?)',
            [$facturaId, $maxId->nextId]
        );

        return redirect()->route('citas.cliente')->with('success', 'Cita registrada correctamente');

    } catch (\Exception $e) {
        return redirect()->route('citas.cliente')->with('error', 'Error: ' . $e->getMessage());
    }
}

public function inicio()
{
    $productos = DB::select(
        'SELECT p.Id, p.Nombre, p.Precio, p.foto,
                i.Stock, i.Cantidad_Minima,
                t.T_Producto as Categoria
         FROM Producto p
         JOIN T_Producto t ON t.Id = p.T_Producto_Id
         JOIN Producto_Inventario pi ON pi.Producto_Id = p.Id
         JOIN Inventario i ON i.Id_Inventario = pi.Inventario_Id_Inventario
         WHERE p.Estado = 1'
    );

    return view('index.index', compact('productos'));
}

public function completarCita($id)
{
    if (!session('usuario') || !in_array(session('rol'), ['U1', 'U2']))
        return redirect()->route('login');

    try {
        DB::update('UPDATE Citas SET Estado = 2 WHERE Id = ?', [$id]);
        return redirect()->route('citas')->with('success', 'Cita marcada como completada');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}


public function reporteVentas(Request $request)
{
    if (!session('usuario') || session('rol') != 'U1')
        return redirect()->route('login');

    $anioSel = $request->query('anio', date('Y'));
    $mesSel  = $request->query('mes', 0);

    // ── Años disponibles para el filtro ──
    $anios = DB::select('SELECT DISTINCT YEAR(Fecha_Hora) as anio FROM Facturación ORDER BY anio DESC');
    $anios = array_column($anios, 'anio');
    if (empty($anios)) $anios = [date('Y')];

    // ── Ventas filtradas con nombres de empleado y cliente ──
    $sql = 'SELECT f.Id, f.Fecha_Hora, f.Observacion,
                   CASE WHEN f.Estado = 1 THEN "Completada" ELSE "Pendiente" END as Estado,
                   f.Empleado_Usuarios_Id, f.Clientes_Usuarios_Id,
                   CONCAT(ue.P_Nombre, " ", ue.P_Apellido) as NombreEmpleado,
                   CONCAT(uc.P_Nombre, " ", uc.P_Apellido) as NombreCliente
            FROM Facturación f
            LEFT JOIN Usuarios ue ON ue.Id = f.Empleado_Usuarios_Id
            LEFT JOIN Usuarios uc ON uc.Id = f.Clientes_Usuarios_Id
            WHERE YEAR(f.Fecha_Hora) = ?';

    $params = [$anioSel];

    if ($mesSel > 0) {
        $sql .= ' AND MONTH(f.Fecha_Hora) = ?';
        $params[] = $mesSel;
    }

    $sql .= ' ORDER BY f.Fecha_Hora DESC';
    $ventas = DB::select($sql, $params);

    // ── Productos por cada venta ──
    foreach ($ventas as $v) {
        $v->productos = DB::select(
            'SELECT p.Nombre FROM Producto_Facturación pf
             JOIN Producto p ON p.Id = pf.Id_Producto
             WHERE pf.Id_Facturación = ?',
            [$v->Id]
        );
    }

    // ── Estadísticas resumen ──
    $totalVentas   = count($ventas);
    $totalProductos = array_sum(array_map(fn($v) => count($v->productos), $ventas));
    $totalClientes  = count(array_unique(array_column($ventas, 'Clientes_Usuarios_Id')));

    // ── Datos para la gráfica (todos los meses del año seleccionado) ──
    $nombresMeses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    $conteoMeses  = array_fill(0, 12, 0);

    $ventasPorMes = DB::select(
        'SELECT MONTH(Fecha_Hora) as mes, COUNT(*) as total
         FROM Facturación
         WHERE YEAR(Fecha_Hora) = ?
         GROUP BY MONTH(Fecha_Hora)',
        [$anioSel]
    );

    foreach ($ventasPorMes as $vm) {
        $conteoMeses[$vm->mes - 1] = (int) $vm->total;
    }

    $graficaMeses   = $nombresMeses;
    $graficaTotales = $conteoMeses;

    return view('admin.reporte-ventas', compact(
        'ventas', 'anios', 'anioSel', 'mesSel',
        'totalVentas', 'totalProductos', 'totalClientes',
        'graficaMeses', 'graficaTotales'
    ));
}
}