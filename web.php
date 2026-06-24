<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProductoController;

// ── Públicas (sin protección) ───────────────────────────────
Route::get('/', [PanelController::class, 'inicio'])->name('inicio');
Route::get('/login',         [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',        [AuthController::class, 'login'])->name('login.post');
Route::get('/logout',        [AuthController::class, 'logout'])->name('logout');
Route::get('/recuperar',         [AuthController::class, 'showRecuperar'])->name('recuperar');
Route::post('/recuperar',        [AuthController::class, 'recuperar'])->name('recuperar.post');
Route::get('/nueva-contrasena',  [AuthController::class, 'showNuevaContrasena'])->name('nueva-contrasena');
Route::post('/nueva-contrasena', [AuthController::class, 'nuevaContrasena'])->name('nueva-contrasena.post');
Route::get('/registro',      [AuthController::class, 'showRegistro'])->name('registro');
Route::post('/registro',     [AuthController::class, 'registro'])->name('registro.post');

// ── Protegidas (requieren sesión activa) ────────────────────
Route::middleware('sesion')->group(function () {

    // Páginas principales por rol
    // El middleware ya verifica que hay sesión, aquí además verificamos el rol correcto
    Route::get('/admin', function() {
        if (session('rol') != 'U1') return redirect()->route('login');
        return view('admin.principal');
    })->name('admin');

    Route::get('/empleado', function() {
        if (session('rol') != 'U2') return redirect()->route('login');
        return view('empleado.principal');
    })->name('empleado');

    Route::get('/proveedor', function() {
        if (session('rol') != 'U3') return redirect()->route('login');
        return view('proveedor.principal');
    })->name('proveedor');

    // Paneles admin
    Route::get('/usuarios',   [PanelController::class, 'usuarios'])->name('usuarios');
    Route::get('/inventario', [PanelController::class, 'inventario'])->name('inventario');
    Route::get('/citas',      [PanelController::class, 'citas'])->name('citas');
    Route::get('/ventas',     [PanelController::class, 'ventas'])->name('ventas');

    // Formularios
    Route::get('/usuarios/form',    [PanelController::class, 'formUsuario'])->name('usuarios.form');
    Route::post('/usuarios/form',   [PanelController::class, 'storeUsuario'])->name('usuarios.store');
    Route::get('/inventario/form',  [PanelController::class, 'formInventario'])->name('inventario.form');
    Route::post('/inventario/form', [PanelController::class, 'storeInventario'])->name('inventario.store');
    Route::get('/citas/form',       [PanelController::class, 'formCita'])->name('citas.form');
    Route::post('/citas/form',      [PanelController::class, 'storeCita'])->name('citas.store');
    Route::get('/ventas/form',      [PanelController::class, 'formVenta'])->name('ventas.form');
    Route::post('/ventas/form',     [PanelController::class, 'storeVenta'])->name('ventas.store');

    // Empleado
    Route::get('/inventario/empleado',  [PanelController::class, 'inventarioEmpleado'])->name('inventario.empleado');
    Route::get('/citas/empleado',       [PanelController::class, 'citasEmpleado'])->name('citas.empleado');
    Route::get('/ventas/empleado', [PanelController::class, 'ventasEmpleado'])->name('ventas.empleado');

    // Proveedor
    Route::get('/inventario/proveedor', [PanelController::class, 'inventarioProveedor'])->name('inventario.proveedor');

    // Cliente
    Route::get('/citas/cliente',   [PanelController::class, 'citasCliente'])->name('citas.cliente');
Route::get('/compras/cliente', [PanelController::class, 'comprasCliente'])->name('compras.cliente');
    Route::get('/cliente', [PanelController::class, 'clienteIndex'])->name('cliente');

    // Inventario acciones
    Route::get('/inventario/inactivos',       [PanelController::class, 'inactivos'])->name('inventario.inactivos');
    Route::get('/inventario/{id}/editar',     [PanelController::class, 'editInventario'])->name('inventario.edit');
    Route::post('/inventario/{id}/editar',    [PanelController::class, 'updateInventario'])->name('inventario.update');
    Route::post('/inventario/{id}/borrar',    [PanelController::class, 'destroyInventario'])->name('inventario.destroy');
    Route::post('/inventario/{id}/reactivar', [PanelController::class, 'reactivar'])->name('inventario.reactivar');

    Route::get('/citas/inactivas',        [PanelController::class, 'citasInactivas'])->name('citas.inactivas');
    Route::get('/citas/{id}/editar',      [PanelController::class, 'editCita'])->name('citas.edit');
    Route::post('/citas/{id}/editar',     [PanelController::class, 'updateCita'])->name('citas.update');
    Route::post('/citas/{id}/inactivar',  [PanelController::class, 'inactivarCita'])->name('citas.inactivar');
    Route::post('/citas/{id}/reactivar',  [PanelController::class, 'reactivarCita'])->name('citas.reactivar');

    Route::get('/clientes/buscar',  [PanelController::class, 'buscarCliente'])->name('clientes.buscar');
    Route::post('/clientes/store',  [PanelController::class, 'storeCliente'])->name('clientes.store');
    Route::post('/ventas/store',    [PanelController::class, 'storeVenta'])->name('ventas.store');

    // Gestión de ventas
    Route::get('/gestion/ventas2',             [PanelController::class, 'gestionVentas'])->name('gestion.ventas');
    Route::get('/gestion/ventas/inactivas',   [PanelController::class, 'gestionVentasInactivas'])->name('gestion.ventas.inactivas');
    Route::get('/gestion/ventas2/{id}/editar', [PanelController::class, 'editVenta'])->name('gestion.ventas.edit');
    Route::post('/gestion/ventas2/{id}/editar',[PanelController::class, 'updateVenta'])->name('gestion.ventas.update');
    Route::post('/gestion/ventas2/{id}/inactivar', [PanelController::class, 'inactivarVenta'])->name('gestion.ventas.inactivar');
    Route::post('/gestion/ventas2/{id}/reactivar', [PanelController::class, 'reactivarVenta'])->name('gestion.ventas.reactivar');

    Route::get('/gestion/ventas/registrar',  [PanelController::class, 'formVentaTatuaje'])->name('gestion.ventas.form');
    Route::post('/gestion/ventas/registrar', [PanelController::class, 'storeVentaTatuaje'])->name('gestion.ventas.store');

    Route::post('/citas/cliente/store', [PanelController::class, 'storeCitaCliente'])->name('citas.cliente.store');

    Route::get('/usuarios/inactivos',       [PanelController::class, 'usuariosInactivos'])->name('usuarios.inactivos');
Route::get('/usuarios/{id}/editar',     [PanelController::class, 'editUsuario'])->name('usuarios.edit');
Route::post('/usuarios/{id}/editar',    [PanelController::class, 'updateUsuario'])->name('usuarios.update');
Route::post('/usuarios/{id}/inactivar', [PanelController::class, 'inactivarUsuario'])->name('usuarios.inactivar');
Route::post('/usuarios/{id}/reactivar', [PanelController::class, 'reactivarUsuario'])->name('usuarios.reactivar');

Route::post('/citas/{id}/completar', [PanelController::class, 'completarCita'])->name('citas.completar');


Route::get('/reporte/ventas', [PanelController::class, 'reporteVentas'])->name('reporte.ventas');



});
