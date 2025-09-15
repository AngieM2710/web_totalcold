var tabla;

//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);    
    });
}

// Función limpiar formulario
function limpiar() {
    $("#id_equipo").val("");
    $("#codigo_interno").val("");
    $("#marca").val("");
    $("#modelo").val("");
    $("#capacidad").val("");
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

// Listar equipos
function listar() {
    tabla = $('#tbllistado').DataTable({
        "lengthMenu": [[5, 10, 25, 75, 100], [5, 10, 25, 75, 100]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: '../ajax/equipos.php?op=listar',
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
    });
}

// Guardar y editar
function guardaryeditar(e){
    e.preventDefault();
    $("#btnGuardar").prop("disabled",true);

    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/equipos.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){
            Swal.fire({
                title: '<span style="font-size: 24px;">'+datos+'</span>',
                icon: "success",
                width: '600px',
                customClass: {
                    popup: "mi-alerta-personalizada",
                    confirmButton: 'swal2-confirm'
                }
            });
            mostrarform(false);
            tabla.ajax.reload();
        }
    });

    limpiar();
}

// Mostrar equipo
function mostrar(id_equipo){

    $.post("../ajax/equipos.php?op=mostrar",{id_equipo: id_equipo}, function(data){
        data = JSON.parse(data);        
        mostrarform(true);

        $("#id_equipo").val(data.id_equipo);
        $("#codigo_interno").val(data.codigo_interno);
        $("#marca").val(data.marca);
        $("#modelo").val(data.modelo);
        $("#capacidad").val(data.capacidad);
    });
}

// Desactivar equipo
function desactivar(id_equipo){
    Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de desactivar el equipo?</span>',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 18px;">Sí</span>',
        denyButtonText: '<span style="font-size: 18px;">No</span>',
        cancelButtonText: '<span style="font-size: 18px;">Cancelar</span>',
        width: '600px'
    }).then((result)=>{
        if(result.isConfirmed){
            $.post("../ajax/equipos.php?op=desactivar",{id_equipo: id_equipo}, function(e){
                Swal.fire({
                    title: '<span style="font-size: 24px;">Equipo desactivado!</span>',
                    icon: "success",
                    width: '600px'
                });
                tabla.ajax.reload();
            });
        }
    });
}

// Activar usuario
function activar(id_equipo){
    Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de activar el equipo?</span>',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 18px;">Sí</span>',
        denyButtonText: '<span style="font-size: 18px;">No</span>',
        cancelButtonText: '<span style="font-size: 18px;">Cancelar</span>',
        width: '600px'
    }).then((result)=>{
        if(result.isConfirmed){
            $.post("../ajax/equipos.php?op=activar",{id_equipo: id_equipo}, function(e){
                Swal.fire({
                    title: '<span style="font-size: 24px;">Equipo activado!</span>',
                    icon: "success",
                    width: '600px'
                });
                tabla.ajax.reload();
            });
        }
    });
}

init();
