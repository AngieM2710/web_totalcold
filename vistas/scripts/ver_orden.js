/**
 * Opciones de estado para la ORDEN principal.
 */
const OPCIONES_ESTADO_ORDEN = [
    { valor: 0, nombre: 'Pendiente', clase: 'bg-danger' },
    { valor: 1, nombre: 'Terminado', clase: 'bg-success' },
    { valor: 2, nombre: 'En Proceso', clase: 'bg-warning' }
];

// Variable global para almacenar los datos de la orden y rastrear los cambios
let ordenDataGlobal = {}; 


// =========================================================================
// FUNCIÓN DE INICIALIZACIÓN Y CARGA DE DATOS
// =========================================================================

function init(){
    // La función init aquí se encargaría de obtener el ID de la orden
    const id_orden = obtenerIdOrdenDesdeURL(); 

    if (id_orden) {
        cargarOrden(id_orden); // Inicia el proceso de carga y renderizado
    } else {
        $("#mainContainer").html("<div class='alert alert-danger'>Error: No se ha especificado un ID de orden.</div>");
    }
}


function cargarOrden(id_orden) {
    // Usamos la ruta corregida que mencionó anteriormente
    $.post("../ajax/ordenes.php?op=obtener_orden_completa", { id_orden: id_orden }, function(response) {
        try {
            const data = JSON.parse(response);
            if (data.success) {
                renderOrden(data.data);
            } else {
                $("#mainContainer").html(`<div class='alert alert-danger'>${data.message}</div>`);
            }
        } catch (e) {
            console.error("Error al parsear JSON:", e);
            $("#mainContainer").html("<div class='alert alert-danger'>Error de datos del servidor.</div>");
        }
    });
}

function obtenerIdOrdenDesdeURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}


// =========================================================================
// RENDERIZADO DE LA INTERFAZ
// =========================================================================

function inicializarOrdenGlobal(data) {
    ordenDataGlobal = data; 
    ordenDataGlobal.equipos = ordenDataGlobal.equipos.map(eq => {
        // Asumimos que podemos tener observaciones previas por equipo
        eq.observaciones_actual = eq.observaciones_equipo || ''; 

        eq.servicios = eq.servicios.map(sv => {
            // Usamos estado_actual para rastrear cambios en el servicio
            sv.estado_actual = sv.estado; 
            return sv;
        });
        return eq;
    });
}


function renderOrden(data) {
    inicializarOrdenGlobal(data); 

    const orden = ordenDataGlobal.orden;
    const equipos = ordenDataGlobal.equipos;
    
    // ----------------------------------------------------------------------
    // 1. INFO DEL CLIENTE y SELECT DE ESTADO GENERAL
    // ----------------------------------------------------------------------
    
    const estadoInfo = OPCIONES_ESTADO_ORDEN.find(op => op.valor == orden.estado) || { nombre: 'Desconocido', clase: 'bg-secondary' };
    const estadoBadge = `<span class="badge ${estadoInfo.clase} text-white ms-3">${estadoInfo.nombre}</span>`;

    let clienteHtml = `
        <h5 class="text-info mb-3">
            <i class="fas fa-user"></i> Información del Cliente 
        </h5>
        ${estadoBadge}
        <p class="mb-1"><strong>Cliente:</strong> ${orden.cliente} </p>
        <p class="mb-1"><strong>Dirección:</strong> ${orden.direccion}</p>
        <p class="mb-1"><strong>Fecha:</strong> ${orden.fecha}</p>
        <p class="mb-1"><strong>Tipo de Pago:</strong> ${orden.tipo_pago}</p>
        <p class="mb-1"><strong>Costo Total:</strong> $${orden.costos}</p>

        <div class="mt-3 mb-4">
            <strong>Observaciones Generales:</strong>
            <p class="text-muted small">${orden.observaciones || 'Sin observaciones.'}</p>
        </div>
        
        <div class="form-group mb-4 border-top pt-3">
            <label for="selectEstadoOrden" class="fw-bold text-dark">Cambiar Estado de la Orden:</label>
            <select id="selectEstadoOrden" class="form-control" onchange="actualizarEstadoOrdenLocal(this.value)">
    `;

    // Renderizar opciones del SELECT
    OPCIONES_ESTADO_ORDEN.forEach(opcion => {
        const seleccionado = (opcion.valor == orden.estado) ? 'selected' : '';
        clienteHtml += `<option value="${opcion.valor}" ${seleccionado}>${opcion.nombre}</option>`;
    });

    clienteHtml += `
            </select>
            <input type="hidden" id="ordenId" value="${orden.id_orden}"> 
        </div>

      <div class="mt-4 **d-flex flex-column align-items-center w-100**">
    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg btn-volver **mb-2**">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
    <button id="btnGuardarOrden" class="btn btn-primary btn-lg" onclick="guardarOrdenCompleta(${orden.id_orden})">
        <i class="fas fa-save"></i> Guardar
    </button>
</div>
    `;
    $("#infoClienteContainer").html(clienteHtml);

    // ----------------------------------------------------------------------
    // 2. RENDERIZAR DETALLE DE SERVICIOS (Doble columna para documentación)
    // ----------------------------------------------------------------------
   let serviciosHtml = `
    <h5 class="text-info mb-3"><i class="fas fa-tools"></i> Servicios Asignados</h5>
`;

if (equipos.length > 0) {
    equipos.forEach((eq, index) => {
        const obsActualesEquipo = eq.observaciones_actual; 

        serviciosHtml += `
            <div class="mb-4 border rounded p-3 bg-light">
                <h6 class="fw-bold mb-2">
                    Equipo ${index + 1} – Marca: ${eq.marca} (${eq.modelo})
                </h6>

                <div class="row"> 
                    
                    <div class="col-md-5 border-end"> 
                        <h6 class="text-muted small mb-2">Tareas y Servicios</h6>
                        <ul class="list-unstyled mb-0">
        `;
        
        if (eq.servicios.length > 0) {
            eq.servicios.forEach(sv => {
                const marcado = (sv.estado_actual == 1) ? 'checked' : '';
                const checkboxId = `servicio_${sv.id_equipo_servicio}`;

                serviciosHtml += `
                    <li class="d-flex justify-content-between align-items-center py-1"> 
                        <div class="form-check d-flex align-items-center">
                            <input 
                                type="checkbox" 
                                id="${checkboxId}"
                                class="form-check-input me-2" 
                                ${marcado} 
                                onchange="actualizarEstadoServicioLocal(${eq.id_equipo_orden}, ${sv.id_equipo_servicio}, this.checked)" 
                            >
                            <label for="${checkboxId}" class="mb-0 text-dark small">
                                ${sv.nombre_servicio} 
                            </label>
                        </div>
                    </li>
                `;
            });
        } else {
             serviciosHtml += `<li class="py-1 text-muted small">Sin servicios asignados.</li>`;
        }
        
        serviciosHtml += `
                        </ul>
                    </div> 
                    <div class="col-md-7"> 

                        <div class="form-group mb-3">
                            <label for="obs_${eq.id_equipo_orden}" class="small fw-bold mb-1">Observaciones</label>
                            <textarea 
                                id="obs_${eq.id_equipo_orden}" 
                                class="form-control form-control-sm" 
                                rows="2" 
                                placeholder="Escribe tus observaciones del trabajo realizado..."
                                onchange="actualizarObservacionesLocal(${eq.id_equipo_orden}, this.value)"
                            >${obsActualesEquipo}</textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1">Evaporador</label>
                                <button type="button" class="btn btn-outline-secondary w-100 btn-sm" onclick="$('#fotoEvaporador_${eq.id_equipo_orden}').click();">Subir Foto</button>
                                <input type="file" class="d-none" id="fotoEvaporador_${eq.id_equipo_orden}" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold mb-1">Compresor</label>
                                <button type="button" class="btn btn-outline-secondary w-100 btn-sm" onclick="$('#fotoCompresor_${eq.id_equipo_orden}').click();">Subir Foto</button>
                                <input type="file" class="d-none" id="fotoCompresor_${eq.id_equipo_orden}" accept="image/*">
                            </div>
                        </div>

                        <div class="form-group">
                             <label class="small fw-bold mb-1">Fotos adicionales</label>
                             <button type="button" class="btn btn-outline-primary w-100 btn-sm" onclick="$('#fotosAdicionales_${eq.id_equipo_orden}').click();">+ Subir Fotos</button>
                             <input type="file" class="d-none" id="fotosAdicionales_${eq.id_equipo_orden}" multiple accept="image/*">
                        </div>

                    </div>
                    </div>
                </div>
        `;
    });
} else {
    serviciosHtml += `<div class='alert alert-warning'>No hay equipos registrados para esta orden.</div>`;
}
    $("#serviciosContainer").html(serviciosHtml);
}

// =========================================================================
// FUNCIONES LOCALES DE ACTUALIZACIÓN (Solo modifican la variable JS)
// =========================================================================

function actualizarEstadoOrdenLocal(nuevo_estado) {
    ordenDataGlobal.orden.estado = parseInt(nuevo_estado); 
    console.log(`[LOCAL] Estado de la ORDEN cambiado a: ${nuevo_estado}`);
}

function actualizarObservacionesLocal(id_equipo_orden, nuevo_obs) {
    const equipo = ordenDataGlobal.equipos.find(eq => eq.id_equipo_orden == id_equipo_orden);
    if (equipo) {
        equipo.observaciones_actual = nuevo_obs; 
        console.log(`[LOCAL] Observaciones del Equipo ${id_equipo_orden} actualizadas.`);
    } else {
        console.error(`[LOCAL] No se pudo encontrar el equipo ${id_equipo_orden} para actualizar las observaciones.`);
    }
}


function actualizarEstadoServicioLocal(id_equipo_orden, id_equipo_servicio, is_checked) {
    const nuevo_estado = is_checked ? 1 : 0;
    
    const equipo = ordenDataGlobal.equipos.find(eq => eq.id_equipo_orden == id_equipo_orden);
    if (equipo) {
        const servicio = equipo.servicios.find(sv => sv.id_equipo_servicio == id_equipo_servicio);
        if (servicio) {
            servicio.estado_actual = nuevo_estado;
            console.log(`[LOCAL] Servicio ${id_equipo_servicio} actualizado a: ${nuevo_estado}`);
            return;
        }
    }
    console.error(`[LOCAL] No se pudo encontrar el servicio ${id_equipo_servicio} para actualizar.`);
}


// =========================================================================
// FUNCIÓN FINAL DE GUARDADO (Recolección de datos y AJAX con FormData)
// =========================================================================

function guardarOrdenCompleta(id_orden) {
    $("#btnGuardarOrden").prop("disabled",true);

    const formData = new FormData();
    formData.append('op', 'guardar_cambios_orden_completa');
    formData.append('id_orden', id_orden);
    formData.append('estado_orden', ordenDataGlobal.orden.estado); 

    let serviciosActualizados = [];
    let equiposActualizados = [];

    ordenDataGlobal.equipos.forEach(eq => {
        // 1. Recolección de datos por equipo (Observaciones)
        equiposActualizados.push({
            id_equipo_orden: eq.id_equipo_orden,
            // Asumimos que la observación del equipo también se actualizará en la DB
            observaciones: $(`#obs_${eq.id_equipo_orden}`).val() 
        });

        // 2. Recolección de datos de servicios
        eq.servicios.forEach(sv => {
            serviciosActualizados.push({
                id_equipo_servicio: sv.id_equipo_servicio,
                estado: sv.estado_actual
            });
        });

        // 3. Recolección de ARCHIVOS (MUY IMPORTANTE)
        const archivos = ['fotoEvaporador', 'fotoCompresor', 'fotosAdicionales'];
        
        archivos.forEach(nombreCampo => {
            const inputElement = $(`#${nombreCampo}_${eq.id_equipo_orden}`)[0];
            if (inputElement && inputElement.files.length > 0) {
                 // Si es múltiple (fotosAdicionales), iteramos sobre ellos
                if (nombreCampo === 'fotosAdicionales') {
                    for (let i = 0; i < inputElement.files.length; i++) {
                        // El [] en el nombre del campo es crucial para PHP
                        formData.append(`${nombreCampo}_${eq.id_equipo_orden}[]`, inputElement.files[i]);
                    }
                } else {
                    // Si es un solo archivo (Evaporador, Compresor)
                    formData.append(`${nombreCampo}_${eq.id_equipo_orden}`, inputElement.files[0]);
                }
            }
        });

    });

    // 4. Agregar los arrays de datos al FormData como JSON strings
    formData.append('equipos_json', JSON.stringify(equiposActualizados)); 
    formData.append('servicios_json', JSON.stringify(serviciosActualizados));


    // 5. Ejecutar la llamada AJAX
    $.ajax({
        url: "../ajax/ordenes.php", 
        type: "POST",
        data: formData, // Enviamos el objeto FormData
        contentType: false, // Desactiva jQuery para establecer el Content-Type
        processData: false, // Evita que jQuery procese los datos
        success: function(response) {
            $("#btnGuardarOrden").prop("disabled", false);

            const res = JSON.parse(response);
            
            if (res.success) {
                 Swal.fire({
                    title: '<span style="font-size: 24px;">Orden guardada y actualizada!</span>',
                    icon: "success",
                    width: '600px'
                 });
                 // Recarga la vista
                 cargarOrden(id_orden); 
            } else {
                 Swal.fire({
                    title: '<span style="font-size: 24px;">Error al guardar la orden!</span>',
                    text: res.message,
                    icon: "error",
                    width: '600px'
                 });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $("#btnGuardarOrden").prop("disabled", false);
            console.error("Error AJAX:", textStatus, errorThrown, jqXHR.responseText);
            Swal.fire({
                title: '<span style="font-size: 24px;">Error de conexión o servidor!</span>',
                text: 'Revise la consola para detalles del error.',
                icon: "error",
                width: '600px'
             });
        }
    });
}


// =========================================================================
// EJECUCIÓN DEL SCRIPT
// =========================================================================
init();