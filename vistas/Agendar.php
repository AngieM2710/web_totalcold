<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.php");
} else {
  require 'header.php';
  if ($_SESSION['administrador'] == 1 || $_SESSION['tecnico'] == 1)  {
    ?>

<div class="container-fluid py-4">



<!--     <div class="contenedorboton">

        <div class="m-0 font-weight-bold text-primary">
            <a href="#" id="btnagregar" onclick="mostrarform(true)" 
                class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
            </a>
            <a href="../reportes/usuarios.php" target="_blank" 
                class="btn btn-sm btn-info shadow-sm" id="btnreporte">
                <i class="fas fa-print fa-sm text-white-50"></i> Reporte
            </a>
        </div>
    </div>
 -->
  <div class="row">
    <!-- Formulario Agregar Servicio -->
    <div class="col-lg-7 mb-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-4">Agregar Nuevo Servicio</h4>
          <form>
            <div class="mb-3">
                <div class="row align-items-center">
                    <!-- Columna para el label -->
                    <div class="col-md-3">
                        <label class="form-label mb-0">Cliente:</label>
                    </div>
                    <!-- Columna para el select -->
                    <div class="col-md-9">
                        <select id="id_cli" name="id_cli" class="form-control selectpicker" 
                            data-live-search="true"  style="width: 100%;" 
                            required>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="row align-items-center">
                    <!-- Columna para el label -->
                    <div class="col-md-3">
                        <label class="form-label mb-0">Técnico(s) Asignado:</label>
                    </div>
                    <!-- Columna para el select -->
                    <div class="col-md-9">
                        <select id="id_tec" name="id_tec" class="form-control selectpicker" 
                            data-live-search="true"  style="width: 100%;" 
                            required>
                        </select>
                    </div>
                </div>
            </div>

             <div class="mb-3">
                <div class="row align-items-center">
                    <!-- Columna para el label -->
                    <div class="col-md-3">
                        <label class="form-label">Fecha y Hora</label>
                    </div>
                    <!-- Columna para el select -->
                    <div class="col-md-9">
                        <input type="datetime-local" class="form-control">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="row align-items-center">
                    <!-- Columna para el label -->
                    <div class="col-md-3">
                        <label class="form-label mb-0">Tipo de Servicio :</label>
                    </div>
                    <!-- Columna para el select -->
                    <div class="col-md-9">
                        <select id="id_serv" name="id_serv" class="form-control selectpicker" 
                            data-live-search="true"  style="width: 100%;" 
                            required>
                        </select>
                    </div>
                </div>
            </div>

<div class="mb-3">
  <div class="row align-items-center">
    <div class="col-md-3">
      <label class="form-label mb-0">Estado del Servicio Técnico :</label>
    </div>
    <div class="col-md-9">
      <select class="form-control selectpicker" id="estadoServicio">
        <option value="">Seleccione...</option>
        <option value="Pendiente">Pendiente</option>
        <option value="Terminado">Terminado</option>
      </select>
    </div>
  </div>
</div>

<!--             <div class="mb-3 " >
              <label class="form-label">Técnico(s) Asignado</label>
              <input type="text" class="form-control" placeholder="Seleccionar técnico">
            </div> -->

<!--             <div class="mb-3 ">
              <label class="form-label">Fecha y Hora</label>
              <input type="datetime-local" class="form-control">
            </div> -->

<!--             <div class="mb-3 " >
              <label class="form-label">Tipo de Servicio</label>
              <select class="form-select">
                <option>Seleccione...</option>
                <option>Mantenimiento</option>
                <option>Reparación</option>
              </select>
            </div> -->

<!--             <div class="mb-3 d-flex align-items-center">
              <label class="form-label me-3">Capacidad de Equipo</label>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="capacidadSwitch" checked>
                <label class="form-check-label text-success" for="capacidadSwitch">Pendiente</label>
              </div>
            </div> -->

            <div class="mb-3">
              <label class="form-label">Estado</label>
              <input type="text" class="form-control" placeholder="Utilidad soporte">
            </div>

            <div class="mb-3">
              <label class="form-label">Observaciones</label>
              <textarea class="form-control" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-outline-secondary me-2">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar Servicio</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Panel lateral derecho -->
    <div class="col-lg-5">
      <!-- Agenda del día -->
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h6 class="mb-3">Agenda del Día</h6>
          <input type="date" class="form-control mb-3">
          <ul class="list-group">
            <li class="list-group-item">9:00 AM - Juan Ortega - Pendiente</li>
          </ul>
        </div>
      </div>

      <!-- Archivos Adjuntos -->
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h6 class="mb-3">Archivos Adjuntos</h6>
          <div class="mb-3">
            <label class="form-label mb-0">Evaporador</label>
                <div class="row align-items-center">
                    <div class="col-md-6">
                    <input type="text" class="form-control mb-2" placeholder="Modelo: ABCD123">
                    </div>
                    <div class="col-md-6">
                     
                    <input type="text" class="form-control mb-2" placeholder="SN: 567891134">
                    </div>
                </div>
               <!--  <input type="text" class="form-control mb-2" placeholder="SN: 567891134"> -->
            <button class="btn btn-sm btn-outline-primary">Subir Foto</button>
          </div>

          <div class="mb-3">
            <label class="form-label">Compresor</label>
            <div class="row align-items-center">
                    <div class="col-md-6">
                    <input type="text" class="form-control mb-2" placeholder="Modelo: WXYZ678">
                    </div>

                    <div class="col-md-6">
                    <input type="text" class="form-control mb-2" placeholder="SN: 12347890">
                    </div>
                </div>            
            <button class="btn btn-sm btn-outline-primary">Subir Foto</button>
          </div>
        </div>
      </div>

      <!-- Fotos de instalación -->
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="mb-3">Fotos de Instalación</h6>
          <button class="btn btn-sm btn-outline-secondary">+ Subir Fotos</button>
        </div>
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

<script type="text/javascript" src="scripts/Agendar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
}
ob_end_flush();
?>
