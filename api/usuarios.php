<?php
require 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];
if ($metodo === 'GET') responder(['usuario' => $_SESSION['usuario'] ?? null]);
if ($metodo === 'DELETE') {
    $_SESSION = [];
    session_destroy();
    responder(['mensaje' => 'Sesión cerrada.']);
}
if ($metodo !== 'POST') responder(['mensaje' => 'Método no permitido.'], 405);
$d = datos();
$nombre = trim($d['nombre'] ?? '');
$clave = $d['clave'] ?? '';
if ($nombre === '' || strlen($nombre) > 50 || $clave === '' || strlen($clave) > 72) responder(['mensaje' => 'Completá los datos: usuario hasta 50 bytes y contraseña hasta 72 bytes.'], 400);
if (($_GET['accion'] ?? '') === 'registro') {
    $consulta = $pdo->prepare('SELECT id FROM usuarios WHERE nombre = ?');
    $consulta->execute([$nombre]);
    if ($consulta->fetch()) responder(['mensaje' => 'Ese nombre ya está registrado.'], 409);
    try {
        $consulta = $pdo->prepare('INSERT INTO usuarios (nombre, clave) VALUES (?, ?)');
        $consulta->execute([$nombre, password_hash($clave, PASSWORD_BCRYPT)]);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') responder(['mensaje' => 'Ese nombre ya está registrado.'], 409);
        throw $e;
    }
    responder(['mensaje' => 'Registro correcto. Ya podés iniciar sesión.'], 201);
}
$consulta = $pdo->prepare('SELECT * FROM usuarios WHERE nombre = ?');
$consulta->execute([$nombre]);
$usuario = $consulta->fetch();
if (!$usuario || !password_verify($clave, $usuario['clave'])) responder(['mensaje' => 'Usuario o contraseña incorrectos.'], 401);
session_regenerate_id(true);
$_SESSION['usuario'] = ['id' => $usuario['id'], 'nombre' => $usuario['nombre']];
responder(['mensaje' => 'Sesión iniciada.']);
