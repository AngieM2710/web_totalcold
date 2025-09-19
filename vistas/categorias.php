<?php
ob_start();
session_start();

 if (!isset($_SESSION["nombre"]))
{
  header("Location: login.php");
} 
 else
{  
require 'header.php';
  if ($_SESSION['administrador']==1)
{  
?>
        <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800 letrastitulo">Registro de categorías</h1>
        <div class="card shadow mb-4 contendorprinc">
              <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Seleccione una acción utilizando los botones disponibles</h6>
              </div>

            <div class="card-body">

              <a href="#" id="btnagregar" onclick="mostrarform(true)" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus-circle"></i>
                                        </span>
                                        <span class="text">Agregar</span>
                              </a>
                </div>

                    <div class="card-body ">
                    <div class="table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-bordered" width="100%" cellspacing="0">
                          <thead class="Contenhead2">
                            <th>Opciones</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                          </thead>
                          <tbody class="Contentbody">                            
                          </tbody>
                          <tfoot class="Contentfoot">
                            <th>Opciones</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>

                    <!-- form para agg -->
                    <div class="panel-body" style="height: 100%;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST" class="user letragenera">
                        <div class="form-row">
                          <div class="col-sm-6 mb-3 mb-sm-0">                            
                              <label>Descripción del servicio (*):</label>
                              <input type="hidden" name="id_servicios" id="id_servicios">
                             <!--  <select id="id_servicios" name="id_servicios" class="form-control  selectpicker" data-live-search="true" required></select> -->
                          </div>
                          <div class="col-sm-6 mb-3 mb-sm-0">
                            <!--   <label>Descripción(*):</label> -->
                              <input type="text" onkeydown="return /[a-z, ]/i.test(event.key)" class="form-control " name="descripcion" id="descripcion" maxlength="100" placeholder="Categoría" required>
                          </div>
                      </div>
                     <br>
                          
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <center>
                                  <button type="submit" id="btnGuardar" class="btn btn-success btn-icon-split">
                                      <span class="icon text-white-50">
                                          <i class="fas fa-save"></i>
                                      </span>
                                      <span class="text">Guardar</span>
                                  </button>

                                  <a id="btnCancelar" onclick="cancelarform()" class="btn btn-danger btn-icon-split">
                                      <span class="icon text-white-50">
                                          <i class="fas fa-arrow-circle-left"></i>
                                      </span>
                                      <span class="text">Cancelar</span>
                                  </a>
                              </center>
                            </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->

  <!--Fin-Contenido-->
<?php
 }
else
{
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