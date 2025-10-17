<?php
ob_start();
session_start();
date_default_timezone_set('America/Guayaquil');
if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1 || $_SESSION['tecnico'] == 1) {
?>

<!-- <style>

</style> -->

<div class="container-fluid">
    <div class="row">

        <!-- Parte Izquierda: Lista y Tabla -->
        <div class="col-lg-8 p-4">
            <h3 class="mb-4 text-gray-800 letrastitulo">Órdenes Programadas</h3>
            
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
                    <?php 
                        // Verifica si el usuario es administrador (asumiendo que '1' significa verdadero/administrador)
                        if (isset($_SESSION['administrador']) && $_SESSION['administrador'] == 1) { 
                        ?>
                            <a href="Agendar.php" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
                            </a>
                        <?php 
                        }
                        ?>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="tabCitasContent">
                      
                        <div class="tab-pane fade show active" id="lista" role="tabpanel">
                            <div class="list-group" id="listadoregistros">
                                  <!-- Aquí se insertarán dinámicamente las citas -->
                            </div>
                        </div>


                        <!-- Vista de Tabla -->
                        <div class="tab-pane fade" id="tabla" role="tabpanel">
                            <div class="table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Fecha-Hora</th>
                                        <th>Cliente</th>
                                        <th>Tecnico</th>
                                        <th>Servicios</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="Contentbody">
                                </tbody>
                                <tfoot>
                                    <tr>
                                       <th>Fecha-Hora</th>
                                        <th>Cliente</th>
                                        <th>Tecnico</th>
                                        <th>Servicios</th>
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

<div class="col-lg-4 p-4">
    
    <div class="card shadow mb-4 contendorprinc">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rango de Fechas </h6>
        </div>
        <div class="card-body p-0">
            <input type="text" id="fechaInicioLogica" hidden value="">
            <input type="text" id="fechaFinLogica" hidden value="">
            <div id="doble-calendario-visible">
                <div id="calendario-inicio-container">
                    <!-- <h6 class="text-center text-primary">Fecha Inicio </h6> -->
                </div>
                <div id="calendario-fin-container">
                    <!-- <h6 class="text-center text-primary">Fecha Fin </h6> -->
                </div>
            </div>
        </div>
    </div>
    
    <input type="text" id="fechaInicioLogica" hidden value="">
    <input type="text" id="fechaFinLogica" hidden value="">
    
    <div class="card shadow contendorprinc">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros Adicionales</h6>
        </div>
        <div class="card-body">
            <form id="formFiltro">
                
                <div class="mb-3">
                    <label for="fechaInicioRef" class="form-label">Fecha de Inicio (Ref.)</label>
                    <input type="text" class="form-control" id="fechaInicioRef" readonly style="background-color: #f8f9fc;">
                </div>
                
                <div class="mb-3">
                    <label for="fechaFinRef" class="form-label">Fecha de Fin (Ref.)</label>
                    <input type="text" class="form-control" id="fechaFinRef" readonly style="background-color: #f8f9fc;">
                </div>
                
                <div class="mb-3">
                    <label for="tecnicoFiltro" class="form-label">Técnico Asignado</label>
                    <select id="id_tec" name="id_tec" class="form-control selectpicker" data-live-search="true" style="width: 100%;" required></select>
                </div>
                <div class="mb-3">
                    <label for="clienteFiltro" class="form-label">Buscar Cliente</label>
                    <select id="id_cli" name="id_cli" class="form-control selectpicker" data-live-search="true" style="width: 100%;" required></select>
                </div>
                <div class="mb-3">
                    <label for="estadoFiltro" class="form-label">Estado del Servicio</label>
                    <select class="form-select form-control" id="estadoFiltro">
                        <option value="">Todos los Estados</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Terminado">Terminado</option>
                    </select>
                </div>
                

                
                <button type="button" class="btn btn-primary w-100 mt-2" onclick="aplicarFiltro()">Aplicar Filtros</button>
            </form>
        </div>
    </div>
</div>

    </div> <!-- /.row -->
</div> <!-- /.container-fluid -->

<script src="scripts/agenda.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
}
ob_end_flush();
?>
