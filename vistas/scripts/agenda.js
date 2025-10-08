var tabla;
function init(){
    listar();
}


function listar() {
    console.log("Listando citas desde la base de datos...");

    $.ajax({
        url: '../ajax/agenda.php?op=listarcard',
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log("Datos recibidos:", response);

            // Limpia el contenedor
            $('#listadoregistros').html('');

            // Verifica que existan datos
            if (response && response.aaData && response.aaData.length > 0) {
                response.aaData.forEach(function(item) {
                    var card = `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            ${item.hora} - Cliente: ${item.cliente} - ${item.servicio}
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