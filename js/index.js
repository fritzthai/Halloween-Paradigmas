async function cargar() {
    try {
        const disfraces = await api('disfraces.php');
        const lista = document.querySelector('#disfraces');
        lista.replaceChildren();
        if (!disfraces.length) lista.append(texto('p', 'Todavía no hay disfraces cargados.'));
        disfraces.forEach(disfraz => {
            const tarjeta = document.createElement('article');
            tarjeta.append(texto('h2', disfraz.nombre), texto('p', disfraz.descripcion), texto('p', 'Votos: ' + disfraz.votos));
            const boton = texto('button', 'Votar');
            boton.addEventListener('click', async () => {
                boton.disabled = true;
                try {
                    const resultado = await api('votos.php', 'POST', {id_disfraz: disfraz.id});
                    mensaje(resultado.mensaje);
                    await cargar();
                } catch (error) { mensaje(error.message); }
                finally { boton.disabled = false; }
            });
            tarjeta.append(boton);
            lista.append(tarjeta);
        });
    } catch (error) { mensaje(error.message); }
}
cargar();
