var tabla;
    cargarClientes();
    cargarTecnicos();
    cargarServicios();
    estadoServicio();

    //Función que se ejecuta al inicio
    function init(){
}
function cargarClientes(){
    $.post("../ajax/clientes.php?op=selectClientes", function(r){ 
        let opciones = '<option value="">Nombre del Cliente </option>' + r;
            $("#id_cli").html(opciones);// cargamos las opciones
            $('#id_cli').selectpicker('refresh');// refrescamos bootstrap-select
    });
}
function cargarTecnicos(){
    $.post("../ajax/tecnicos.php?op=selectTenicos", function(r){ 
        let opciones = '<option value="">Nombre del Técnico </option>' + r;
            $("#id_tec").html(opciones);// cargamos las opciones
            $('#id_tec').selectpicker('refresh');// refrescamos bootstrap-select
    });
}
function cargarServicios(){
    $.post("../ajax/categorias.php?op=selectServicios", function(r){ 
        let opciones = '<option value="">Tipo de Servicio </option>' + r;
            $("#id_serv").html(opciones);// cargamos las opciones
            $('#id_serv').selectpicker('refresh');// refrescamos bootstrap-select
    });
}

function estadoServicio() {
  const select = document.getElementById('estadoServicio');

  // Inicializar selectpicker
  $(select).selectpicker();

  // Función para cambiar color del botón
  function actualizarColor() {
    const button = select.closest('.bootstrap-select').querySelector('.dropdown-toggle');
    
    // Limpiar clases previas
    button.classList.remove('btn-danger', 'btn-success', 'btn-light', 'text-white');

    if (select.value === 'Pendiente') {
      button.classList.add('btn-danger', 'text-white'); // rojo
    } else if (select.value === 'Terminado') {
      button.classList.add('btn-success', 'text-white'); // verde
    } else {
      button.classList.add('btn-light'); // color por defecto
    }
  }

  // Ejecutar al cambiar selección
  select.addEventListener('changed.bs.select', actualizarColor);

  // Ejecutar al iniciar para reflejar valor inicial
  actualizarColor();
}

// Llamar la función después de que el DOM esté listo
document.addEventListener('DOMContentLoaded', estadoServicio);



/* function filtrarCliente(){
    let texto = $("#buscarCli").val();

    if(texto.length < 2){ // mínimo 2 letras para buscar
        $("#listaClientes").html("");
        return;
    }

    $.ajax({
        url: "../ajax/clientes.php?op=buscar",
        type: "POST",
        data: { texto: texto },
        success: function(respuesta){
            let clientes = JSON.parse(respuesta);
            let html = "";
            clientes.forEach(c => {
                html += `<li class="list-group-item list-group-item-action"
                              onclick="seleccionarCliente('${c.id}','${c.nombre}')">
                              ${c.nombre}
                         </li>`;
            });
            $("#listaClientes").html(html);
        }
    });
}

function seleccionarCliente(id, nombre){
    $("#id_cliente").val(id);
    $("#buscarCli").val(nombre);
    $("#listaClientes").html(""); // limpiar sugerencias
} */

init();