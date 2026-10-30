# Halloween — Paradigmas y Lenguajes de Programación III

## Ejecutar en XAMPP

1. Descargar el repositorio (Code → Download ZIP), extraerlo y renombrar la carpeta como `halloween`. Colocarla dentro de `C:\xampp\htdocs`.
2. Iniciar Apache y MySQL desde el panel de XAMPP.
3. Abrir `http://localhost/phpmyadmin` e importar `halloween.sql`. Importarlo una sola vez sobre una instalación nueva.
4. Abrir `http://localhost/halloween/` desde el navegador. No abrir index.html con doble clic.
5. Si MySQL tiene otros datos de conexión, modificar `api/conexion.php` (por defecto: localhost, root, contraseña vacía).

## Administrador

Usuario: `admin`
Contraseña: `Admin123`

El administrador se crea al importar el SQL. Iniciar sesión y entrar al enlace Administración para crear los primeros disfraces. No hay disfraces de muestra.

Se reserva el usuario admin para administración porque la tabla indicada no contiene un campo de rol. El registro no permite repetir nombres. Las contraseñas se guardan con BCRYPT y el acceso se controla con sesiones PHP.

## Uso

Los visitantes pueden ver los disfraces. Para votar deben registrarse e iniciar sesión. Cada usuario puede votar una vez a cada disfraz: un segundo intento devuelve un mensaje y no suma votos. El administrador puede crear, listar, editar y eliminar disfraces. La eliminación usa el campo eliminado (borrado lógico).

## Tablas

Se mantienen los campos solicitados en las tres tablas. Se agregan claves primarias con AUTO_INCREMENT, claves foráneas y restricciones UNIQUE para identificar registros, relacionarlos e impedir duplicados. foto y foto_blob se guardan vacíos porque la carga de imágenes es opcional y no se implementó.

## API REST

| Método | Dirección | Operación |
| --- | --- | --- |
| GET | api/disfraces.php | Listar disfraces activos |
| POST | api/disfraces.php | Crear disfraz (admin) |
| PUT | api/disfraces.php?id=1 | Editar disfraz (admin) |
| DELETE | api/disfraces.php?id=1 | Eliminar disfraz (admin) |
| POST | api/usuarios.php?accion=registro | Registrar usuario |
| POST | api/usuarios.php?accion=login | Iniciar sesión |
| GET | api/usuarios.php | Consultar sesión |
| DELETE | api/usuarios.php | Cerrar sesión |
| POST | api/votos.php | Votar (usuario autenticado) |

Las solicitudes POST y PUT envían JSON. Usuarios recibe nombre y clave; disfraces recibe nombre y descripcion; votos recibe id_disfraz. La API devuelve JSON y estados HTTP (200, 201, 400, 401, 403, 404, 405 y 409 según el caso).

Se usa PDO con consultas preparadas, password_hash/password_verify, session_start, fetch con async/await, addEventListener y textContent para mostrar datos sin interpretarlos como HTML. No requiere paquetes, frameworks ni conexiones a servicios externos.

## Comprobación en XAMPP

1. Entrar como admin, crear un disfraz y verificarlo en la página principal.
2. Editar su descripción y comprobar el cambio.
3. Cerrar sesión y probar Votar: debe pedir iniciar sesión y no sumar.
4. Registrar un usuario, intentar repetir su nombre y comprobar el rechazo.
5. Iniciar sesión con una contraseña incorrecta: debe rechazarla.
6. Iniciar sesión correctamente y votar: debe sumar uno.
7. Votar otra vez al mismo disfraz: no debe sumar.
8. Entrar a admin.php con el usuario común: debe denegar el acceso. La API de CRUD también comprueba permisos.
9. Volver como admin y eliminar el disfraz: debe desaparecer del listado.

## Entrega

El repositorio contiene solamente este proyecto. No se incluyen otros proyectos ni archivos de XAMPP.
