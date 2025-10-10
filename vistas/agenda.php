<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1 || $_SESSION['tecnico'] == 1) {
?>

<!-- <style>

</style> -->

<div class="container-fluid">
    <div class="row">

        <!-- Parte Izquierda: Lista y Tabla -->
        <div class="col-lg-8 p-4">
            <h3 class="mb-4 text-gray-800 letrastitulo">Lista de Citas Programadas</h3>
            
            <div class="card shadow mb-4 contendorprinc">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs mb-0" id="tabCitas" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="lista-tab" data-bs-toggle="tab" data-bs-target="#lista" type="button" role="tab">Lista de Citas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tabla-tab" data-bs-toggle="tab" data-bs-target="#tabla" type="button" role="tab">Vista de Tabla</button>
                        </li>
                    </ul>
                    <a href="Agendar.php"class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Agregar
                    </a>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="tabCitasContent">
                      
                        <div class="tab-pane fade show active" id="lista" role="tabpanel">
                            <div class="list-group" id="listadoregistros">
                                  <!-- Aquí se insertarán dinámicamente las citas -->
                            </div>
                        </div>


                        <!-- Vista de Tabla -->
                        <div class="tab-pane fade" id="tabla" role="tabpanel">
                            <div class="table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Fecha-Hora</th>
                                        <th>Cliente</th>
                                        <th>Tecnico</th>
                                        <th>Servicios</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="Contentbody">
                                </tbody>
                                <tfoot>
                                    <tr>
                                       <th>Fecha-Hora</th>
                                        <th>Cliente</th>
                                        <th>Tecnico</th>
                                        <th>Servicios</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </tfoot>
                            </table>
                            </div>
                        </div>
                    </div> <!-- /.tab-content -->
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.col-lg-8 -->

<div class="col-lg-4 p-4">
    
    <div class="card shadow mb-4 contendorprinc">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rango de Fechas </h6>
        </div>
        <div class="card-body p-0">
            <div id="doble-calendario-visible">
                <div id="calendario-inicio-container">
                    <!-- <h6 class="text-center text-primary">Fecha Inicio </h6> -->
                </div>
                <div id="calendario-fin-container">
                    <!-- <h6 class="text-center text-primary">Fecha Fin </h6> -->
                </div>
            </div>
        </div>
    </div>
    
    <input type="text" id="fechaInicioLogica" hidden value="">
    <input type="text" id="fechaFinLogica" hidden value="">
    
    <div class="card shadow contendorprinc">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros Adicionales</h6>
        </div>
        <div class="card-body">
            <form id="formFiltro">
                
                <div class="mb-3">
                    <label for="fechaInicioRef" class="form-label">Fecha de Inicio (Ref.)</label>
                    <input type="text" class="form-control" id="fechaInicioRef" readonly style="background-color: #f8f9fc;">
                </div>
                
                <div class="mb-3">
                    <label for="fechaFinRef" class="form-label">Fecha de Fin (Ref.)</label>
                    <input type="text" class="form-control" id="fechaFinRef" readonly style="background-color: #f8f9fc;">
                </div>
                
                <div class="mb-3">
                    <label for="tecnicoFiltro" class="form-label">Técnico Asignado</label>
                    <select class="form-select form-control" id="tecnicoFiltro">
                        <option value="">Cualquier Técnico</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="estadoFiltro" class="form-label">Estado del Servicio</label>
                    <select class="form-select form-control" id="estadoFiltro">
                        <option value="">Todos los Estados</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Curso">En Curso</option>
                        <option value="Terminado">Terminado</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="clienteFiltro" class="form-label">Buscar Cliente</label>
                    <input type="text" class="form-control" id="clienteFiltro" placeholder="Nombre o parte del servicio">
                </div>
                
                <button type="button" class="btn btn-primary w-100 mt-2" onclick="aplicarFiltro()">Aplicar Filtros</button>
            </form>
        </div>
    </div>
</div>

    </div> <!-- /.row -->
</div> <!-- /.container-fluid -->

<script src="scripts/agenda.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {
    // 1. ESTABLECER EL IDIOMA ESPAÑOL. Esta línea es CLAVE para la traducción.
    // Esto aplica la configuración de datepicker-es.min.js
    if ($.datepicker.regional['es']) {
        $.datepicker.setDefaults($.datepicker.regional['es']);
    }

    // Referencias a los inputs
    const fromLogica = $("#fechaInicioLogica"); 
    const toLogica = $("#fechaFinLogica"); 
    const fromRef = $("#fechaInicioRef"); 
    const toRef = $("#fechaFinRef"); 

    const displayFormat = "dd/mm/yy"; // Formato de visualización (ej: 02/10/2025)
    const logicFormat = "yy-mm-dd";   // Formato para el backend/lógica (ej: 2025-10-02)

    function updateDateFields(selectedDate, calendarType) {
        const dateObj = $.datepicker.parseDate(logicFormat, selectedDate);
        const refValue = $.datepicker.formatDate(displayFormat, dateObj);

        if (calendarType === 'from') {
            fromLogica.val(selectedDate); 
            fromRef.val(refValue);
        } else {
            toLogica.val(selectedDate); 
            toRef.val(refValue);
        }
    }

    // Opciones base para ambos calendarios
    const baseOptions = {
        dateFormat: logicFormat, 
        numberOfMonths: 1,
        // Forzar a que se muestre siempre inline (modo permanente)
        beforeShow: function(input, inst) {
            inst.dpDiv.css({
                'display': 'block',
                'position': 'relative',
                'float': 'none',
                'box-shadow': 'none' 
            });
        }
    };

    // Inicializar Calendario de INICIO (FROM)
    $("#calendario-inicio-container").datepicker({
        ...baseOptions, 
        onSelect: function(selectedDate) {
            updateDateFields(selectedDate, 'from');
            $("#calendario-fin-container").datepicker("option", "minDate", selectedDate);
        }
    }).datepicker("show"); 

    // Inicializar Calendario de FIN (TO)
    $("#calendario-fin-container").datepicker({
        ...baseOptions, 
        onSelect: function(selectedDate) {
            updateDateFields(selectedDate, 'to');
            $("#calendario-inicio-container").datepicker("option", "maxDate", selectedDate);
        }
    }).datepicker("show"); 
    
    // Configuración Inicial de Meses (para que se vean meses diferentes)
    const today = new Date();
    // Establece el segundo calendario en el mes siguiente para ver dos meses diferentes
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate()); 

    $("#calendario-inicio-container").datepicker("setDate", today);
    $("#calendario-fin-container").datepicker("setDate", nextMonth);
});
// La función aplicarFiltro() se mantiene sin cambios, ya que usa los inputs ocultos
function aplicarFiltro() {
    const fechaInicio = document.getElementById('fechaInicioLogica').value;
    const fechaFin = document.getElementById('fechaFinLogica').value;
    
    // ... (restos de filtros) ...
    
    console.log("Filtrando con fechas:", fechaInicio, "a", fechaFin);
    // ... Tu lógica de backend/AJAX
}

</script>

<?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
}
ob_end_flush();
?>
