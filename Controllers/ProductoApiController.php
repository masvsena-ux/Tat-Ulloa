<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoApiController extends Controller
{
    // Devuelve las categorías para el Spinner de Android
    public function categorias()
    {
        $categorias = DB::select('SELECT Id, T_Producto FROM T_Producto');
        return response()->json($categorias);
    }

    // Registra el producto desde Android
    public function store(Request $request)
    {
        try {
            // Validar que vengan todos los datos necesarios
            if (!$request->filled(['id', 'nombre', 'precio', 'categoria_id', 'cantidad', 'descripcion'])) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Faltan datos obligatorios'
                ], 400);
            }

            $codigo      = $request->input('id');
            $nombre      = $request->input('nombre');
            $precio      = $request->input('precio');
            $categoriaId = $request->input('categoria_id');
            $cantidad    = (int) $request->input('cantidad');
            $descripcion = $request->input('descripcion');

            // Validar el código: 13 números (código de barras) o hasta 50 caracteres alfanuméricos
            $esNumerico13          = (strlen($codigo) === 13 && ctype_digit($codigo));
            $esAlfanumericoHasta50 = (strlen($codigo) >= 1 && strlen($codigo) <= 50 && ctype_alnum($codigo));

            if (!$esNumerico13 && !$esAlfanumericoHasta50) {
                return response()->json([
                    'success' => false,
                    'error'   => 'El código debe tener 13 números o máximo 50 caracteres alfanuméricos'
                ], 400);
            }

            // Validar cantidad (stock inicial) entre 1 y 100
            if ($cantidad < 1 || $cantidad > 100) {
                return response()->json([
                    'success' => false,
                    'error'   => 'La cantidad debe estar entre 1 y 100'
                ], 400);
            }

            // Verificar que no exista ya un producto con ese código (Id)
            $existe = DB::selectOne(
                'SELECT Id FROM Producto WHERE Id = ?',
                [$codigo]
            );

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Ya existe un producto con ese código'
                ], 400);
            }

            // ── Manejar foto (opcional) ──────────────────────────────
            $foto = null;
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $archivo = $request->file('foto');
                $nombre_archivo = time() . '_' . preg_replace('/\s+/', '_', $archivo->getClientOriginalName());
                $archivo->move(public_path('uploads/inventario'), $nombre_archivo);
                $foto = 'uploads/inventario/' . $nombre_archivo;
            }

            // Calcular el siguiente Id_Inventario disponible
            $maxInventarioId = DB::selectOne(
                'SELECT COALESCE(MAX(Id_Inventario), 0) + 1 as nextId FROM Inventario'
            );

            // Obtener admin, empleado y cliente de la BD
            $admin    = DB::selectOne('SELECT Usuarios_Id FROM Administrador LIMIT 1');
            $empleado = DB::selectOne('SELECT Usuarios_Id FROM Empleado LIMIT 1');
            $cliente  = DB::selectOne('SELECT Usuarios_Id FROM Clientes LIMIT 1');

            if (!$admin || !$empleado || !$cliente) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No hay registros base de Administrador, Empleado o Cliente'
                ], 500);
            }

            // Insertar en Producto (foto puede ser null si no enviaron imagen)
            DB::insert(
                'INSERT INTO Producto (Id, Nombre, Descripccion, Precio, Estado, Fecha_Registro, Foto, T_Producto_Id, Administrador_Usuarios_Id, Clientes_Usuarios_Id, Empleado_Usuarios_Id)
                 VALUES (?, ?, ?, ?, 1, NOW(), ?, ?, ?, ?, ?)',
                [
                    $codigo,
                    $nombre,
                    $descripcion,
                    $precio,
                    $foto,
                    $categoriaId,
                    $admin->Usuarios_Id,
                    $cliente->Usuarios_Id,
                    $empleado->Usuarios_Id,
                ]
            );

            // Insertar en Inventario (Stock = cantidad, límites fijos 1 - 100)
            DB::insert(
                'INSERT INTO Inventario (Id_Inventario, Stock, Cantidad_Minima, Cantidad_Maxima)
                 VALUES (?, ?, 1, 100)',
                [$maxInventarioId->nextId, $cantidad]
            );

            // Relacionar Producto con Inventario
            DB::insert(
                'INSERT INTO Producto_Inventario (Producto_Id, Inventario_Id_Inventario)
                 VALUES (?, ?)',
                [$codigo, $maxInventarioId->nextId]
            );

            return response()->json([
                'success' => true,
                'message' => 'Producto registrado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}