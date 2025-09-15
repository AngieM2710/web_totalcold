<?php
//ob_start();
//session_start();

// if (!isset($_SESSION["nombres"])) {
  //  header("Location: login.php");
//} else { 
    require 'header.php';
    //if ($_SESSION['administrador'] == 1) {  
?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800 letrastitulo">Gestión de equipos</h1>
    <div class="card shadow mb-4 contendorprinc">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Seleccione una acción utilizando los botones disponibles</h6>
        </div>

        <div class="card-body">
            <a href="#" id="btnagregar" onclick="mostrarform(true)" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-plus-circle"></i></span>
                <span class="text">Agregar</span>
            </a>

            <a href="../reportes/equipos.php" target="_blank" class="btn btn-info btn-icon-split" id="btnreporte">
                <span class="icon text-white-50"><i class="fas fa-print"></i></span>
                <span class="text">Reporte</span>
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="Contenhead2">
                        <th>Opciones</th>
                        <th>Código interno</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                    </thead>
                    <tbody class="Contentbody"></tbody>
                    <tfoot class="Contentfoot">
                        <th>Opciones</th>
                        <th>Código interno</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                    </tfoot>
                </table>
            </div>

            <!-- Formulario para agregar/editar -->
            <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST" class="user letragenera" enctype="multipart/form-data">

                        <!-- Información del equipo -->
                        <div class="form-group col-lg-12 col-md-6 col-sm-6 col-xs-12">
                            <div class="div2"><h2 class="leth2">Información</h2></div>

                            <div class="form-row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label>Código interno(*):</label>
                                    <input type="hidden" name="id_equipo" id="id_equipo">
                                    <input type="text" class="form-control" name="codigo_interno" id="codigo_interno" maxlength="100" placeholder="Código interno" required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label>Marca(*):</label>
                                    <input type="text" class="form-control" name="marca" id="marca" maxlength="100" placeholder="Marca" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label>Modelo(*):</label>
                                    <input type="text" class="form-control" name="modelo" id="modelo" maxlength="100" placeholder="Modelo" required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label>Capacidad(*):</label>
                                    <input type="text" class="form-control" name="capacidad" id="capacidad" placeholder="Capacidad" required>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <center>
                            <button type="submit" id="btnGuardar" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                                <span class="text">Guardar</span>
                            </button>

                            <a id="btnCancelar" onclick="cancelarform()" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50"><i class="fas fa-arrow-circle-left"></i></span>
                                <span class="text">Cancelar</span>
                            </a>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="scripts/equipos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php 
  /*   } else {
        require 'noacceso.php';
    }  */
    require 'footer.php';
//}
ob_end_flush();
?>
