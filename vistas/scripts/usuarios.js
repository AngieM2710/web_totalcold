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
            url: '../ajax/usuarios.php?op=listar',
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

    // Si hay imagen nueva
    if($("#imagen")[0].files.length > 0){
        formData.append("imagen", $("#imagen")[0].files[0]);
    } else {
        formData.delete("imagen");
    }

    $.ajax({
        url: "../ajax/usuarios.php?op=guardaryeditar",
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

// Mostrar usuario
function mostrar(id_usuarios){
    $('#imagen').val('');
    $('#file-name').text('');
    imagenSeleccionada = null;

    $.post("../ajax/usuarios.php?op=mostrar",{id_usuarios: id_usuarios}, function(data){
        data = JSON.parse(data);        
        mostrarform(true);

        $("#id_usuarios").val(data.id_usuarios);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#correo").val(data.correo);
        $("#password").val("");
        $("#telefono").val(data.telefono);
        $("#estado").val(data.estado);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
        $("#imagenactual").val(data.imagen);
    });
}

// Vista previa de imagen
function imgtemp(){
    document.getElementById("imagen").addEventListener("change", function(e){
        const file = e.target.files[0];
        if(file){
            if(file.type.startsWith("image/")){
                const reader = new FileReader();
                reader.onload = function(e){
                    const preview = document.getElementById("imagenmuestra");
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Archivo no válido',
                    text: 'Por favor selecciona una imagen (JPG, PNG, GIF).'
                });
                e.target.value = '';
            }
        }
    });
}

// Desactivar usuario
function desactivar(id_usuarios){
    Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de desactivar el usuario?</span>',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 18px;">Sí</span>',
        denyButtonText: '<span style="font-size: 18px;">No</span>',
        cancelButtonText: '<span style="font-size: 18px;">Cancelar</span>',
        width: '600px'
    }).then((result)=>{
        if(result.isConfirmed){
            $.post("../ajax/usuarios.php?op=desactivar",{id_usuarios: id_usuarios}, function(e){
                Swal.fire({
                    title: '<span style="font-size: 24px;">Usuario desactivado!</span>',
                    icon: "success",
                    width: '600px'
                });
                tabla.ajax.reload();
            });
        }
    });
}

// Activar usuario
function activar(id_usuarios){
    Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de activar el usuario?</span>',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 18px;">Sí</span>',
        denyButtonText: '<span style="font-size: 18px;">No</span>',
        cancelButtonText: '<span style="font-size: 18px;">Cancelar</span>',
        width: '600px'
    }).then((result)=>{
        if(result.isConfirmed){
            $.post("../ajax/usuarios.php?op=activar",{id_usuarios: id_usuarios}, function(e){
                Swal.fire({
                    title: '<span style="font-size: 24px;">Usuario activado!</span>',
                    icon: "success",
                    width: '600px'
                });
                tabla.ajax.reload();
            });
        }
    });
}

init();
