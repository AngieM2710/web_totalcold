$(document).ready(() => {
  // Inicialización general
  $("#formulario").on("submit", guardaryeditar);
  ["Clientes", "Tecnicos", "Servicios", "Equipos"].forEach(entidad => cargarOpciones(entidad));
  estadoServicio();
});

// ------------------------------
// CARGA DE SELECTS (una sola función)
// ------------------------------
function cargarOpciones(tipo) {
  const urls = {
    Clientes: "../ajax/clientes.php?op=selectClientes",
    Tecnicos: "../ajax/tecnicos.php?op=selectTenicos",
    Servicios: "../ajax/categorias.php?op=selectServicios",
    Equipos: "../ajax/equipos.php?op=selectEquipos"
  };

  const ids = {
    Clientes: "#id_cli",
    Tecnicos: "#id_tec",
    Servicios: "#id_serv",
    Equipos: "#id_equipo"
  };

  $.post(urls[tipo], function (r) {
    const textoBase = {
      Clientes: "Nombre del Cliente",
      Tecnicos: "Nombre del Técnico",
      Servicios: "Tipo de Servicio",
      Equipos: "Marca - Capacidad"
    }[tipo];

    $(ids[tipo]).html(`<option value="">${textoBase}</option>${r}`).selectpicker("refresh");
  });
}

// ------------------------------
// COLOR AUTOMÁTICO DEL ESTADO
// ------------------------------
function estadoServicio() {
  const select = $("#estadoServicio");
  select.selectpicker();

  const actualizarColor = () => {
    const btn = select.closest(".bootstrap-select").find(".dropdown-toggle");
    btn.removeClass("btn-danger btn-success btn-light text-white");
    if (select.val() === "Pendiente") btn.addClass("btn-danger text-white");
    else if (select.val() === "Terminado") btn.addClass("btn-success text-white");
    else btn.addClass("btn-light");
  };

  select.on("changed.bs.select", actualizarColor);
  actualizarColor();
}

// ------------------------------
// MANEJO DE EQUIPOS
// ------------------------------
const equiposContainer = document.getElementById("equiposContainer");
let serviciosOrden = [];

// function createEquipo(id, nombre, servicios) {
//   const div = document.createElement("div");
//   div.classList.add("equipo-item", "border", "rounded", "p-3", "mb-3");
//   div.dataset.idEquipo = id;
//   div.dataset.servicios = JSON.stringify(servicios.map(s => s.id));

//   const serviciosTexto = servicios.map(s => s.nombre).join(", ");
//   div.innerHTML = `
//     <div class="mb-2">
//       <strong>Equipo:</strong> ${nombre}<br>
//       <strong>Servicios:</strong> ${serviciosTexto}
//     </div>
//     <div class="mb-2">
//       <a href="#" class="btn btn-info btn-circle btn-sm verDetalles"><i class="fas fa-eye"></i></a>
//       <a href="#" class="btn btn-danger btn-circle btn-sm removeEquipo"><i class="fas fa-trash"></i></a>
//     </div>
//   `;

//   // Acciones
//   div.querySelector(".removeEquipo").addEventListener("click", e => {
//     e.preventDefault();
//     div.remove();
//     actualizarServiciosOrden();
//   });

//   div.querySelector(".verDetalles").addEventListener("click", e => {
//     e.preventDefault();
//     $("#detalleEquipo").text(nombre);
//     $("#detalleServicios").text(serviciosTexto);
//     new bootstrap.Modal("#modalDetallesEquipo").show();
//   });

//   equiposContainer.appendChild(div);
//   actualizarServiciosOrden();
// }

function createEquipo(id, nombre, servicios) {
    const div = document.createElement("div");
    div.classList.add("equipo-item");
    div.dataset.idEquipo = id;
    div.dataset.servicios = JSON.stringify(servicios.map(s => s.id));

    const serviciosBadges = servicios.map(s =>
        `<span class="badge bg-info text-dark badge-servicio">${s.nombre}</span>`
    ).join(" ");

    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6>${nombre}</h6>
                <div>${serviciosBadges}</div>
            </div>
            <div class="d-flex flex-column gap-2">
                <a href="#" class="btn btn-outline-info btn-circle B verDetalles" title="Ver Detalles">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="#" class="btn btn-outline-danger btn-circle B removeEquipo" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
    `;

    // Eventos
    div.querySelector(".removeEquipo").addEventListener("click", e => {
        e.preventDefault();
        div.remove();
        actualizarServiciosOrden();
    });

    div.querySelector(".verDetalles").addEventListener("click", e => {
        e.preventDefault();
        $("#detalleEquipo").text(nombre);
        $("#detalleServicios").html(serviciosBadges);
        new bootstrap.Modal("#modalDetallesEquipo").show();
    });

    equiposContainer.appendChild(div);
    actualizarServiciosOrden();
}

// Botón de guardar equipo
$("#saveEquipo").on("click", () => {
  const equipoSelect = $("#id_equipo");
  const serviciosSelect = $("#id_serv");
  const equipoId = equipoSelect.val();
  const equipoNombre = equipoSelect.find("option:selected").text();
  const serviciosArray = serviciosSelect.find("option:selected").map((_, opt) => ({
    id: opt.value,
    nombre: opt.text
  })).get();

  if (equipoId && serviciosArray.length) {
    createEquipo(equipoId, equipoNombre, serviciosArray);
    $("#formAddEquipo")[0].reset();
    $(".selectpicker").selectpicker("refresh");
    bootstrap.Modal.getInstance($("#modalAddEquipo")[0]).hide();
  } else {
    Swal.fire("Atención", "Seleccione un equipo y al menos un servicio.", "warning");
  }
});

// ------------------------------
// MANEJO DE SERVICIOS ORDEN
// ------------------------------
const tablaServiciosOrden = $("#tablaServiciosOrden tbody");

// function refrescarTablaServicios() {
//   tablaServiciosOrden.html("");
//   serviciosOrden.forEach(servicio => {
//     const tr = $(`
//       <tr>
//         <td>${servicio.nombre}</td>
//         <td><input type="number" class="form-control precioServicio" value="${servicio.precio}" min="0"></td>
//       </tr>
//     `);
//     tr.find(".precioServicio").on("input", e => servicio.precio = parseFloat(e.target.value) || 0);
//     tablaServiciosOrden.append(tr);
//   });
// }

function actualizarServiciosOrden() {
  const contador = {};
  // Recorremos los equipos y sus servicios
  $(".equipo-item").each(function () {
    const servicios = JSON.parse(this.dataset.servicios); // array de IDs
    servicios.forEach(id_serv => {
      const nombre = $("#id_serv option[value='" + id_serv + "']").text().trim();
      if (!contador[id_serv]) contador[id_serv] = { id: id_serv, nombre, cantidad: 0, precio: 0 };
      contador[id_serv].cantidad++;
    });
  });

  // Convertimos el objeto a array
  serviciosOrden = Object.values(contador);
  refrescarTablaServicios();
}

function refrescarTablaServicios() {
  const tbody = $("#tablaServiciosOrden tbody");
  tbody.html("");
  let total = 0;

  serviciosOrden.forEach(s => {
    const tr = $(`
      <tr>
        <td>${s.nombre}</td>
        <td>${s.cantidad}</td>
        <td><input type="number" class="form-control precioServicio" value="${s.precio}" min="0"></td>
      </tr>
    `);

    tr.find(".precioServicio").on("input", e => {
      s.precio = parseFloat(e.target.value) || 0;
      calcularTotalOrden();
    });

    tbody.append(tr);
  });

  calcularTotalOrden();
}

function calcularTotalOrden() {
  let total = 0;
  serviciosOrden.forEach(s => total += s.precio);
  $("#totalOrden").text(total.toFixed(2));
}

// function actualizarServiciosOrden() {
//   serviciosOrden = [];
//   $(".equipo-item").each(function () {
//     const servicios = $(this).find("strong:last").text().replace("Servicios:", "").split(",").map(s => s.trim());
//     servicios.forEach(nombre => {
//       if (!serviciosOrden.find(s => s.nombre === nombre))
//         serviciosOrden.push({ nombre, precio: 0 });
//     });
//   });
//   refrescarTablaServicios();
// }


// preesnta en el card de boton servicio la orden perepara por sus servicios unificado y valor
function capturarServiciosOrden() {
  const servicios = [];

  $("#tablaServiciosOrden tbody tr").each(function() {
    const nombre = $(this).find("td:nth-child(1)").text().trim();
    const cantidad = parseInt($(this).find("td:nth-child(2)").text().trim());
    const precio = parseFloat($(this).find("input").val()) || 0;

    // Buscar el id del servicio desde el select original
    const id_serv = $("#id_serv option").filter(function() {
      return $(this).text().trim() === nombre;
    }).val();

    servicios.push({
      id: id_serv,
      nombre: nombre,
      cantidad: cantidad,
      precio: precio
    });
  });

  return servicios;
}
// boton captura el valor  de los servicio unificado para distribuirlos
$("#btnGuardarServicios").on("click", () => {
  serviciosOrden = capturarServiciosOrden();
  $("#modalServicios").modal("hide");
  console.log("Servicios capturados:", serviciosOrden);
   mostrarResumenServicios();
});
function mostrarResumenServicios() {
  let html = "";
  let total = 0;

  serviciosOrden.forEach(s => {
    html += `
      <div class="servicio-item">
        <span class="servicio-nombre">• ${s.nombre} (${s.cantidad})</span>
        <span class="servicio-precio">$${s.precio.toFixed(2)}</span>
      </div>
    `;
    total += s.precio;
  });

  html += `<strong>Total: $${total.toFixed(2)}</strong>`;
  $("#resumenServicios").html(html);
}

/* function mostrarResumenServicios() {
  let html = "";
  let total = 0;

  serviciosOrden.forEach(s => {
    html += `<div>• ${s.nombre} (${s.cantidad}) → $${s.precio.toFixed(2)}</div>`;
    total += s.precio;
  });

  html += `<strong>Total: $${total.toFixed(2)}</strong>`;
  $("#resumenServicios").html(html);
}
 */
//-------------------------------------------------------
$("#btnServiciosOrden").on("click", () => {
  actualizarServiciosOrden();
  new bootstrap.Modal("#modalServiciosOrden").show();
});

$("#addServicioOrden").on("click", () => {
  const nuevo = $("#nuevoServicioOrden").val().trim();
  if (nuevo && !serviciosOrden.find(s => s.nombre === nuevo)) {
    serviciosOrden.push({ nombre: nuevo, precio: 0 });
    refrescarTablaServicios();
    $("#nuevoServicioOrden").val("");
  }
});

// ------------------------------
// GUARDAR ORDEN
// ------------------------------
function guardaryeditar(e) {
  e.preventDefault();
  $("#btnGuardar").prop("disabled", true);

  // valida el boton que este lleno ls campos , cliente, tec,fecha dire y los equipos:     
  //  Validar campos obligatorios
    const id_cli = $("#id_cli").val();
    const id_tec = $("#id_tec").val();
    const fecha = $("#fecha").val();
    const direccion = $("#direccion").val().trim();
    const equiposAgregados = document.querySelectorAll('.equipo-item').length;

    if (!id_cli || !id_tec || !fecha || !direccion || equiposAgregados === 0) {
        Swal.fire({
            title: "Datos incompletos",
            text: "Debe llenar Cliente, Técnico, Fecha/Hora, Dirección y agregar al menos un equipo con servicios.",
            icon: "warning",
            confirmButtonText: "Aceptar"
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    //Verifica que serviciosOrden tenga precios válidos antes de enviar
    serviciosOrden = capturarServiciosOrden();
    const totalServicios = serviciosOrden.reduce((acc, s) => acc + s.precio, 0);
    if (totalServicios <= 0) {
    Swal.fire("Atención", "Debe ingresar un valor en los servicios.", "warning");
    $("#btnGuardar").prop("disabled", false);
    return;
    }
    //
   // Crear formData
  const formData = new FormData($("#formulario")[0]);
  const equipos = $(".equipo-item").map((_, div) => ({
    id_equipo: div.dataset.idEquipo,
    servicios: JSON.parse(div.dataset.servicios)
  })).get();

  formData.append("equipos", JSON.stringify(equipos));
  formData.append("serviciosOrden", JSON.stringify(serviciosOrden));

  $.ajax({
    url: "../ajax/ordenes.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: datos => {
      Swal.fire({
        title: `<span style="font-size: 24px;">${datos}</span>`,
        icon: "success",
        width: 600
      });
      if (typeof tabla !== "undefined" && tabla) tabla.ajax.reload();
      limpiar();
      $("#btnGuardar").prop("disabled", false);
    },
    error: err => {
      Swal.fire("Error", "No se pudo guardar la orden.", "error");
      $("#btnGuardar").prop("disabled", false);
    }
  });
}

function limpiar() {
  $("#formulario")[0].reset();// Limpiar formulario principal
  $(".selectpicker").selectpicker("refresh");
  $("#equiposContainer").empty(); // Limpiar equipos
  $("#tablaServiciosOrden tbody").empty();// Limpiar tabla de servicios en el modal
  $("#totalOrden").text("0.00");
  $("#resumenServicios").html("<em>No hay servicios añadidos.</em>"); // Limpiar resumen lateral
  // Reiniciar variables
  serviciosOrden = [];
  // Cerrar modal si sigue abierto
  const modalServicios = bootstrap.Modal.getInstance($("#modalServiciosOrden")[0]);
  if (modalServicios) modalServicios.hide();

}
//--------------------------------------------------------------------------------------------------------------------


/* var tabla;
$(document).ready(function() {
    $("#formulario").on("submit", function(e) {
    guardaryeditar(e);
    });
    cargarClientes();
    cargarTecnicos();
    cargarServicios();
    cargarEquipos();
    estadoServicio();
    init();
});
//Función que se ejecuta al inicio
function init(){ }
//Función de selectores 
    function cargarClientes(){
        $.post("../ajax/clientes.php?op=selectClientes", function(r){ 
            let opciones = '<option value="">Nombre del Cliente </option>' + r;
                $("#id_cli").html(opciones);// cargamos las opciones
                $('#id_cli').selectpicker('refresh');// refrescamos bootstrap-select
        });
    }
    function cargarTecnicos(){
        $.post("../ajax/tecnicos.php?op=selectTenicos", function(r){ 
            let opciones = '<option value="">Nombre del Técnico </option>' + r;
                $("#id_tec").html(opciones);// cargamos las opciones
                $('#id_tec').selectpicker('refresh');// refrescamos bootstrap-select
        });
    }
    function cargarServicios(){
        $.post("../ajax/categorias.php?op=selectServicios", function(r){ 
            let opciones = '<option value="">Tipo de Servicio </option>' + r;
                $("#id_serv").html(opciones);// cargamos las opciones
                $('#id_serv').selectpicker('refresh');// refrescamos bootstrap-select
        });
    }

    function cargarEquipos(){
        $.post("../ajax/equipos.php?op=selectEquipos", function(r){ 
            let opciones = '<option value="">Marca  -  Capacidad </option>' + r;
                $("#id_equipo").html(opciones);// cargamos las opciones
                $('#id_equipo').selectpicker('refresh');// refrescamos bootstrap-select
        });
    }

    function estadoServicio() {
        const select = document.getElementById('estadoServicio');
        // Inicializar selectpicker
        $(select).selectpicker();
        // Función para cambiar color del botón
        function actualizarColor() {
        const button = select.closest('.bootstrap-select').querySelector('.dropdown-toggle');
        // Limpiar clases previas
        button.classList.remove('btn-danger', 'btn-success', 'btn-light', 'text-white');
            if (select.value === 'Pendiente') {
                button.classList.add('btn-danger', 'text-white'); // rojo
            } else if (select.value === 'Terminado') {
                button.classList.add('btn-success', 'text-white'); // verde
            } else {
                button.classList.add('btn-light'); // color por defecto
            }
        }
        // Ejecutar al cambiar selección
        select.addEventListener('changed.bs.select', actualizarColor);
        // Ejecutar al iniciar para reflejar valor inicial
        actualizarColor();
    }
    // Llamar la función después de que el DOM esté listo
    document.addEventListener('DOMContentLoaded', estadoServicio);


//--------------------SECCION DERECHA VISTA -------------------------------- 
// --- Manejo de equipos ---
const equiposContainer = document.getElementById('equiposContainer');
const saveEquipoBtn = document.getElementById('saveEquipo');

    function createEquipo(equipoId , equipoNombre, serviciosArray) {
        const div = document.createElement('div');
        div.classList.add('equipo-item', 'border', 'rounded', 'p-3', 'mb-3');
        div.dataset.idEquipo = equipoId; // Guarda el ID real del equipo
        div.dataset.servicios = JSON.stringify(serviciosArray.map(s => s.id)); // Guardar IDs de serv
        //const serviciosTexto = serviciosArray.join(', ');

        // Mostrar nombres de los servicios en la UI
        const serviciosTexto = serviciosArray.map(s => s.nombre).join(', ');

        div.innerHTML = `
            <div class="mb-2">
            <strong>Equipo:</strong> ${equipoNombre} <br>
            <strong>Servicios:</strong> ${serviciosTexto}
            </div>
            <a href="#" class="btn btn-info btn-circle btn-sm verDetalles"><i class="fas fa-eye"></i></a>
            <a href="#" class="btn btn-danger btn-circle btn-sm removeEquipo"><i class="fas fa-trash"></i></a>
            `;
        // Botón eliminar
        div.querySelector('.removeEquipo').addEventListener('click', (e) => {
            e.preventDefault();
            div.remove();
            actualizarServiciosOrden(); // Actualiza la tabla cuando se elimina equipo
        });
        // Botón ver detalles
        div.querySelector('.verDetalles').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('detalleEquipo').textContent = equipoNombre;
            document.getElementById('detalleServicios').textContent = serviciosTexto;
            new bootstrap.Modal(document.getElementById('modalDetallesEquipo')).show();
        });
        equiposContainer.appendChild(div);
        actualizarServiciosOrden(); // Actualiza la tabla automáticamente al agregar equipo
    }

    saveEquipoBtn.addEventListener('click', () => {
        const equipoSelect = document.getElementById('id_equipo');
        const serviciosSelect = document.getElementById('id_serv');

        const equipoId = equipoSelect.value; // Aquí tomas el ID
        const equipoNombre = equipoSelect.options[equipoSelect.selectedIndex]?.text || "";
        const serviciosArray = Array.from(serviciosSelect.selectedOptions).map(opt => ({
        id: opt.value,      // 🔹 este es el ID que necesitas
        nombre: opt.text    // opcional, para mostrar en la UI
        }));


            if (equipoNombre && serviciosArray.length > 0) {
                createEquipo(equipoId,equipoNombre, serviciosArray);
                document.getElementById('formAddEquipo').reset();
                $('.selectpicker').selectpicker('refresh');
                bootstrap.Modal.getInstance(document.getElementById('modalAddEquipo')).hide();
            } else {
                alert("Por favor seleccione un equipo y al menos un servicio.");
            }
    });

// --- Manejo de servicios de la orden ---
const btnServiciosOrden = document.getElementById('btnServiciosOrden');
const tablaServiciosOrden = document.getElementById('tablaServiciosOrden').querySelector('tbody');
const addServicioOrdenBtn = document.getElementById('addServicioOrden');
const nuevoServicioInput = document.getElementById('nuevoServicioOrden');

let serviciosOrden = [];

    function refrescarTablaServicios() {
        tablaServiciosOrden.innerHTML = '';
        serviciosOrden.forEach(servicio => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${servicio.nombre}</td>
                <td><input type="number" class="form-control precioServicio" value="${servicio.precio}" min="0"></td>
            `;
            tr.querySelector('.precioServicio').addEventListener('input', (e) => {
                servicio.precio = parseFloat(e.target.value) || 0;
            });
            tablaServiciosOrden.appendChild(tr);
        });
    }

// Consolida servicios de todos los equipos
    function actualizarServiciosOrden() {
        serviciosOrden = [];
        document.querySelectorAll('.equipo-item').forEach(equipo => {
            const serviciosText = equipo.querySelector('div').textContent.match(/Servicios:\s*(.*)/)?.[1]?.split(',').map(s => s.trim());
            if (serviciosText) {
                serviciosText.forEach(s => {
                    if (!serviciosOrden.find(x => x.nombre === s)) {
                        serviciosOrden.push({ nombre: s, precio: 0 });
                    }
                });
            }
        });
        refrescarTablaServicios();
    }

    btnServiciosOrden.addEventListener('click', () => {
        actualizarServiciosOrden();
        new bootstrap.Modal(document.getElementById('modalServiciosOrden')).show();
    });

    addServicioOrdenBtn.addEventListener('click', () => {
        const nuevoServicio = nuevoServicioInput.value.trim();
        if (nuevoServicio && !serviciosOrden.find(s => s.nombre === nuevoServicio)) {
            serviciosOrden.push({ nombre: nuevoServicio, precio: 0 });
            refrescarTablaServicios();
            nuevoServicioInput.value = '';
        }
    });
//-------------------- FIN SECCION DERECHA VISTA -------------------------------- 

    function guardaryeditar(e)
    {
        e.preventDefault(); 
        $("#btnGuardar").prop("disabled",true);
        var formData = new FormData($("#formulario")[0]);
        // Añadir equipos y servicios seleccionados
        const equipos = [];

        document.querySelectorAll('.equipo-item').forEach(div => {
            const id_equipo = div.dataset.idEquipo; // 🔹 Recuperamos el ID guardado
            //const servicios = div.querySelector('div').textContent.match(/Servicios:\s*(.*)/)?.[1]?.split(',').map(s => s.trim());
            const servicios = JSON.parse(div.dataset.servicios); 
            equipos.push({ id_equipo: id_equipo, servicios: servicios });
        });

        formData.append("equipos", JSON.stringify(equipos));
        formData.append("serviciosOrden", JSON.stringify(serviciosOrden));
        console.log("Equipos enviados:", equipos);

        $.ajax({
        url: "../ajax/ordenes.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos)  {        
      
            Swal.fire({
                title: '<span style="font-size: 24px;">'+datos+'</span>',
                icon: "success",
                width: '600px',
                customClass: {
                        popup: "mi-alerta-personalizada",
                    confirmButton: 'swal2-confirm'
                },
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    confirmButton.style.fontSize = '18px'; 
                    confirmButton.style.padding = '10px 24px';
                }
            }); ;	          
                if (typeof tabla !== 'undefined' && tabla !== null) {
                    tabla.ajax.reload();
                }
            }

        });
        limpiar();
    }

    // Limpiar formulario
    function limpiar() {
        $("#id_orden").val("");
        $("#fecha").val("");
        $("#id_cli").val("").selectpicker('refresh');
        $("#id_tec").val("").selectpicker('refresh');
        $("#direccion").val("");
        $("#observaciones").val("");
        $("#tipo_pago").val("").selectpicker('refresh');
        $("#estadoServicio").val("").selectpicker('refresh');
    }

init(); */