async function api(ruta, metodo = 'GET', datos = null) {
    const opciones = {method: metodo};
    if (datos !== null) {
        opciones.headers = {'Content-Type': 'application/json'};
        opciones.body = JSON.stringify(datos);
    }
    const respuesta = await fetch('api/' + ruta, opciones);
    const resultado = await respuesta.json();
    if (!respuesta.ok) throw new Error(resultado.mensaje);
    return resultado;
}
function mensaje(texto) { document.querySelector('#mensaje').textContent = texto; }
function texto(etiqueta, contenido) {
    const elemento = document.createElement(etiqueta);
    elemento.textContent = contenido;
    return elemento;
}
async function sesion() {
    try {
        const datos = await api('usuarios.php');
        const usuario = datos.usuario;
        document.querySelector('#sesion').textContent = usuario ? 'Sesión: ' + usuario.nombre : 'Iniciá sesión para votar.';
        document.querySelector('#enlace-admin').hidden = !usuario || usuario.nombre !== 'admin';
        document.querySelector('#salir').hidden = !usuario;
    } catch (error) { mensaje(error.message); }
}
document.querySelector('#salir').addEventListener('click', async () => {
    try { await api('usuarios.php', 'DELETE'); window.location.href = 'index.html'; }
    catch (error) { mensaje(error.message); }
});
sesion();
