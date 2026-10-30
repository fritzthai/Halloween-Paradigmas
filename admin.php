<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.html'); exit; }
if ($_SESSION['usuario']['nombre'] !== 'admin') { http_response_code(403); exit('Acceso denegado.'); }
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Administración | Halloween</title><link rel="stylesheet" href="css/estilos.css"><script src="js/comun.js" defer></script><script src="js/admin.js" defer></script></head><body><header><a class="marca" href="index.html">Halloween</a><nav><a href="index.html">Disfraces</a><a href="registro.html">Registro</a><a href="login.html">Ingresar</a><a id="enlace-admin" href="admin.php" hidden>Administración</a><button id="salir" hidden>Cerrar sesión</button></nav></header><main><p id="sesion"></p><p id="mensaje" role="status"></p><h1>Administrar disfraces</h1><form id="form-disfraz" class="panel"><input name="id" type="hidden"><label>Nombre<input name="nombre" maxlength="50" required></label><label>Descripción<textarea name="descripcion" required></textarea></label><button>Guardar</button><button id="cancelar" type="button">Cancelar edición</button></form><section id="disfraces" class="tarjetas"></section></main></body></html>
