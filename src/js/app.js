let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    name: '',
    date: '',
    time: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion(); //Muestra y oculta las secciones
    tabs();//Cambia la seccion cuando se presionan los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaAnterior();
    paginaSiguiente();

    consultarAPI() // consulta la api de php

    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita en el objeto
    seleccionarHora(); // Añade la hora de la cita en el objeto

    mostrarResumen(); // Mostrar resumen de la Cita
}

function mostrarSeccion() {
    //ocultar la seccion que tenga la classe de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }
    // seleccionar la seccion con el paso...
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar')

    //Quita la classe actual del tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach( boton => {
        boton.addEventListener('click', function(e) {
            paso = parseInt( e.target.dataset.paso );

            mostrarSeccion();
            botonesPaginador();

        });
    })

}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if(paso === 3) {
        paginaAnterior.classList.remove('ocultar')
        paginaSiguiente.classList.add('ocultar');

        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar')
        paginaSiguiente.classList.remove('ocultar');
    }
    mostrarSeccion();

}
function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {
        if (paso <= pasoInicial) return;
        paso --;
        botonesPaginador();
    })
}
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {
        if (paso >= pasoFinal) return;
        paso ++;
        botonesPaginador();
    })
}

async function consultarAPI() {

    try {
        const url = 'http://localhost:3000/api/services';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach( servicio => {
        const { id, name, price } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('servicio__nombre');
        nombreServicio.textContent = name;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('servicio__precio');
        precioServicio.textContent = `$${price}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function seleccionarServicio(servicio) {
    const {id} = servicio;
    const {servicios} = cita;
    //identificar al elemento al que se da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    // Comprobar si un servicio ya fue agregado y quitarlo
    if( servicios.some( agregado => agregado.id === id)) {
        cita.servicios = servicios.filter( agregado => agregado.id !== id );
        divServicio.classList.remove('seleccionado');
    } else {
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }

}

function nombreCliente() {
    cita.name = document.querySelector('#name').value;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#date');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date(e.target.value).getUTCDay();

        if( [1, 0].includes(dia) ) {
            e.target.value = '';
            mostrarAlerta('Monday and Sunday Closed', 'error', '.formulario');
        } else {
            cita.date = e.target.value;
        }

    })
};

function seleccionarHora() {
    const inputHora = document.querySelector('#time');
    inputHora.addEventListener('input', function(e){
        const horaCita = e.target.value;
        const horaSplit = horaCita.split(":")[0];
        if(horaSplit < 9 || horaSplit > 20) {
            e.target.value = '';
            mostrarAlerta('Open times: 9:00 ~ 20:00', 'error', '.formulario');
        } else {
            cita.time = e.target.value;
        }

    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    //Previene que se generen mas de una aletra
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    }

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);
    if (desaparece) {
        // Eliminar la alerta despues de 3s
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    };
};

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');
     // limpiar el contenido del resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    if (cita.servicios.length === 0 || Object.values(cita).includes('')) {
        mostrarAlerta('Booking details are incomplete', 'error', '.contenido-resumen', false);
    } 

    // Formatear el DIV de resumen
    const  {name, date, time, servicios } = cita;

    // Heading para Servicios en Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Summary of Services';
    resumen.appendChild(headingServicios);

    // iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, price, name } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = name;
        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Price: </span>$${price}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });
    
    // Heading para Cita en Resumen
    const headingCita = document.createElement('H3');
        headingCita.textContent = 'Booking Infomation';
        resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Name: </span> ${name}`;

    //Formatear  la fecha
    const fechaObj = new Date(date);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();
    
    const fechaUtc = new Date( Date.UTC(year, mes, dia));

    const opciones = {weekday: 'long', month: 'long', day: 'numeric'};
    const fechaFormateada = fechaUtc.toLocaleDateString('ja-JP-u-ca-japanese', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Date: </span> ${fechaFormateada}`;
    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Time: </span> ${time}`;

    // Boton para Crear una Cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Book Now';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    resumen.appendChild(botonReservar);
}

function reservarCita() {
    
}
