<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';

    if ($_SESSION['administrador'] == 1) {

/*         if (isset($_GET['id'])) {
        $id_orden = $_GET['id']; */
        $id_orden = isset($_GET['id']) ? $_GET['id'] : '';
        ?>
        
        
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Formulario Agregar Servicio -->
                <div class="col-lg-7 mb-4">
                    <div class="card shadow-sm contendorprinc">
                        <div class="card-body">
                            <h4 class="mb-4 letrastitulo">
                                <?php echo $id_orden ? "Editar Servicio" : "Agregar Nuevo Servicio"; ?>
                            </h4>
                            <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                                <input type="hidden" id="id_orden" name="id_orden" value="<?php echo $id_orden; ?>">
                                
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Cliente:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select id="id_cli" name="id_cli" class="form-control selectpicker" data-live-search="true" style="width: 100%;" required></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Técnico(s) Asignado:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select id="id_tec" name="id_tec" class="form-control selectpicker" data-live-search="true" style="width: 100%;" required></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label">Fecha y Hora</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="fecha" id="fecha" type="datetime-local" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

<!--                                 <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Estado del Servicio Técnico :</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="form-control selectpicker" name="estadoServicio" id="estadoServicio">
                                                <option value="">Seleccione...</option>
                                                <option value="0">Pendiente</option>
                                                <option value="1">Terminado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Tipo de pago :</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="form-control selectpicker" name="tipo_pago" id="tipo_pago">
                                                <option value="">Seleccione...</option>
                                                <option value="Trasferencia">Transferencia</option>
                                                <option value="Efectivo">Efectivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                 
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Dirección : </label>
                                        </div>
                                        <div class="col-md-9">
                                            <textarea name="direccion" id="direccion" class="form-control" rows="2"required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea  name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-outline-secondary me-2">Cancelar</button>&nbsp;
                                    <button type="submit" form="formulario" id="btnGuardar" class="btn btn-success botonS">Guardar </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
 
                <!-- Panel lateral derecho -->
                <div class="col-lg-5">                  
                    <div class="card shadow-sm mb-4 contendorprinc">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 letrastitulo">Items de Cobros </h6>
                                <i class="fas fa-calendar-day text-primary"></i>
                            </div>
                            <div id="resumenServicios"></div>
                            <div class="mt-2 d-flex gap-2 ">
                               <button type="button" class="btn btn-sm btn-warning" id="btnServiciosOrden">Determinar Cobro</button>&nbsp;
                            </div>
                        </div>
                    </div>

                    <!-- Detalle Equipos -->
                    <div class="card shadow-sm mb-4 contendorprinc">
                        <div class="card-body">
                            <h6 class="mb-3 letrastitulo">Detalle Equipos - Servicios</h6>
                            <div id="equiposContainer"></div>
                            <div class="mt-2 d-flex gap-2">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddEquipo">+ Agregar Equipo</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Agregar Equipo -->
        <div class="modal fade" id="modalAddEquipo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Equipo al Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAddEquipo">
                            <div class="mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0">Equipos:</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select id="id_equipo" name="id_equipo" class="form-control selectpicker" data-live-search="true" required></select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0">Servicios:</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select id="id_serv" name="id_serv[]" class="form-control selectpicker" multiple data-live-search="true" required></select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="saveEquipo" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detalles de Equipo -->
        <div class="modal fade" id="modalDetallesEquipo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles Equipo - Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Equipo:</strong> <span id="detalleEquipo"></span></p>
                        <p><strong>Servicios:</strong> <span id="detalleServicios"></span></p>
                        <div observaciones class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" rows="3"></textarea>    
                        </div>

                        <!-- Aquí puedes agregar más detalles si es necesario -->
                        <div class="card-body">                          

                            <div class="mb-3">
                                
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label mb-0">Evaporador</label> <br>
                                            <button class="btn btn-sm btn-outline-primary">Subir Foto</button>        
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label mb-0">Compresor</label> <br>
                                            <button class="btn btn-sm btn-outline-primary">Subir Foto</button>
                                        </div>
                                    </div> <br><br>

                                    <div>
                                        <h6 class="mb-3">Fotos adicionales</h6>
                                        <button class="btn btn-sm btn-outline-secondary">+ Subir Fotos</button>
                                    </div>
                                    
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Servicios de la Orden -->
        <div class="modal fade" id="modalServiciosOrden" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Servicios de la Orden</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="tablaServiciosOrden">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio Total</th>
                                </tr>
                            </thead>
                            <tbody>  </tbody>
                        </table>
                            <div class="servicio-total-container mt-3 text-end">
                            <span class="label-total">Total General : </span>
                            <span class="valor-total">
                                $ <strong id="totalOrden">0.00</strong>
                            </span>
                            </div>
                       <!--  <h5 class="servicio-item">Total:  $ <strong id="totalOrden">0.00</strong></h5> -->
                  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btnGuardarServicios" data-bs-dismiss="modal">Capturar</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php

        /* }  */
    } 
    else {
        require 'noacceso.php';
    }
    require 'footer.php';
?>

<script type="text/javascript" src="scripts/ordenes.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function() {
      const idOrden = "<?php echo $id_orden; ?>";
      if (idOrden) {
          cargarDetalleOrden(idOrden);
      }
  });
</script>

<?php
}
ob_end_flush();
?>
