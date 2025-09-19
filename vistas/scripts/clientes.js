var tabla;

//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    imgtemp();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);    
    });

    $("#imagenmuestra").hide();
}

// Función limpiar formulario
function limpiar() {
    $("#id_usuarios").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#correo").val("");
    $("#password").val("");
    $("#telefono").val("");
    $("#estado").val("1");
    $("#imagenmuestra").attr("src","img/default-user.png");
    $("#imagen").val("");
    $("#imagenactual").val("");
}

// Mostrar formulario
function mostrarform(flag){
    if(flag){
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        $("#btnreporte").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnreporte").show();
    }
}

// Cancelar formulario
function cancelarform(){
    limpiar();
    mostrarform(false);
}

// Listar usuarios
function listar() {
    tabla = $('#tbllistado').DataTable({
        "lengthMenu": [[5, 10, 25, 75, 100], [5, 10, 25, 75, 100]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: '../ajax/clientes.php?op=listar',
            type: "GET",
            dataType: "json",
            error: function(e){ console.log(e.responseText); }
        },
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "search": "",
            "searchPlaceholder": "Buscar...",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente"
            }
        },
        "initComplete": function() {
            $('.dataTables_filter').html(`
                <div class="input-group">
                    <input type="search" id="customSearch" class="form-control" placeholder="Buscar...">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            `);

            $('#customSearch').on('keyup', function() {
                tabla.search(this.value).draw();
            });
        },
        "destroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    }).DataTable();
}

init();
