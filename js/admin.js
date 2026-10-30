const formulario = document.querySelector('#form-disfraz');
async function cargar() {
    try {
        const disfraces = await api('disfraces.php');
        const lista = document.querySelector('#disfraces');
        lista.replaceChildren();
        disfraces.forEach(disfraz => {
            const tarjeta = document.createElement('article');
            tarjeta.append(texto('h2', disfraz.nombre), texto('p', disfraz.descripcion), texto('p', 'Votos: ' + disfraz.votos));
            const editar = texto('button', 'Editar');
            editar.addEventListener('click', () => {
                formulario.elements.id.value = disfraz.id;
                formulario.nombre.value = disfraz.nombre;
                formulario.descripcion.value = disfraz.descripcion;
                formulario.nombre.focus();
            });
            const eliminar = texto('button', 'Eliminar');
            eliminar.addEventListener('click', async () => {
                if (!confirm('¿Eliminar este disfraz?')) return;
                try {
                    const resultado = await api('disfraces.php?id=' + disfraz.id, 'DELETE');
                    mensaje(resultado.mensaje);
                    if (formulario.elements.id.value === String(disfraz.id)) formulario.reset();
                    await cargar();
                } catch (error) { mensaje(error.message); }
            });
            tarjeta.append(editar, eliminar);
            lista.append(tarjeta);
        });
    } catch (error) { mensaje(error.message); }
}
formulario.addEventListener('submit', async evento => {
    evento.preventDefault();
    const id = formulario.elements.id.value;
    try {
        const resultado = await api('disfraces.php' + (id ? '?id=' + id : ''), id ? 'PUT' : 'POST', {nombre: formulario.nombre.value, descripcion: formulario.descripcion.value});
        mensaje(resultado.mensaje);
        formulario.reset();
        await cargar();
    } catch (error) { mensaje(error.message); }
});
document.querySelector('#cancelar').addEventListener('click', () => formulario.reset());
cargar();
