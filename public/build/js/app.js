const d=document;let paso=1;const pasoFinal=3,pasoInicial=1,cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function iniciarApp(){mostrarSeccion(),tabs(),botonesPaginador(),paginaSiguiente(),paginaAnterior(),consultarAPI(),idCliente(),nombreCliente(),seleccionarFecha(),seleccionarHora(),mostrarResumen()}function mostrarSeccion(){const e=d.querySelector(".mostrar");e&&e.classList.remove("mostrar");d.querySelector("#paso-"+paso).classList.add("mostrar");const t=d.querySelector(".actual");t&&t.classList.remove("actual");d.querySelector(`[data-paso="${paso}"]`).classList.add("actual")}function tabs(){d.querySelectorAll(".tabs button").forEach(e=>{e.addEventListener("click",e=>{e.preventDefault(),paso=parseInt(e.target.dataset.paso),mostrarSeccion(),botonesPaginador()})})}function botonesPaginador(){const e=d.querySelector("#anterior"),t=d.querySelector("#siguiente");1===paso?(e.classList.add("ocultar"),t.classList.remove("ocultar")):3===paso?(e.classList.remove("ocultar"),t.classList.add("ocultar"),mostrarResumen()):(e.classList.remove("ocultar"),t.classList.remove("ocultar")),mostrarSeccion()}function paginaAnterior(){d.querySelector("#anterior").addEventListener("click",e=>{paso<=1||(paso--,botonesPaginador())})}function paginaSiguiente(){d.querySelector("#siguiente").addEventListener("click",e=>{paso>=3||(paso++,botonesPaginador())})}async function consultarAPI(){try{const e="us-cdbr-east-05.cleardb.net",t=await fetch(e);mostrarServicios(await t.json())}catch(e){console.log(e)}}function mostrarServicios(e){e.forEach(e=>{const{id:t,nombre:a,precio:o}=e,r=d.createElement("P");r.classList.add("nombre-servicio"),r.textContent=a;const n=d.createElement("P");n.classList.add("precio-servicio"),n.textContent="$"+o;const c=d.createElement("DIV");c.classList.add("servicio"),c.dataset.idServicio=t,c.onclick=function(){seleccionarServicio(e)},c.appendChild(r),c.appendChild(n),d.querySelector("#servicios").appendChild(c)})}function seleccionarServicio(e){const{id:t}=e,{servicios:a}=cita,o=d.querySelector(`[data-id-servicio="${t}"]`);a.some(e=>e.id===t)?(cita.servicios=a.filter(e=>e.id!==t),o.classList.remove("seleccionado")):(cita.servicios=[...a,e],o.classList.add("seleccionado")),console.log(cita)}function idCliente(){cita.id=d.querySelector("#id").value}function nombreCliente(){cita.nombre=d.querySelector("#nombre").value}function seleccionarFecha(){const e=d.querySelector("#fecha");e.addEventListener("input",t=>{const a=new Date(t.target.value).getUTCDay();[6,0].includes(a)?(t.target.value="",mostrarAlerta("Los fines de semana está cerrado","error",".formulario")):cita.fecha=e.value})}function seleccionarHora(){d.querySelector("#hora").addEventListener("input",e=>{const t=e.target.value.split(":")[0];t<10||t>18?(e.target.value="",mostrarAlerta("El horario es de 10:00 a 18:00","error",".formulario")):cita.hora=e.target.value})}function mostrarAlerta(e,t,a,o=!0){const r=d.querySelector(".alerta");r&&r.remove();const n=d.createElement("DIV");n.textContent=e,n.classList.add("alerta"),n.classList.add(t),n.classList.add("mt");d.querySelector(a).appendChild(n),o&&setTimeout(()=>{n.remove()},3e3)}function mostrarResumen(){const e=d.querySelector(".contenido-resumen");for(;e.firstChild;)e.removeChild(e.firstChild);if(Object.values(cita).includes("")||0===cita.servicios.length)return void mostrarAlerta("Faltan datos de Servicios, Fecha u Hora","error",".contenido-resumen",!1);const{nombre:t,fecha:a,hora:o,servicios:r}=cita,n=d.createElement("H3");n.textContent="Resumen de Servicios",e.appendChild(n),r.forEach(t=>{const{id:a,precio:o,nombre:r}=t,n=d.createElement("DIV");n.classList.add("contenedor-servicio");const c=d.createElement("P");c.textContent=r;const s=d.createElement("P");s.innerHTML="<span>Precio:</span> $"+o,n.appendChild(c),n.appendChild(s),e.appendChild(n)});const c=d.createElement("H3");c.textContent="Resumen de Cita",e.appendChild(c);const s=d.createElement("P");s.innerHTML="<span>Nombre:</span> "+t;const i=new Date(a),l=i.getDate()+2,u=i.getMonth(),p=i.getFullYear(),m=new Date(Date.UTC(p,u,l)).toLocaleDateString("es-AR",{day:"numeric",weekday:"long",month:"long",year:"numeric"}),v=d.createElement("P");v.innerHTML="<span>Fecha:</span> "+m;const h=d.createElement("P");h.innerHTML=`<span>Hora:</span> ${o}hs`;const f=d.createElement("BUTTON");f.classList.add("boton"),f.textContent="Reservar Turno",f.onclick=reservarCita,e.appendChild(s),e.appendChild(v),e.appendChild(h),e.appendChild(f)}async function reservarCita(){const{nombre:e,fecha:t,hora:a,servicios:o,id:r}=cita,n=o.map(e=>e.id),c=new FormData;c.append("fecha",t),c.append("hora",a),c.append("usuarioId",r),c.append("servicios",n);try{const e="us-cdbr-east-05.cleardb.net",t=await fetch(e,{method:"POST",body:c});(await t.json()).resultado&&Swal.fire({icon:"success",title:"Turno Creado",text:"Tu turno fue creado correctamente",button:"ok"}).then(()=>{setTimeout(()=>{window.location.reload()},3e3)})}catch(e){Swal.fire({icon:"error",title:"Error",text:"Hubo un error al reservar el turno"})}}d.addEventListener("DOMContentLoaded",e=>{iniciarApp()});