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
    <h1 class="h3 mb-2 text-gray-800 letrastitulo">Gestión de usuarios</h1>

    <!-- Card principal -->
    <div class="card shadow mb-4 contendorprinc">

        <!-- Encabezado -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Lista de usuarios
            </h6>
<!-- <div class="row mb-2">
    <div class="col-md-4.2">
        <div class="card shadow-sm border-0 text-center cardaux">
            <div class="card-body">
                <span class="label bg-blue">Total de Usuarios</span>
                <span class="label bg-blue" id="totalTecnicos">0</span>
            </div>
        </div>
    </div>
    <div class="col-md-3.5">
        <div class="card shadow-sm border-0 text-center cardaux">
            <div class="card-body">
                <span class="label bg-green"><i class="fas fa-user-check me-1"></i> Activos</span>
                <span class="label bg-green" id="activos">0</span>
            </div>
        </div>
    </div>
    <div class="col-md-3.5">
        <div class="card shadow-sm border-0 text-center cardaux">
            <div class="card-body">
                <span class="label bg-red"><i class="fas fa-user-times me-1"></i> Inactivos</span>
                <span class="label bg-red" id="inactivos">0</span>
            </div>
        </div>
    </div>
</div> -->
            <div>
                <!-- Botón agregar -->
<!--                 <a href="#" id="btnagregar" onclick="mostrarform(true)" 
                    class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
                </a> -->
                <a href="#" onclick="abrirModal1('agregar')" 
                class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
                </a>
                <!-- Botón reporte -->
                <a href="../reportes/usuarios.php" target="_blank" 
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
                            <th>Foto</th>
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
                            <th>Foto</th>
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

<!--             <div class="panel-body" id="formularioregistros" style="display:none;">
                <form name="formulario" id="formulario" method="POST" class="user" enctype="multipart/form-data">
                    
                    <h2 class="form-title">Información del Usuario</h2>

                    <div class="form-row">

                        <div class="col-lg-4 col-md-12 mb-4">
                            <div class="upload-section">
                                <div class="avatar-container">
                                    <img src="img/default-user.png" id="imagenmuestra" class="avatar-image" alt="Foto del técnico">
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

                        <div class="col-lg-8 col-md-12">
                            <input type="hidden" name="id_usuarios" id="id_usuarios">
                            <input type="hidden" name="estado" id="estado">

                            <div class="form-row">
                                <div class="col-sm-6 form-group-new">
                                    <label>Nombre(*):</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Ingrese el nombre" required>
                                </div>
                                <div class="col-sm-6 form-group-new">
                                    <label>Apellido(*):</label>
                                    <input type="text" class="form-control" name="apellido" id="apellido" maxlength="100" placeholder="Ingrese el apellido" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-6 form-group-new">
                                    <label>Teléfono(*):</label>
                                    <input type="number" class="form-control" name="telefono" id="telefono" maxlength="10" placeholder="Ingrese el teléfono" required>
                                </div>
                                <div class="col-sm-6 form-group-new">
                                    <label>Correo:</label>
                                    <input type="email" class="form-control" name="correo" id="correo" placeholder="Ingrese el correo electrónico">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-12 form-group-new">
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
             -->
        </div>
    </div>

<!-- Modal Usuarios -->
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
             <input type="hidden" name="id_usuarios" id="id_usuarios">
             <input type="hidden" name="estado" id="estado" value="1">
              <input type="hidden" name="imagenactual" id="imagenactual">
            
              <div class="form-row">

                  <div class="col-lg-4 text-center mb-">
                      <div class="upload-section">
                        <img src="img/default-user.png" id="imagenmuestra" class="rounded-circle shadow" width="140" height="140">
                        <div class="mt-2 " style="padding-top: 15px;">
                            <label for="imagen" class="btn btn-outline-primary btn-sm ">
                            <i class="fas fa-camera"></i> Subir Foto
                            </label>
                            <input type="file" name="imagen" id="imagen" class="d-none" accept="image/png,image/jpeg">
                        </div>
                        <small id="file-name" class="text-muted"></small>
                       </div>
                  </div>

                    <!-- Datos -->
                    <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6 form-group input-group-new">
                        <label class="form-label">Nombre(*)</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" 
                        placeholder="Nombre(s)" required>
                        </div>
                        <div class="col-md-6 form-group input-group-new">
                        <label class="form-label">Apellido(*)</label>
                        <input type="text" class="form-control" name="apellido" id="apellido" 
                        placeholder="Appellido(s)" required>
                        </div>
                        <div class="col-md-6">
                        <label class="form-label">Teléfono(*)</label>
                        <input type="number" class="form-control" name="telefono" id="telefono" 
                        placeholder="Teléfono" required>
                        </div>
                        <div class="col-md-6 form-group input-group-new">
                        <label class="form-label">Correo</label>
                        <input type="email" class="form-control" name="correo" id="correo"
                        placeholder="Correo Electrónico" required>
                        </div>
                        <div class="col-12 form-group input-group-new">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password"
                        placeholder="Contraseña">
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
  </div>
</div>

</div>
<?php
 }
else
{
require 'noacceso.php';
}  
require 'footer.php';
?>

<script type="text/javascript" src="scripts/usuarios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php 
}
ob_end_flush();
?>
