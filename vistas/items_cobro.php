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
                  Lista de Servicios
              </h6>

              <!-- Botón agregar -->
                <a href="#" onclick="abrirModal1('agregar')" 
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
                              <th>Servicio</th>
                              <th>Descripción</th>
                              <th>Estado</th>
                              <th>Opciones</th>
                          </tr>
                      </thead>
                      <tbody class="Contentbody">
                      </tbody>
                      <tfoot>
                          <tr>
                              <th>Servicio</th>
                              <th>Descripción</th>
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
                        <input type="hidden" name="id_item_cobro" id="id_item_cobro">
                    
                    <div class="form-row">

                        <div class="col-lg-12">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label mb-0">Categoria :</label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control selectpicker" id="id_servicios" name="id_servicios" class="form-control selectpicker" data-live-search="true" required></select>
                                </div>
                            </div>
                        </div> <br>
            
                    <!-- Datos -->
                    <div class="col-lg-12">
                        <div class="row g-3">
                            <div class="col-md-12 form-group input-group-new">
                            <label class="form-label"> Descripción (*)</label>
                                <input type="text" 
                                        class="form-control shadow-sm" 
                                        name="nombre" 
                                        id="nombre" 
                                        maxlength="100" 
                                        placeholder="Item de cobro" 
                                        required>
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
?>

<script type="text/javascript" src="scripts/items_cobro.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
}
ob_end_flush();
?>
