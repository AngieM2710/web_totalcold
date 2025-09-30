<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1) {
?>

<style>
    body {
        background-color: #f8f9fa;
    }
    .sidebar {
        min-height: 100vh;
        background: #4b7bec;
        color: #fff;
    }
    .sidebar a {
        color: #fff;
        text-decoration: none;
    }
    .sidebar a.active,
    .sidebar a:hover {
        background-color: #3867d6;
    }
    .card-status {
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.35rem;
        font-weight: 500;
    }
    .pendiente { background-color: #5dade2; color: #fff; }
    .encurso { background-color: #f39c12; color: #fff; }
    .terminado { background-color: #27ae60; color: #fff; }
    #calendario {
        height: 300px;
        background-color: #f1f1f1;
        border-radius: 0.35rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        font-weight: bold;
        font-size: 1.2rem;
    }
</style>

<div class="container-fluid">
    <div class="row">

        <!-- Parte Izquierda: Lista y Tabla -->
        <div class="col-lg-8 p-4">
            <h3 class="mb-4 text-gray-800 letrastitulo">Lista de Citas Programadas</h3>
            
            <div class="card shadow mb-4 contendorprinc">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs mb-0" id="tabCitas" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="lista-tab" data-bs-toggle="tab" data-bs-target="#lista" type="button" role="tab">Lista de Citas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tabla-tab" data-bs-toggle="tab" data-bs-target="#tabla" type="button" role="tab">Vista de Tabla</button>
                        </li>
                    </ul>
                    <a href="Agendar.php"class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
                    </a>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="tabCitasContent">
                        <!-- Lista de Citas -->
                        <div class="tab-pane fade show active" id="lista" role="tabpanel">
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    09:00 AM - Cliente: Juan Pérez - Mantenimiento
                                    <span class="card-status pendiente">Pendiente</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    11:30 AM - Cliente: Laura Rodríguez - Instalación
                                    <span class="card-status encurso">En Curso</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    02:00 PM - Cliente: Carlos Serviguz - Instalación
                                    <span class="card-status terminado">Terminado</span>
                                </div>
                            </div>
                        </div>

                        <!-- Vista de Tabla -->
                        <div class="tab-pane fade" id="tabla" role="tabpanel">
                            <div class="table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="Contentbody">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </tfoot>
                            </table>
                            </div>
                        </div>
                    </div> <!-- /.tab-content -->
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.col-lg-8 -->

        <!-- Parte Derecha: Filtro + Calendario -->
        <div class="col-lg-4 p-4">
            <!-- Filtros -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
                </div>
                <div class="card-body">
                    <form id="formFiltro">
                        <div class="mb-3">
                            <label for="estadoFiltro" class="form-label">Estado</label>
                            <select class="form-select" id="estadoFiltro">
                                <option value="">Todos</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En Curso">En Curso</option>
                                <option value="Terminado">Terminado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="clienteFiltro" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="clienteFiltro" placeholder="Buscar cliente">
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="aplicarFiltro()">Filtrar</button>
                    </form>
                </div>
            </div>

            <!-- Calendario Simple -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Calendario</h6>
                </div>
                <div class="card-body">
                    <div id="calendario">Calendario (Normal)</div>
                </div>
            </div>
        </div> <!-- /.col-lg-4 -->

    </div> <!-- /.row -->
</div> <!-- /.container-fluid -->

<script src="scripts/agenda.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function aplicarFiltro() {
    const estado = document.getElementById('estadoFiltro').value;
    const cliente = document.getElementById('clienteFiltro').value.toLowerCase();
    document.querySelectorAll('#lista .list-group-item').forEach(item => {
        const texto = item.textContent.toLowerCase();
        const estadoItem = item.querySelector('.card-status').textContent;
        if ((estado === "" || estadoItem === estado) && (cliente === "" || texto.includes(cliente))) {
            item.style.display = "flex";
        } else {
            item.style.display = "none";
        }
    });
}
</script>

<?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
}
ob_end_flush();
?>
