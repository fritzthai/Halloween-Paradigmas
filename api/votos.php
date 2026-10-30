<?php
require 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') responder(['mensaje' => 'Método no permitido.'], 405);
autenticado();
$d = datos();
$id = (int) ($d['id_disfraz'] ?? 0);
$pdo->beginTransaction();
$consulta = $pdo->prepare('SELECT id FROM disfraces WHERE id = ? AND eliminado = 0 FOR UPDATE');
$consulta->execute([$id]);
if (!$consulta->fetch()) {
    $pdo->rollBack();
    responder(['mensaje' => 'Disfraz no encontrado.'], 404);
}
$consulta = $pdo->prepare('SELECT id FROM votos WHERE id_usuario = ? AND id_disfraz = ?');
$consulta->execute([$_SESSION['usuario']['id'], $id]);
if ($consulta->fetch()) {
    $pdo->rollBack();
    responder(['mensaje' => 'Ya votaste este disfraz.'], 409);
}
$consulta = $pdo->prepare('INSERT INTO votos (id_usuario, id_disfraz) VALUES (?, ?)');
$consulta->execute([$_SESSION['usuario']['id'], $id]);
$consulta = $pdo->prepare('UPDATE disfraces SET votos = votos + 1 WHERE id = ?');
$consulta->execute([$id]);
$pdo->commit();
responder(['mensaje' => 'Voto registrado.'], 201);
