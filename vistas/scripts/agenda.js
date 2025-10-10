var tabla;
function init(){
    listar();
    listartabla();
}


function listar() {
    console.log("Listando citas desde la base de datos...");

    $.ajax({
        url: '../ajax/agenda.php?op=listarcard',
        type: "GET",
        dataType: "json",
        success: function(response) {
            /* console.log("Datos recibidos:", response); */

            // Limpia el contenedor
            $('#listadoregistros').html('');

            // Verifica que existan datos
            if (response && response.aaData && response.aaData.length > 0) {
                response.aaData.forEach(function(item) {
                    var card = `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                          ${item.fecha} - Hora:  ${item.hora} - Cliente: ${item.cliente} - ${item.servicio}
                          ${item.estado}
                        </div>
                    `;
                    $('#listadoregistros').append(card);
                });
            } else {
                $('#listadoregistros').html('<div class="list-group-item">No hay citas programadas.</div>');
            }
        },
        error: function(e) {
            console.log("Error en listarcard(): ", e.responseText);
        }
    });
}

// Llamar cuando cargue la página
$(document).ready(function() {
    init();
});

 function listartabla()
{
	tabla=$('#tbllistado').dataTable(
	{
		"lengthMenu": [[ 5, 10, 25, 75, 100],[5, 10, 25, 75, 100]], 
		"aProcessing": true,
	    "aServerSide": true,
        "dom": '<"row"<"col-sm-9"l><"col-sm-3"f>>rtip', 
		"ajax":
				{
					url: '../ajax/agenda.php?op=listartab',
					type : "get",
					dataType : "json",						
					error: function(e){
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
        "initComplete": function() {
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
        },
		"bDestroy": true,
		"iDisplayLength": 10,
	    "order": [[ 0, "desc" ]]
	}).DataTable();
} 