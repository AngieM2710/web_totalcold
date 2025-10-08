<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1 || $_SESSION['tecnico'] == 1)  {
        ?>
        <div class="row">
            <!-- Formulario Agregar Servicio -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="mb-4">Agregar Nuevo Servicio</h4>
                        <form>
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
                                        <input type="datetime-local" class="form-control">
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
                            <button class="btn btn-sm btn-outline-primary">Subir Foto</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Compresor</label>
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <input type="text" class="form-control mb-2" placeholder="Modelo: WXYZ678">
                                </div>
                            </div>
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
}
ob_end_flush();
?>
