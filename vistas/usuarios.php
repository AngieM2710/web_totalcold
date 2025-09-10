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
    <h1 class="h3 mb-2 text-gray-800 letrastitulo">Gestión de usuarios</h1>
    <div class="card shadow mb-4 contendorprinc">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Seleccione una acción utilizando los botones disponibles</h6>
        </div>

        <div class="card-body">
            <a href="#" id="btnagregar" onclick="mostrarform(true)" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-plus-circle"></i></span>
                <span class="text">Agregar</span>
            </a>

            <a href="../reportes/usuarios.php" target="_blank" class="btn btn-info btn-icon-split" id="btnreporte">
                <span class="icon text-white-50"><i class="fas fa-print"></i></span>
                <span class="text">Reporte</span>
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="Contenhead2">
                        <th>Opciones</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Foto</th>
                        <th>Estado</th>
                    </thead>
                    <tbody class="Contentbody"></tbody>
                    <tfoot class="Contentfoot">
                        <th>Opciones</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Foto</th>
                        <th>Estado</th>
                    </tfoot>
                </table>
            </div>

            <!-- Formulario para agregar/editar -->
            <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST" class="user letragenera" enctype="multipart/form-data">
                    <div class="form-row">
                        <!-- Imagen de usuario -->
                        <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12 text-center centro">
                            <div style="position: relative; display: inline-block;">
                                <img src="img/default-user.png" id="imagenmuestra" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;" alt="Imagen de usuario">
                                <label for="imagen" style="position: absolute; bottom: 10px; right: 10px; cursor: pointer;">
                                    <i class="fas fa-camera" style="box-shadow: 0 4px 20px rgba(0,0,0,0.1); font-size: 20px; color: #4ca1af; background: #fff; padding: 5px; border-radius: 50%; border: 1px solid #ddd;"></i>
                                </label>
                            </div>
                            <div style="margin-top: 10px;">
                                <input type="file" class="form-control tamimg2" name="imagen" id="imagen" style="display: none;" accept="image/x-png,image/gif,image/jpeg">
                                <span id="file-name" style="display: block; margin-top: 5px; font-size: 14px; color: #555;"></span>
                                <input type="hidden" name="imagenactual" id="imagenactual">
                            </div>
                        </div>

                        <!-- Información personal -->
                        <div class="form-group col-lg-8 col-md-6 col-sm-6 col-xs-12">
                            <div class="div2"><h2 class="leth2">Información Personal</h2></div>

                            <div class="form-row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label>Nombre(*):</label>
                                    <input type="hidden" name="id_usuarios" id="id_usuarios">
                                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label>Apellido(*):</label>
                                    <input type="text" class="form-control" name="apellido" id="apellido" maxlength="100" placeholder="Apellido" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label>Teléfono(*):</label>
                                    <input type="number" class="form-control" name="telefono" id="telefono" maxlength="10" placeholder="Teléfono" required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label>Correo:</label>
                                    <input type="email" class="form-control" name="correo" id="correo" placeholder="Correo electrónico">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <label>Clave:</label>
                                    <input type="password" class="form-control" name="password" id="password" maxlength="64" placeholder="Clave">
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

<script type="text/javascript" src="scripts/usuarios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('imagen').addEventListener('change', function(event) {
    var file = event.target.files[0];
    if (file) {
        document.getElementById('file-name').textContent = file.name;
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagenmuestra').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Función para cargar imagen al editar usuario
function cargarImagenUsuario(imagenURL) {
    if (imagenURL) {
        document.getElementById('imagenmuestra').src = imagenURL;
    } else {
        document.getElementById('imagenmuestra').src = "img/default-user.png";
    }
    document.getElementById('file-name').textContent = '';
}

// Función para resetear la imagen al crear un nuevo usuario
function resetearImagen() {
    document.getElementById('imagenmuestra').src = "img/default-user.png";
    document.getElementById('file-name').textContent = '';
    document.getElementById('imagen').value = '';
}
</script>

<?php 
  /*   } else {
        require 'noacceso.php';
    }  */
    require 'footer.php';
//}
ob_end_flush();
?>
