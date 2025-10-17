var tabla;
$(document).ready(function () {
    if ($.datepicker.regional['es']) {
        $.datepicker.setDefaults($.datepicker.regional['es']);
    }
    // Cuando cambian los calendarios sin presionar boton 
    $("#fechaInicioLogica, #fechaFinLogica").on("change", aplicarFiltro);
    // Cuando cambia técnico, cliente o estado
    $("#id_tec, #id_cli, #estadoFiltro").on("change", aplicarFiltro);

    init();
});

function init() {
    cargarClientes();
    cargarTecnicos();
    calendarios();
    listar();
    listar_tecnico();
    listartabla();
}

function cargarClientes() {
    $.post("../ajax/clientes.php?op=selectClientes", function (r) {
        let opciones = '<option value=""> Todos los Clientes </option>' + r;
        $("#id_cli").html(opciones);// cargamos las opciones
        $('#id_cli').selectpicker('refresh');// refrescamos bootstrap-select
    });
}
function cargarTecnicos() {
    $.post("../ajax/tecnicos.php?op=selectTenicos", function (r) {
        let opciones = '<option value="">Todos los Técnico </option>' + r;
        $("#id_tec").html(opciones);// cargamos las opciones
        $('#id_tec').selectpicker('refresh');// refrescamos bootstrap-select
    });
}

function calendarios() {
    // Asegurarse de que todo corra cuando el DOM esté listo
    $(function () {
        // Referencias a los inputs ocultos y de referencia
        const fromLogica = $("#fechaInicioLogica");
        const toLogica = $("#fechaFinLogica");
        const fromRef = $("#fechaInicioRef");
        const toRef = $("#fechaFinRef");

        const displayFormat = "dd/mm/yy"; // Para mostrar
        const logicFormat = "yy-mm-dd";    // Para backend / lógica

        // Función para actualizar los inputs
        function updateDateFields(selectedDate, type) {
            const dateObj = $.datepicker.parseDate(logicFormat, selectedDate);
            const refValue = $.datepicker.formatDate(displayFormat, dateObj);

            if (type === 'from') {
                fromLogica.val(selectedDate);
                fromRef.val(refValue);
            } else {
                toLogica.val(selectedDate);
                toRef.val(refValue);
            }
        }

        // Opciones base para ambos calendarios
        const baseOptions = {
            numberOfMonths: 1,
            dateFormat: "yy-mm-dd", // para lógica interna
            beforeShow: function (input, inst) {
                inst.dpDiv.css({
                    'display': 'block',
                    'position': 'relative',
                    'float': 'none',
                    'box-shadow': 'none'
                });
            }
        };


        // Inicializar calendario de INICIO
        $("#calendario-inicio-container").datepicker({
            ...baseOptions,
            onSelect: function (date) {
                updateDateFields(date, 'from');
                $("#calendario-fin-container").datepicker("option", "minDate", date);
                $("#fechaInicioLogica").trigger("change"); //  Forza el evento para obtener el cambio automatico
            }
        });

        // Inicializar calendario de FIN
        $("#calendario-fin-container").datepicker({
            ...baseOptions,
            onSelect: function (date) {
                updateDateFields(date, 'to');
                $("#calendario-inicio-container").datepicker("option", "maxDate", date);
                $("#fechaFinLogica").trigger("change");
            }
        });

        // Fechas iniciales 
        const today = new Date();// hoy
        //const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate()); //mes siguiente

        $("#calendario-inicio-container").datepicker("setDate", today);
        // $("#calendario-fin-container").datepicker("setDate", nextMonth);
        $("#calendario-fin-container").datepicker("setDate", today);

        // Llenar inputs ocultos y visibles al cargar
        updateDateFields($.datepicker.formatDate(logicFormat, today), 'from');
        updateDateFields($.datepicker.formatDate(logicFormat, today), 'to');

        // Aquí: una vez que ya están listas las fechas, aplicamos el filtro
        aplicarFiltro();

    });
}

function aplicarFiltro() {
    //filtro de valores para automatiazarlo
    const fechaInicio = $("#fechaInicioLogica").val();
    let fechaFin = $("#fechaFinLogica").val();
    const idTec = $("#id_tec").val();
    const idCli = $("#id_cli").val();
    const estado = $("#estadoFiltro").val();
    // Ajustar fecha fin para incluir todo el día
    if (fechaFin) fechaFin = fechaFin + ' 23:59:59';
    // Creamos un objeto de filtros
    const filtros = {};
    if (fechaInicio) filtros.fechaInicio = fechaInicio;
    if (fechaFin) filtros.fechaFin = fechaFin;
    if (idTec) filtros.id_tec = idTec;
    if (idCli) filtros.id_cli = idCli;
    if (estado) filtros.estado = estado;
    // Llamamos a listar con los filtros
    listar(filtros);
    listar_tecnico(filtros);
}

function listar(filtros = {}) {
    /*   console.log("Listando citas con filtros:", filtros); */

    $.ajax({
        url: '../ajax/agenda.php?op=listarcard',
        type: "GET",
        dataType: "json",
        data: filtros, // enviamos filtros si existen
        success: function (response) {
            $('#listadoregistros').html('');

            if (response && response.aaData && response.aaData.length > 0) {
                response.aaData.forEach(function (item) {
                    var card = `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                          ${item.fecha} - Hora: ${item.hora} - Cliente: ${item.cliente} - ${item.servicio} ${item.estado} 
                        </div>
                        <div class="d-flex align-items-center gap-2">
                        ${item.estado} &nbsp;
                        ${item.acciones}
                        </div>
                    </div>
                    `;
                    $('#listadoregistros').append(card);
                });
            } else {
                $('#listadoregistros').html('<div class="list-group-item">No hay citas programadas.</div>');
            }
        },
        error: function (e) {
            console.log("Error en listarcard(): ", e.responseText);
        }
    });
}


function listar_tecnico(filtros = {}) {
    /*   console.log("Listando citas con filtros:", filtros); */

    $.ajax({
        url: '../ajax/agenda.php?op=listarcard',
        type: "GET",
        dataType: "json",
        data: filtros, // enviamos filtros si existen
        success: function (response) {
            $('#listadoregistros_tecnico').html('');

            if (response && response.aaData && response.aaData.length > 0) {
                response.aaData.forEach(function (item) {
                    var card = `
                        <div class="list-group" id="listadoregistros">
                            <div class="card mb-3 custom-card-style">        
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1 font-weight-bold text-dark">
                                                Cliente: ${item.cliente} - ${item.fecha}
                                            </h5>
                                            <p class="text-success font-weight-bold small mb-0">Hora: ${item.hora}</p> 
                                        </div>
                                    <a href="ver_orden.php?id=${item.id}" 
                                            class="btn btn-sm btn-primary flex-shrink-0" 
                                            title="Ir a Orden">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <p class="mb-0 text-secondary">
                                            <span class="font-weight-bold">Servicio:</span> ${item.servicio}
                                        </p>
                                        ${item.estado} 
                                    </div>
                                </div> 
                            </div> 
                        </div>
                    `;
                    $('#listadoregistros_tecnico').append(card);
                });
            } else {
                $('#listadoregistros_tecnico').html('<div class="list-group-item">No hay citas programadas.</div>');
            }
        },
        error: function (e) {
            console.log("Error en listarcard(): ", e.responseText);
        }
    });
}



function listartabla() {
    tabla = $('#tbllistado').dataTable(
        {
            /* "lengthMenu": [[ 5, 10, 25, 75, 100],[5, 10, 25, 75, 100]],  */
            /* 		"aProcessing": true,
                    "aServerSide": true,
                    "dom": '<"row"<"col-sm-9"l><"col-sm-3"f>>rtip',  */
            "ajax":
            {
                url: '../ajax/agenda.php?op=listartab',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "language": {
                "lengthMenu": "Mostrar : _MENU_ registros",
                "search": "",
                "searchPlaceholder": "Buscar...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente"
                },
            },
            /* "initComplete": function() {
                // Reemplaza el campo de búsqueda con uno personalizado (icono a la derecha)
                $('.dataTables_filter').html(`
                    <div class="input-group">
                        <input type="search" id="customSearch" class="form-control" placeholder="Buscar...">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                `);
    
                // Vincula el campo de búsqueda personalizado con DataTables
                $('#customSearch').on('keyup', function() {
                    tabla.search(this.value).draw(); // Actualiza la búsqueda en DataTables
                });
            }, */
            "bDestroy": true,
            "iDisplayLength": 10,
            "order": [[0, "desc"]]
        }).DataTable();
} 
