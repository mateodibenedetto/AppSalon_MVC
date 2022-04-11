//** VARIABLES */
const d = document;
let paso = 1;
const pasoFinal = 3;
const pasoInicial = 1;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}



d.addEventListener('DOMContentLoaded', e => {
    iniciarApp();
});


//** INICIAR APP */
function iniciarApp() {
    mostrarSeccion(); // Muestra y oculta las secciones
    tabs(); // Cambia la sección cuando se presionan los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); // Consulta la API en el bacneknd de PHP
    
    idCliente();
    nombreCliente(); // Añade el nombre del cliente al objeto cita
    seleccionarFecha(); // Añade la fecha del cliente al objeto cita
    seleccionarHora(); // Añade la hora del cliente al objeto cita

    mostrarResumen(); // Muestra el resumen de la cita
}


//** MOSTRAR SECCION */
function mostrarSeccion() {

    // Ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = d.querySelector('.mostrar');
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }


    // Seleccionar la sección con el paso...
    const seccion = d.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = d.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab = d.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}


//** TABS */
function tabs() {
    
    // Agrega y cambia la variable de paso según el tab seleccionado
    const botones = d.querySelectorAll('.tabs button');
    botones.forEach( boton => {
        boton.addEventListener('click', e => {
            e.preventDefault();

            paso = parseInt( e.target.dataset.paso );
            mostrarSeccion();

            botonesPaginador(); 
        });
    });
}

//** BOTONES PAGINADOR */
function botonesPaginador() {

    const paginaAnterior = d.querySelector('#anterior');
    const paginaSiguiente = d.querySelector('#siguiente');

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');

        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

// PaginaAnterior
function paginaAnterior() {
    const paginaAnterior = d.querySelector('#anterior');
    paginaAnterior.addEventListener('click', e => {

        if(paso <= pasoInicial) return;
        paso--;

        botonesPaginador();
    });
}

// PaginaSiguiente
function paginaSiguiente() {
    const paginaSiguiente = d.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', e => {

        if(paso >= pasoFinal) return;
        paso++;

        botonesPaginador();
    });
}

//** API */
async function consultarAPI() {

    try {
        // const url = 'http://localhost:3000/public/api/servicios';
        const url = 'us-cdbr-east-05.cleardb.net';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}

//** MOSTRAR SERVICIOS */
function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = d.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = d.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = d.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        };
        
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        d.querySelector('#servicios').appendChild(servicioDiv);
    
    });
}

//** SELECCIONAR SERVICIOS */
function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;

    // Indentificar al elemento que se le da click
    const divServicio = d.querySelector(`[data-id-servicio="${id}"]`);

    // Comprobar si el servicio ya fue agreagdo
    if( servicios.some( agregado => agregado.id === id) ) {
        // Eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id)
        divServicio.classList.remove('seleccionado');
    } else {
        // Agregarlo
        cita.servicios = [...servicios, servicio]; // agregamos el servicio al arreglo de cita al seleccionarlo
        divServicio.classList.add('seleccionado');
    }

    
    console.log(cita)
}

//** NOMBRE CLIENTE  */
function idCliente() {
    cita.id = d.querySelector('#id').value;
}

//** NOMBRE CLIENTE  */
function nombreCliente() {
    cita.nombre = d.querySelector('#nombre').value;
}

//** SELECCIONAR FECHA  */
function seleccionarFecha() {
    const inputFecha = d.querySelector('#fecha');
    inputFecha.addEventListener('input', e => {
        
        const dia = new Date(e.target.value).getUTCDay();
        
        if( [6,0].includes(dia) ) {
            e.target.value = ''; // para que no se seteee una fecha en el input
            mostrarAlerta('Los fines de semana está cerrado', 'error', '.formulario');
        } else {
            cita.fecha = inputFecha.value;
        }
    });
}

//** SELECCIONAR HORA  */
function seleccionarHora() {
    const inputHora = d.querySelector('#hora');
    inputHora.addEventListener('input', e => {

       const horaCita = e.target.value;
       const hora = horaCita.split(":")[0];

        if(hora < 10 || hora > 18) {
            e.target.value = ''; // para que no se seteee una hora en el input
            mostrarAlerta('El horario es de 10:00 a 18:00', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;
        }
    });
}

//** MOSTRAR ALERTA  */
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    // Previene que se genere mas de una alerta
    const alertaPrevia = d.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    // Scripting para crear la alerta
    const alerta = d.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    alerta.classList.add('mt');

    const referencia = d.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece) {
        // Eliminar la alerta
        setTimeout(() => {
            alerta.remove();
        },3000);
    }
}

//** RESUMEN  */
function mostrarResumen() {
    const resumen = d.querySelector('.contenido-resumen');

    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    if( Object.values(cita).includes('') || cita.servicios.length === 0) {
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false)
        return;
    } 

    // Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;


    // Heading para servicios en resumen
    const headingServicios = d.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);


    // Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = d.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = d.createElement('P');
        textoServicio.textContent = nombre

        const precioServicio = d.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    // Heading para citas en resumen
    const headingCita = d.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);
    
    const nombreCliente = d.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const day = fechaObj.getDate() + 2;
    const month = fechaObj.getMonth();
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, month, day));

    const opciones = {day: 'numeric', weekday: 'long', month: 'long', year: 'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones);

    const fechaCita = d.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = d.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora}hs`;

    // Boton para crear una reserveción
    const botonReservar = d.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Turno';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}

//** RESERVAR CITA  */
async function reservarCita() {
    
    const { nombre, fecha, hora, servicios, id } = cita;

    const idServicios = servicios.map( servicio => servicio.id); // almacenar los id de los servicios

    const datos = new FormData();
    
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);


    try {
        // Petición hacia la API
        const url = 'us-cdbr-east-05.cleardb.net';
        const respuesta = await fetch(url, {
        method: 'POST',
        body: datos 
        });

        const resultado = await respuesta.json();

        // console.log(resultado.resultado); devuevle true si hay una respuesta

        if(resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Turno Creado',
                text: 'Tu turno fue creado correctamente',
                button: 'ok'
            }).then( () => {
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al reservar el turno',
        })
    }


   
    // console.log([...datos]);
}