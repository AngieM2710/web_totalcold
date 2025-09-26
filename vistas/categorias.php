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
      <h1 class="h3 mb-2 text-gray-800 letrastitulo">Gestión  de categorías</h1>

      <!-- Card principal -->
      <div class="card shadow mb-4 contendorprinc">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
              <h6 class="m-0 font-weight-bold text-primary">
                  Lista de categorías
              </h6>

              <!-- Botón agregar -->
              <a href="#" id="btnagregar" onclick="mostrarform(true)" 
                  class="btn btn-sm btn-primary shadow-sm">
                  <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
              </a>
          </div>

          <div class="card-body">
              <!-- Listado -->
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

              <!-- Formulario (oculto por defecto con JS) -->
              <div class="panel-body" id="formularioregistros" style="display:none;">
                  <form name="formulario" id="formulario" method="POST" class="letragenera">
                      
                      <div class="form-row">
                          <div class="col-md-6 mb-3">
                              <label for="descripcion" class="form-label font-weight-bold">
                                  Descripción del servicio (*)
                              </label>
                              <input type="hidden" name="id_servicios" id="id_servicios">
                              <input type="text" 
                                     class="form-control shadow-sm" 
                                     name="descripcion" 
                                     id="descripcion" 
                                     maxlength="100" 
                                     placeholder="Categoría" 
                                     required>
                          </div>
                      </div>

                      <!-- Botones -->
                      <div class="form-group text-center mt-4">
                          <button type="submit" id="btnGuardar" class="btn btn-success btn-sm shadow-sm">
                              <i class="fas fa-save"></i> Guardar
                          </button>

                          <a id="btnCancelar" onclick="cancelarform()" 
                             class="btn btn-danger btn-sm shadow-sm">
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
?>

<script type="text/javascript" src="scripts/categorias.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
}
ob_end_flush();
?>
