$(document).ready(async () => {
      agg_edit_eli(); // Detecta si es nuevo o editar
      // Esperamos que se carguen todos los selects
      await Promise.all([
        cargarOpciones("Clientes"),
        cargarOpciones("Tecnicos"),
        cargarOpciones("Servicios"),
        cargarOpciones("Equipos")
      ]);
      // Luego cargamos el detalle (ya seguro los selects existen)
      const idOrden = $("#id_orden").val();
      if (idOrden) {
        cargarDetalleOrden(idOrden);
      }
      //estadoServicio();
});
// ------------------------------- CARGA DE SELECTS (una sola función) -----------------------------
function cargarOpciones(tipo) {
    return new Promise((resolve, reject) => {
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
      $.post(urls[tipo])
        .done((r) => {
          const textoBase = {
            Clientes: "Nombre del Cliente",
            Tecnicos: "Nombre del Técnico",
            Servicios: "Tipos de Servicios",
            Equipos: "Marca - Capacidad"
          }[tipo];

          $(ids[tipo]).html(`<option value="">${textoBase}</option>${r}`).selectpicker("refresh");
          resolve();
        })
        .fail((err) => reject(err));
    });
}
// ------------------------------ COLOR AUTOMÁTICO DEL ESTADO ------------------------------
// function estadoServicio() {
//   const select = $("#estadoServicio");
//   select.selectpicker();

//   const actualizarColor = () => {
//     const btn = select.closest(".bootstrap-select").find(".dropdown-toggle");
//     btn.removeClass("btn-danger btn-success btn-light text-white");
//     if (select.val() === "Pendiente") btn.addClass("btn-danger text-white");
//     else if (select.val() === "Terminado") btn.addClass("btn-success text-white");
//     else btn.addClass("btn-light");
//   };

//   select.on("changed.bs.select", actualizarColor);
//   actualizarColor();
// }
// ------------------------------ MANEJO DE EQUIPOS ------------------------------
const equiposContainer = document.getElementById("equiposContainer");
let serviciosOrden = [];

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

// ------------------------------- MANEJO DE SERVICIOS ORDEN -----------------------------
const tablaServiciosOrden = $("#tablaServiciosOrden tbody");

function actualizarServiciosOrden() {
  const contador = {};

  $(".equipo-item").each(function () {
    const servicios = JSON.parse(this.dataset.servicios);
    servicios.forEach(id_serv => {
      const nombre = $("#id_serv option[value='" + id_serv + "']").text().trim();

      // Si ya existía en serviciosOrden, conservamos su precio
      const existente = serviciosOrden.find(s => s.id === id_serv);

      if (!contador[id_serv]) {
        contador[id_serv] = {
          id: id_serv,
          nombre,
          cantidad: 0,
          precio: existente ? existente.precio : 0
        };
      }

      contador[id_serv].cantidad++;
    });
  });

  serviciosOrden = Object.values(contador);
  refrescarTablaServicios();
}

//--------------------------------------------------------------------
function refrescarTablaServicios() {
  const tbody = $("#tablaServiciosOrden tbody");
  tbody.html("");
  let total = 0;

  serviciosOrden.forEach(s => {
    // Si el precio aún no está definido (undefined o null), lo inicializa en 0
    if (s.precio == null || isNaN(s.precio)) s.precio = 0;

    const tr = $(`
      <tr>
        <td>${s.nombre}</td>
        <td>${s.cantidad}</td>
        <td>
          <input type="number" 
                 class="form-control precioServicio" 
                 value="${s.precio.toFixed(2)}" 
                 min="0" 
                 step="0.01">
        </td>
      </tr>
    `);

    tr.find(".precioServicio").on("input", e => {
      s.precio = parseFloat(e.target.value) || 0;
      calcularTotalOrden();
    });

    tbody.append(tr);
    total += s.precio;
  });

  $("#totalOrden").text(total.toFixed(2));
}

function calcularTotalOrden() {
  let total = 0;
  serviciosOrden.forEach(s => total += s.precio);
  $("#totalOrden").text(total.toFixed(2));
}

// ----------------------------------- ITEMS COBROS -----------------------------------
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
// boton captura el valor  de los servicio unificado para distribuirlos y mostrarlos
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
//------------------------------------------------------------------------------------------
$("#btnServiciosOrden").on("click", () => {
  // Si ya hay servicios cargados (por ejemplo, desde BD), solo refrescamos
  if (serviciosOrden.length > 0) {
    refrescarTablaServicios();
  } else {
    // Si no hay nada cargado (caso nuevo), genera los servicios desde los equipos
    actualizarServiciosOrden();
  }
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


/* function guardarOrden() {
  if (!equipos.length) {
    alert("Debe agregar al menos un equipo.");
    return;
  }

  if (!serviciosOrden.length) {
    alert("Debe seleccionar al menos un servicio.");
    return;
  }

  // Envío seguro
  $.post("../ajax/ordenes.php?op=guardaryeditar", {
    id_orden: $("#id_orden").val(),
    id_cli: $("#id_cli").val(),
    id_tec: $("#id_tec").val(),
    fecha: $("#fecha").val(),
    direccion: $("#direccion").val(),
    tipo_pago: $("#tipo_pago").val(),
    observaciones: $("#observaciones").val(),
    equipos: JSON.stringify(equipos),
    serviciosOrden: JSON.stringify(serviciosOrden)
  }, function(respuesta) {
    console.log("Respuesta del servidor:", respuesta);
  });
}
 */
// ------------------------- GUARDAR ORDEN -----------------------------------
function guardaryeditar(e) {
  e.preventDefault();
  console.log("ID de orden al guardar:", $("#id_orden").val());
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
   // Crear formData
    const formData = new FormData($("#formulario")[0]);
    // const equipos = $(".equipo-item").map((_, div) => ({
    //   id_equipo: div.dataset.idEquipo,
    //   servicios: JSON.parse(div.dataset.servicios)
    // })).get();
    const equipos = buildEquiposForSubmit();
      // vuelca en consola para depuración
      console.log("Payload a enviar:");
      console.log({ 
        id_orden: $("#id_orden").val(),
        id_cli: $("#id_cli").val(),
        id_tec: $("#id_tec").val(),
        fecha: $("#fecha").val(),
        direccion: $("#direccion").val(),
        tipo_pago: $("#tipo_pago").val(),
        observaciones: $("#observaciones").val(),
        equipos: equipos,
        serviciosOrden: serviciosOrden
      });

  formData.append("equipos", JSON.stringify(equipos));
  formData.append("serviciosOrden", JSON.stringify(serviciosOrden));
  console.log("Datos enviados:", { equipos, serviciosOrden });

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


function buildEquiposForSubmit() {
  const equipos = [];

  // recorremos cada equipo DOM (uno por bloque creado con createEquipo)
  $("#equiposContainer .equipo-item").each(function() {
    const id_equipo = $(this).data("idequipo") || $(this).data("idEquipo") || $(this).data("id-equipo");
    // dataset.servicios es string JSON; parsearlo
    let servicios = [];
    try {
      servicios = JSON.parse(this.dataset.servicios || "[]");
    } catch (err) {
      servicios = [];
    }

    // asegurar que servicios sean números (o strings, según tu backend)
    servicios = servicios.map(s => typeof s === "string" ? s : String(s));

    equipos.push({
      id_equipo: id_equipo,    // id real del equipo
      servicios: servicios    // array de ids de servicios
    });
  });

  return equipos;
}


 // ------------------------ PRESENTA LOS DATOS DE VISUALIZAR ------------------------
function cargarDetalleOrden(idOrden) {
  $.getJSON("../ajax/agenda.php?op=obtenerDetalleOrden&id_orden=" + idOrden, function(response) {
    const detalle = response.detalle;
    console.log(detalle)
    if (!detalle || !detalle.length) return;

    // ------------------------ CAMPOS GENERALES ------------------------
    $("#id_orden").val(detalle[0].id_orden);
    $("#fecha").val(detalle[0].fecha);
    $("#id_cli").val(detalle[0].id_cliente).selectpicker('refresh');
    $("#id_tec").val(detalle[0].id_usuarios).selectpicker('refresh');
    $("#direccion").val(detalle[0].direccion);
    $("#observaciones").val(detalle[0].observaciones);
    $("#tipo_pago").val(detalle[0].tipo_pago).selectpicker('refresh');
    $("#estadoServicio").val(detalle[0].estado_orden).selectpicker('refresh');

    // ------------------------ LIMPIAR ------------------------
    $("#equiposContainer").empty();
    serviciosOrden = [];

    // ------------------------ AGRUPAR POR EQUIPO (respetando duplicados) ------------------------
    const equiposMap = {};
    detalle.forEach(item => {
      const key = item.id_equipo_orden; // 👈 agrupamos por id_equipo_orden, no por id_equipo

      if (!equiposMap[key]) {
        equiposMap[key] = {
          id_equipo_orden: item.id_equipo_orden,
          id: item.id_equipo,
          nombre: `${item.marca} ${item.modelo}`,
          servicios: []
        };
      }

      equiposMap[key].servicios.push({
        id_detalle: item.id_detalle_orden,
        id: item.id_servicio,
        nombre: item.servicio,
        precio: parseFloat(item.valor)
      });
    });


    // ------------------------ CREAR VISUAL DE CADA EQUIPO ------------------------
    Object.values(equiposMap).forEach(eq => {
      createEquipo(eq.id, eq.nombre, eq.servicios);
      //createEquipo(eq.id_equipo_orden, eq.nombre, eq.servicios);
    });

    // ------------------------ UNIFICAR SERVICIOS REPETIDOS ------------------------
    const contador = {};
    Object.values(equiposMap).forEach(eq => {
      eq.servicios.forEach(s => {
        if (!contador[s.id]) {
          contador[s.id] = { 
            id: s.id, 
            nombre: s.nombre, 
            cantidad: 0, 
            precio: 0 
          };
        }
        contador[s.id].cantidad++;
        contador[s.id].precio += s.precio;
      });
    });

    serviciosOrden = Object.values(contador);

    // ------------------------ REFRESCAR MODAL Y RESUMEN ------------------------
    refrescarTablaServicios();
    mostrarResumenServicios();
  });
}

























//  // ------------------------ PRESENTA LOS DATOS DE VISUALIZAR ------------------------
// function cargarDetalleOrden(idOrden) {
//   $.getJSON("../ajax/agenda.php?op=obtenerDetalleOrden&id_orden=" + idOrden, function(response) {
//     const detalle = response.detalle;
//     console.log(detalle)
//     if (!detalle || !detalle.length) return;

//     // ------------------------ CAMPOS GENERALES ------------------------
//     $("#id_orden").val(detalle[0].id_orden);
//     $("#fecha").val(detalle[0].fecha);
//     $("#id_cli").val(detalle[0].id_cliente).selectpicker('refresh');
//     $("#id_tec").val(detalle[0].id_usuarios).selectpicker('refresh');
//     $("#direccion").val(detalle[0].direccion);
//     $("#observaciones").val(detalle[0].observaciones);
//     $("#tipo_pago").val(detalle[0].tipo_pago).selectpicker('refresh');
//     $("#estadoServicio").val(detalle[0].estado_orden).selectpicker('refresh');

//     // ------------------------ LIMPIAR ------------------------
//     $("#equiposContainer").empty();
//     serviciosOrden = [];

//     // ------------------------ AGRUPAR POR EQUIPO (respetando duplicados) ------------------------
//     const equiposMap = {};
//     detalle.forEach(item => {
//       const key = item.id_equipo_orden; // 👈 agrupamos por id_equipo_orden, no por id_equipo

//       if (!equiposMap[key]) {
//         equiposMap[key] = {
//           id_equipo_orden: item.id_equipo_orden,
//           id: item.id_equipo,
//           nombre: `${item.marca} ${item.modelo}`,
//           servicios: []
//         };
//       }

//       equiposMap[key].servicios.push({
//         id_detalle: item.id_detalle_orden,
//         id: item.id_servicio,
//         nombre: item.servicio,
//         precio: parseFloat(item.valor)
//       });
//     });


//     // ------------------------ CREAR VISUAL DE CADA EQUIPO ------------------------
//     Object.values(equiposMap).forEach(eq => {
//       //createEquipo(eq.id, eq.nombre, eq.servicios);
//       createEquipo(eq.id_equipo_orden, eq.nombre, eq.servicios);
//     });

//     // ------------------------ UNIFICAR SERVICIOS REPETIDOS ------------------------
//     const contador = {};
//     Object.values(equiposMap).forEach(eq => {
//       eq.servicios.forEach(s => {
//         if (!contador[s.id]) {
//           contador[s.id] = { 
//             id: s.id, 
//             nombre: s.nombre, 
//             cantidad: 0, 
//             precio: 0 
//           };
//         }
//         contador[s.id].cantidad++;
//         contador[s.id].precio += s.precio;
//       });
//     });

//     serviciosOrden = Object.values(contador);

//     // ------------------------ REFRESCAR MODAL Y RESUMEN ------------------------
//     refrescarTablaServicios();
//     mostrarResumenServicios();
//   });
// }






















// function cargarDetalleOrden(idOrden) {
//   $.getJSON("../ajax/agenda.php?op=obtenerDetalleOrden&id_orden=" + idOrden, function(response) {
//     const detalle = response.detalle;
//     if (!detalle || !detalle.length) return;

//     // ------------------------ CAMPOS GENERALES ------------------------
//     $("#id_orden").val(detalle[0].id_orden);
//     $("#fecha").val(detalle[0].fecha);
//     $("#id_cli").val(detalle[0].id_cliente).selectpicker('refresh');
//     $("#id_tec").val(detalle[0].id_usuarios).selectpicker('refresh');
//     $("#direccion").val(detalle[0].direccion);
//     $("#observaciones").val(detalle[0].observaciones);
//     $("#tipo_pago").val(detalle[0].tipo_pago).selectpicker('refresh');
//     $("#estadoServicio").val(detalle[0].estado_orden).selectpicker('refresh');

//     // ------------------------ LIMPIAR ------------------------
//     $("#equiposContainer").empty();
//     serviciosOrden = [];

//     // ------------------------ AGRUPAR POR EQUIPO (respetando duplicados) ------------------------
//     const equiposMap = {};
//     detalle.forEach(item => {
//       const key = item.id_equipo_orden; // agrupamos por id_equipo_orden

//       if (!equiposMap[key]) {
//         equiposMap[key] = {
//           id_equipo_orden: item.id_equipo_orden,
//           id: item.id_equipo,
//           nombre: `${item.marca} ${item.modelo}`,
//           servicios: []
//         };
//       }

//       equiposMap[key].servicios.push({
//         id_detalle: item.id_detalle_orden,
//         id: item.id_servicio,
//         nombre: item.servicio,
//         precio: parseFloat(item.valor)
//       });
//     });

//     // ------------------------ CREAR VISUAL DE CADA EQUIPO ------------------------
//     Object.values(equiposMap).forEach(eq => {
//       // 👇 usar el id_equipo_orden, no el id_equipo
//       createEquipo(eq.id_equipo_orden, eq.nombre, eq.servicios);
//     });

//     // ------------------------ UNIFICAR SERVICIOS REPETIDOS ------------------------
//     const contador = {};
//     Object.values(equiposMap).forEach(eq => {
//       eq.servicios.forEach(s => {
//         if (!contador[s.id]) {
//           contador[s.id] = { 
//             id: s.id, 
//             nombre: s.nombre, 
//             cantidad: 0, 
//             precio: 0 
//           };
//         }
//         contador[s.id].cantidad++;
//         contador[s.id].precio += s.precio;
//       });
//     });

//     serviciosOrden = Object.values(contador);

//     // ------------------------ REFRESCAR MODAL Y RESUMEN ------------------------
//     refrescarTablaServicios();
//     mostrarResumenServicios();
//   });
// }


function agg_edit_eli(){
    const idOrden = $("#id_orden").val();
      if (idOrden) {
          // Modo edición
          cargarDetalleOrden(idOrden);
          $("#btnGuardar").text("Actualizar");
      } else {
          // Modo creación
          $("#btnGuardar").text("Guardar");
      }
    // 💡 Evita múltiples bindings
    $("#formulario").off("submit").on("submit", function(e) {
      e.preventDefault();
      guardaryeditar(e);
    });
}
