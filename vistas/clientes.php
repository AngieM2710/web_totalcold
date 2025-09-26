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
                <a href="#" id="btnagregar" onclick="mostrarform(true)" 
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

            <!-- Formulario -->
            <div class="panel-body" id="formularioregistros" style="display:none;">
                <form name="formulario" id="formulario" method="POST" class="user" enctype="multipart/form-data">
                    
                    <h2 class="form-title">Información del Cliente</h2>

                    <div class="form-row">
                        <!-- Sección de Imagen -->
                        <div class="col-lg-4 col-md-12 mb-4">
                            <div class="upload-section">
                                <div class="avatar-container">
                                    <img src="../public/img/imagenes/lg.png" id="imagenmuestra" class="avatar-image" alt="Foto del cliente">
                                    <label for="imagen" class="upload-btn">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                </div>
                                <span class="upload-label">Haz clic en la cámara para subir una foto</span>
                                <span id="file-name" class="file-name"></span>
                                
                                <input type="file" name="imagen" id="imagen" style="display: none;" accept="image/png, image/jpeg, image/jpg">
                                <input type="hidden" name="imagenactual" id="imagenactual">
                            </div>
                        </div>

                        <!-- Información Personal -->
                        <div class="col-lg-8 col-md-12">
                            <input type="hidden" name="id_usuarios" id="id_usuarios">
                            <input type="hidden" name="estado" id="estado">

                            <div class="form-row">
                                <div class="col-sm-6 form-group-new">
                                    <label>Cédula(*):</label>
                                    <input type="text" class="form-control" name="cedula" id="cedula" maxlength="10" placeholder="Ingrese la cédula" required>
                                </div>
                                <div class="col-sm-6 form-group-new">
                                    <label>Nombre(*):</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Ingrese el nombre" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-6 form-group-new">
                                    <label>Apellido(*):</label>
                                    <input type="text" class="form-control" name="apellido" id="apellido" maxlength="100" placeholder="Ingrese el apellido" required>
                                </div>
                                <div class="col-sm-6 form-group-new">
                                    <label>Teléfono(*):</label>
                                    <input type="number" class="form-control" name="telefono" id="telefono" maxlength="10" placeholder="Ingrese el teléfono" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-6 form-group-new">
                                    <label>Correo:</label>
                                    <input type="email" class="form-control" name="correo" id="correo" placeholder="Ingrese el correo electrónico">
                                </div>
                                <div class="col-sm-6 form-group-new">
                                    <label>Contraseña:</label>
                                    <input type="password" class="form-control" name="password" id="password" maxlength="64" placeholder="Ingrese la contraseña">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" id="btnGuardar" class="btn btn-success btn-form">
                            <i class="fas fa-save"></i> Guardar
                        </button>

                        <a id="btnCancelar" onclick="cancelarform()" class="btn btn-danger btn-form">
                            <i class="fas fa-arrow-circle-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
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
