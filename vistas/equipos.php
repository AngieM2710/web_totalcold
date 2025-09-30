<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else { 
    require 'header.php';
    if ($_SESSION['administrador'] == 1) {  
?>
<div class="container-fluid">

    <!-- Título -->
    <h1 class="h3 mb-2 text-gray-800 letrastitulo">Gestión de Equipos</h1>

    <!-- Card principal -->
    <div class="card shadow mb-4 contendorprinc">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Equipos</h6>
            <div>
                <!-- Botón agregar -->
                <a href="#" onclick="abrirModal('agregar')" 
                class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
                </a>
                <!-- Botón reporte -->
                <a href="../reportes/equipos.php" target="_blank" class="btn btn-sm btn-info shadow-sm" id="btnreporte">
                    <i class="fas fa-print fa-sm text-white-50"></i> Reporte
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Listado -->
            <div class="table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Código interno</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="Contentbody">
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Código interno</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

         
<!--             <div class="panel-body" id="formularioregistros" style="display:none;">
                <form name="formulario" id="formulario" method="POST" class="user" enctype="multipart/form-data">

                    <h2 class="form-title">Información del Equipo</h2>

                    <div class="form-row">
                        <input type="hidden" name="id_equipo" id="id_equipo">

                        <div class="col-lg-6 col-md-12 form-group-new">
                            <label>Código interno(*):</label>
                            <input type="text" class="form-control" name="codigo_interno" id="codigo_interno" maxlength="100" placeholder="Código interno" required>
                        </div>

                        <div class="col-lg-6 col-md-12 form-group-new">
                            <label>Marca(*):</label>
                            <input type="text" class="form-control" name="marca" id="marca" maxlength="100" placeholder="Marca" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-lg-6 col-md-12 form-group-new">
                            <label>Modelo(*):</label>
                            <input type="text" class="form-control" name="modelo" id="modelo" maxlength="100" placeholder="Modelo" required>
                        </div>

                        <div class="col-lg-6 col-md-12 form-group-new">
                            <label>Capacidad(*):</label>
                            <input type="text" class="form-control" name="capacidad" id="capacidad" placeholder="Capacidad" required>
                        </div>
                    </div>

                    <div class="form-buttons mt-3">
                        <button type="submit" id="btnGuardar" class="btn btn-success btn-form">
                            <i class="fas fa-save"></i> Guardar
                        </button>

                        <a id="btnCancelar" onclick="cancelarform()" class="btn btn-danger btn-form">
                            <i class="fas fa-arrow-circle-left"></i> Cancelar
                        </a>
                    </div>

                </form>
            </div> -->
        </div>
    </div>
       <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
            
            <div class="modal-header ">
                <h5 class="modal-title font-weight-bold " id="modalTitle">Registro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_equipo" id="id_equipo">

                <div class="row">

                    <!-- Datos -->
                    <div class="col-lg-12">
                    <div class="row g-3">
                        <div class="col-md-6 form-group input-group-new">
                        <label class="form-label">Código interno(*)</label>
                        <input type="text" class="form-control" name="codigo_interno" id="codigo_interno"
                         maxlength="100" placeholder="Código interno" required>
                        </div>
                        <div class="col-md-6 form-group input-group-new">
                        <label class="form-label">Marca(*)</label>
                        <input type="text" class="form-control" name="marca" id="marca"
                        maxlength="100" placeholder="Marca" required>
                        </div>
                        <div class="col-md-6 form-group input-group-new">
                        <label class="form-label">Modelo</label>
                        <input type="text" class="form-control" name="modelo" id="modelo" 
                        maxlength="100" placeholder="Modelo" required>
                        </div>
                        <div class="col-md-6">
                        <label class="form-label">Capacidad(*)</label>
                        <input type="text" class="form-control" name="capacidad" id="capacidad" 
                        placeholder="Capacidad" required>
                        </div>

                    </div>
                    </div>
                </div>
                </form>
            </div>

            <div class="modal-footer ">
                <button type="submit" form="formulario" id="btnGuardar" class="btn btn-success botonS">
                <i class="fas fa-save"></i> Guardar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times"></i> Cancelar
                </button>
            </div>

</div>

<?php 
    } else {
        require 'noacceso.php';
    } 
    require 'footer.php';
}
ob_end_flush();
?>

<script type="text/javascript" src="scripts/equipos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

