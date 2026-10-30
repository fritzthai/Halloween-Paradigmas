document.querySelector('#autenticacion').addEventListener('submit', async (evento) => {
    evento.preventDefault();
    const formulario = evento.currentTarget;
    try {
        const datos = {nombre: formulario.nombre.value, clave: formulario.clave.value};
        const resultado = await api('usuarios.php?accion=' + formulario.dataset.accion, 'POST', datos);
        mensaje(resultado.mensaje);
        formulario.reset();
        if (formulario.dataset.accion === 'login') window.location.href = 'index.html';
    } catch (error) { mensaje(error.message); }
});
