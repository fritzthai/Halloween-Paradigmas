<?php
require 'conexion.php';
$metodo = $_SERVER['REQUEST_METHOD'];
if ($metodo === 'GET') {
    $consulta = $pdo->query('SELECT id, nombre, descripcion, votos FROM disfraces WHERE eliminado = 0 ORDER BY id DESC');
    responder($consulta->fetchAll());
}
administrador();
if ($metodo === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    $consulta = $pdo->prepare('UPDATE disfraces SET eliminado = 1 WHERE id = ? AND eliminado = 0');
    $consulta->execute([$id]);
    if (!$consulta->rowCount()) responder(['mensaje' => 'Disfraz no encontrado.'], 404);
    responder(['mensaje' => 'Disfraz eliminado.']);
}
if ($metodo !== 'POST' && $metodo !== 'PUT') responder(['mensaje' => 'Método no permitido.'], 405);
$d = datos();
$nombre = trim($d['nombre'] ?? '');
$descripcion = trim($d['descripcion'] ?? '');
if ($nombre === '' || strlen($nombre) > 50 || $descripcion === '') responder(['mensaje' => 'Completá nombre (hasta 50 bytes) y descripción.'], 400);
if ($metodo === 'POST') {
    $consulta = $pdo->prepare("INSERT INTO disfraces (nombre, descripcion, votos, foto, foto_blob) VALUES (?, ?, 0, '', '')");
    $consulta->execute([$nombre, $descripcion]);
    responder(['mensaje' => 'Disfraz creado.'], 201);
}
$id = (int) ($_GET['id'] ?? 0);
$consulta = $pdo->prepare('SELECT id FROM disfraces WHERE id = ? AND eliminado = 0');
$consulta->execute([$id]);
if (!$consulta->fetch()) responder(['mensaje' => 'Disfraz no encontrado.'], 404);
$consulta = $pdo->prepare('UPDATE disfraces SET nombre = ?, descripcion = ? WHERE id = ? AND eliminado = 0');
$consulta->execute([$nombre, $descripcion, $id]);
responder(['mensaje' => 'Disfraz actualizado.']);
