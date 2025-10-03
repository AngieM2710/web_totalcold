<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1) {
        ?>
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Formulario Agregar Servicio -->
                <div class="col-lg-7 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="mb-4">Agregar Nuevo Servicio</h4>
                            <form>
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Cliente:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select id="id_cli" name="id_cli" class="form-control selectpicker" data-live-search="true" style="width: 100%;" required></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Técnico(s) Asignado:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select id="id_tec" name="id_tec" class="form-control selectpicker" data-live-search="true" style="width: 100%;" required></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label">Fecha y Hora</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="datetime-local" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Estado del Servicio Técnico :</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="form-control selectpicker" id="estadoServicio">
                                                <option value="">Seleccione...</option>
                                                <option value="Pendiente">Pendiente</option>
                                                <option value="Terminado">Terminado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <input type="text" class="form-control" placeholder="Utilidad soporte">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-sm btn-warning" id="btnServiciosOrden">Items de Cobro</button> &nbsp;
                                    <button type="button" class="btn btn-outline-secondary me-2">Cancelar</button>&nbsp;
                                    <button type="submit" class="btn btn-primary">Guardar Servicio</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
 
                <!-- Panel lateral derecho -->
                <div class="col-lg-5">
                    <!-- Agenda del día -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="mb-3">Agenda del Día</h6>
                            <input type="date" class="form-control mb-3">
                            <ul class="list-group">
                                <li class="list-group-item">9:00 AM - Juan Ortega - Pendiente</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Detalle Equipos -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="mb-3">Detalle Equipos - Servicios</h6>
                            <div id="equiposContainer"></div>
                            <div class="mt-2 d-flex gap-2">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddEquipo">+ Agregar Equipo</button>
                            </div>
                        </div>
                    </div>

                    <!-- Fotos de instalación -->
                    <!-- <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3">Fotos de Instalación</h6>
                            <button class="btn btn-sm btn-outline-secondary">+ Subir Fotos</button>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Modal Agregar Equipo -->
        <div class="modal fade" id="modalAddEquipo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Equipo al Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAddEquipo">
                            <div class="mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0">Equipos:</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select id="id_equipo" name="id_equipo" class="form-control selectpicker" data-live-search="true" required></select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0">Servicios:</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select id="id_serv" name="id_serv[]" class="form-control selectpicker" multiple data-live-search="true" required></select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="saveEquipo" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detalles de Equipo -->
        <div class="modal fade" id="modalDetallesEquipo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles Equipo - Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Equipo:</strong> <span id="detalleEquipo"></span></p>
                        <p><strong>Servicios:</strong> <span id="detalleServicios"></span></p>
                        <!-- Aquí puedes agregar más detalles si es necesario -->
                        <div class="card-body">
                            <h6 class="mb-3">Fotos de Instalación</h6>
                            <button class="btn btn-sm btn-outline-secondary">+ Subir Fotos</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Servicios de la Orden -->
        <div class="modal fade" id="modalServiciosOrden" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Servicios de la Orden</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="tablaServiciosOrden">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Precio</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="input-group mt-2">
                            <input type="text" id="nuevoServicioOrden" class="form-control" placeholder="Agregar nuevo servicio">
                            <button class="btn btn-primary" id="addServicioOrden">Agregar</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
?>

<script type="text/javascript" src="scripts/Agendar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// --- Manejo de equipos ---
const equiposContainer = document.getElementById('equiposContainer');
const saveEquipoBtn = document.getElementById('saveEquipo');

function createEquipo(equipoNombre, serviciosArray) {
    const div = document.createElement('div');
    div.classList.add('equipo-item', 'border', 'rounded', 'p-3', 'mb-3');

    const serviciosTexto = serviciosArray.join(', ');
    div.innerHTML = `
        <div class="mb-2">
            <strong>Equipo:</strong> ${equipoNombre} <br>
            <strong>Servicios:</strong> ${serviciosTexto}
        </div>
        <a href="#" class="btn btn-info btn-circle btn-sm verDetalles"><i class="fas fa-eye"></i></a>
        <a href="#" class="btn btn-danger btn-circle btn-sm removeEquipo"><i class="fas fa-trash"></i></a>
    `;

    div.querySelector('.removeEquipo').addEventListener('click', (e) => {
        e.preventDefault();
        div.remove();
        actualizarServiciosOrden(); // Actualiza la tabla cuando se elimina equipo
    });

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

    const equipoNombre = equipoSelect.options[equipoSelect.selectedIndex]?.text || "";
    const serviciosArray = Array.from(serviciosSelect.selectedOptions).map(opt => opt.text);

    if (equipoNombre && serviciosArray.length > 0) {
        createEquipo(equipoNombre, serviciosArray);
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
            <td><button class="btn btn-danger btn-sm removeServicio">Eliminar</button></td>
        `;
        tr.querySelector('.removeServicio').addEventListener('click', () => {
            serviciosOrden = serviciosOrden.filter(s => s.nombre !== servicio.nombre);
            refrescarTablaServicios();
        });
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
</script>

<?php
}
ob_end_flush();
?>
