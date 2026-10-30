<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
function responder($datos, $estado = 200) {
    http_response_code($estado);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}
function datos() {
    $datos = json_decode(file_get_contents('php://input'), true);
    if (!is_array($datos)) responder(['mensaje' => 'Datos inválidos.'], 400);
    return $datos;
}
function autenticado() {
    if (!isset($_SESSION['usuario'])) responder(['mensaje' => 'Iniciá sesión para continuar.'], 401);
}
function administrador() {
    autenticado();
    if ($_SESSION['usuario']['nombre'] !== 'admin') responder(['mensaje' => 'Acceso denegado.'], 403);
}
try {
    $pdo = new PDO('mysql:host=localhost;dbname=halloween;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $e) {
    responder(['mensaje' => 'No se pudo conectar. Revisá MySQL e importá halloween.sql.'], 500);
}
set_exception_handler(function ($e) use ($pdo) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    responder(['mensaje' => 'No se pudo completar la operación.'], 500);
});
