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
    <h1 class="h3 mb-2 text-gray-800 letrastitulo">Gestión de Clientes</h1>

    <!-- Card principal -->
    <div class="card shadow mb-4 contendorprinc">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Clientes</h6>
            <div>
                <!-- Botón agregar -->
                <a href="#" onclick="abrirModal1('agregar')" 
                class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
                </a>
                <!-- Botón reporte -->
                <a href="../reportes/clientes.php" target="_blank" 
                    class="btn btn-sm btn-info shadow-sm" id="btnreporte">
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
                        <th>cedula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody class="Contentbody"></tbody>
                    <tfoot>
                        <tr>
                        <th>cedula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal  -->
    <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalTitle">Registro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Formulario original -->
                    <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_cliente" id="id_cliente">
                        <input type="hidden" name="estado" id="estado">
                    
                    <div class="form-row">

                            <!-- Datos -->
                            <div class="col-lg-12">
                            <div class="row g-3">
                                <div class="col-md-6 form-group input-group-new">
                                <label class="form-label">Cédula(*)</label>
                                <input type="number" class="form-control" name="cedula" id="cedula" 
                                maxlength="10" placeholder="Ingrese la cédula" required>
                                </div>
                                <div class="col-md-6 form-group input-group-new">
                                <label class="form-label">Nombre(*)</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" 
                                maxlength="100" placeholder="Ingrese el nombre" required>
                                </div>
                                <div class="col-md-6 form-group input-group-new">
                                <label class="form-label">Apellido(*)</label>
                                <input type="text" class="form-control" name="apellido" id="apellido" 
                                maxlength="100" placeholder="Ingrese el apellido" required>
                                </div>
                                <div class="col-md-6">
                                <label class="form-label">Teléfono(*)</label>
                               <input type="number" class="form-control " name="telefono" id="telefono" maxlength="10" placeholder="Teléfono"
                                      oninput="javascript: if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                      onchange="validarTelefono(this);" required>
                                </div>
                                <div class="col-md-6 form-group input-group-new">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" name="correo" id="correo" 
                                placeholder="Ingrese el correo electrónico">
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

<script type="text/javascript" src="scripts/clientes.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
