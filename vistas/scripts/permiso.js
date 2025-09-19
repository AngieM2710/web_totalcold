var tabla;

function init(){ //hace referencia a otras funciones
    mostrarform(false);
    listar();

    //se reciben peticiones del formulario para guardar y editar
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
    });

/*     $.post("../ajax/usuario.php?op=codigosqr&id=",function(r){
        $("#codigo").html(r);
}); */
}

function limpiar(){
    $("#id_permiso").val("");
    $("#nombre").val("");
}

function mostrarform(flag){
    limpiar();
    if(flag){ //si flag es true muestra el formulario
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
    }
    else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

function cancelarform(){
    limpiar();
    mostrarform(false);
}

function listar(){
    tabla=$('#tbllistado').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
          /*    'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'  */
        ],
        
        "ajax":
        {
            url: "../ajax/permiso.php?op=listar",
            type: "get",
            dataType: "json",
            error: function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 8, //paginacion
        "order": [[0, "desc"]] //orden
    }).DataTable();
}


function guardaryeditar(e){
    e.preventDefault(); //no se activa la accion predeterminada del evento
  //  $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/permiso.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos)
        { 
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
            listar();
         
        }
    });
    limpiar();
}

//muestra los datos que se quieren modificar
function mostrar(id_permiso){
    $.post("../ajax/permiso.php?op=mostrar",{id_permiso : id_permiso}, function(data, status){
        data = JSON.parse(data);
        mostrarform(true);
        $("#id_permiso").val(data.id_permiso);
        $("#nombre").val(data.nombre);
    })

}

init();