<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $correo     = $request->correo;
        $contrasena = $request->contrasena;

        $usuario = DB::selectOne(
            'SELECT u.Correo, u.Contraseña, u.Rol_Id_Rol, u.P_Nombre, u.P_Apellido, u.Telefono,
                    COALESCE(NULLIF(a.Direccion, \'\'), NULLIF(e.Direccion, \'\'), NULLIF(p.Direccion, \'\'), u.Direccion, \'\') as Direccion
             FROM Usuarios u
             LEFT JOIN Administrador a ON a.Usuarios_Id = u.Id AND u.Rol_Id_Rol = \'U1\'
             LEFT JOIN Empleado e      ON e.Usuarios_Id = u.Id AND u.Rol_Id_Rol = \'U2\'
             LEFT JOIN Proveedor p     ON p.Usuarios_Id = u.Id AND u.Rol_Id_Rol = \'U3\'
             WHERE u.Correo = ?',
            [$correo]
        );

        if ($usuario && $contrasena === $usuario->Contraseña) {
            session()->flush();
            session([
                'usuario'  => $usuario->Correo,
                'rol'      => $usuario->Rol_Id_Rol,
                'nombre'   => $usuario->P_Nombre . ' ' . $usuario->P_Apellido,
                'telefono' => $usuario->Telefono,
                'direccion'=> $usuario->Direccion,
                'mostrar_bienvenida' => true,
            ]);

            return match($usuario->Rol_Id_Rol) {
                'U1' => redirect()->route('admin'),
                'U2' => redirect()->route('empleado'),
                'U3' => redirect()->route('proveedor'),
                'U4' => redirect()->route('cliente'),
                default => redirect()->route('login')->with('error', 'Rol no reconocido')
            };
        }

        return redirect()->route('login')->with('error', 'Correo o contraseña incorrectos');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }

    public function showRecuperar()
    {
        return view('auth.recuperar');
    }

    public function recuperar(Request $request)
    {
        $correo = trim($request->correo);
        $usuario = DB::selectOne('SELECT Correo FROM Usuarios WHERE Correo = ?', [$correo]);

        if (!$usuario) {
            return redirect()->route('recuperar')->with('error', 'El correo no está registrado');
        }

        $token      = bin2hex(random_bytes(32));
        $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

        DB::delete('DELETE FROM recuperacion_contrasena WHERE Correo = ?', [$correo]);
        DB::insert('INSERT INTO recuperacion_contrasena (Correo, Token, Expiracion) VALUES (?, ?, ?)',
            [$correo, $token, $expiracion]);

        $link = route('nueva-contrasena') . '?token=' . $token;

        $data = [
            "sender"      => ["name" => "Ulloa Tatu", "email" => "scrumteamcode05@gmail.com"],
            "to"          => [["email" => $correo]],
            "subject"     => "Recuperación de contraseña - Ulloa Tatu",
            "htmlContent" => "
                <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
                    <h2 style='color:#842593;'>Recuperar contraseña</h2>
                    <p>Haz click en el botón para restablecer tu contraseña. Expira en <strong>1 hora</strong>.</p>
                    <a href='$link' style='display:inline-block;padding:12px 24px;background:linear-gradient(to right,#0833a2,#842593);color:white;text-decoration:none;border-radius:8px;'>Restablecer contraseña</a>
                </div>"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "api-key: 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"
        ]);
        curl_exec($ch);
        curl_close($ch);

        return redirect()->route('recuperar')->with('success', 'Se envió un enlace a tu correo');
    }

    public function showNuevaContrasena()
    {
        return view('auth.nueva-contrasena');
    }

    public function nuevaContrasena(Request $request)
    {
        $token      = trim($request->token ?? '');
        $contrasena = $request->contrasena;
        $confirmar  = $request->confirmar;

        if (!$token || !$contrasena || !$confirmar) {
            return redirect()->route('login')->with('error', 'Datos incompletos');
        }

        if ($contrasena !== $confirmar) {
            return redirect()->back()->with('error', 'Las contraseñas no coinciden');
        }

        $resultado = DB::selectOne(
            'SELECT Correo, Expiracion FROM recuperacion_contrasena WHERE Token = ?',
            [$token]
        );

        if (!$resultado) {
            return redirect()->route('login')->with('error', 'El enlace no es válido');
        }

        if (strtotime('now') > strtotime($resultado->Expiracion)) {
            return redirect()->route('recuperar')->with('error', 'El enlace expiró, solicita uno nuevo');
        }

        DB::update('UPDATE Usuarios SET Contraseña = ? WHERE Correo = ?', [$contrasena, $resultado->Correo]);
        DB::delete('DELETE FROM recuperacion_contrasena WHERE Correo = ?', [$resultado->Correo]);

        return redirect()->route('login')->with('success', 'Contraseña actualizada exitosamente');
    }

    public function showRegistro()
    {
        return view('auth.registro');
    }

    public function registro(Request $request)
{
    $contrasena = $request->contrasena;

    if (strlen($contrasena) < 8)
        return redirect()->back()->with('error', 'La contraseña debe tener al menos 8 caracteres');

    if (!preg_match('/[A-Z]/', $contrasena))
        return redirect()->back()->with('error', 'La contraseña debe tener al menos una letra mayúscula');

    if (!preg_match('/[0-9]/', $contrasena))
        return redirect()->back()->with('error', 'La contraseña debe tener al menos un número');

    if (!preg_match('/[^a-zA-Z0-9]/', $contrasena))
        return redirect()->back()->with('error', 'La contraseña debe tener al menos un carácter especial (!@#$...)');

    $partes    = explode(" ", trim($request->nombre));
    $pnombre   = $partes[0] ?? '';
    $snombre   = $partes[1] ?? '';
    $papellido = $partes[2] ?? '';
    $sapellido = $partes[3] ?? '';

    try {
        DB::insert(
            'INSERT INTO Usuarios (Id, Id_tdoc, P_Nombre, S_Nombre, P_Apellido, S_Apellido, Correo, Telefono, Contraseña, Rol_Id_Rol, Estado)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)',
            [$request->numero_documento, $request->tipo_documento, $pnombre, $snombre, $papellido, $sapellido, $request->correo, $request->telefono, $contrasena, 'U4']
        );
        DB::insert(
            'INSERT INTO Clientes (Puntos, fecha_nacimiento, Usuarios_Id) VALUES (0, ?, ?)',
            [$request->fecha_nacimiento, $request->numero_documento]
        );
        return redirect()->route('login')->with('success', 'Registro exitoso');
    } catch (\Exception $e) {
        if ($e->getCode() == "23000") {
            return redirect()->route('registro')->with('error', 'El usuario ya existe');
        }
        return redirect()->route('registro')->with('error', 'Error de conexión, intente más tarde');
    }
}
}