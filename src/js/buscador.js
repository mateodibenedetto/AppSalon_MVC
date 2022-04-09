const d = document;

d.addEventListener('DOMContentLoaded', e => {
    iniciarApp();
});

function iniciarApp() {
    buscarPorFecha();
}

function buscarPorFecha() {
    const fechaInput = d.querySelector('#fecha');
    fechaInput.addEventListener('input', e => {
        const fechaSeleccionada = e.target.value;

        window.location = `?fecha=${fechaSeleccionada}`;
    });
}