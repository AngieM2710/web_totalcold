var tabla;

//Función que se ejecuta al inicio
function init(){
	/* mostrarform(false); */
	listar();
    capturarimg();
	$("#formulario").on("submit",function(e){
		guardaryeditar(e);	
	})
}

function limpiar()
{
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

function abrirModal(tipo) {
  limpiar();
  if (tipo === "agregar") {
    $("#modalTitle").text("Registro de Técnico");
    var modal = new bootstrap.Modal(document.getElementById("tecnicoModal"));
    modal.show();
  }
}



//Función Listar
 function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"lengthMenu": [[ 5, 10, 25, 75, 100],[5, 10, 25, 75, 100]], 
		"aProcessing": true,
	    "aServerSide": true,
        "dom": '<"row"<"col-sm-9"l><"col-sm-3"f>>rtip', 
		"ajax":
				{
					url: '../ajax/tecnicos.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){	console.log(e.responseText);}
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

function guardaryeditar(e)
{
	e.preventDefault(); 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/tecnicos.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
			Swal.fire({
                title: '<span style="font-size: 24px;">'+datos+'</span>',
                icon: "success",
                width: '600px',
                customClass: {
                    popup: "mi-alerta-personalizada",
                    confirmButton: 'swal2-confirm'
                },
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    confirmButton.style.fontSize = '18px'; 
                    confirmButton.style.padding = '10px 24px';
                }
            });

            // Cerrar el modal
            var modal = bootstrap.Modal.getInstance(document.getElementById("tecnicoModal"));
            if (modal) {
                modal.hide();
            }

            tabla.ajax.reload();
            limpiar();
            //  volver a habilitar botón
            $("#btnGuardar").prop("disabled", false);
	    }
	});
}
function capturarimg(){
document.addEventListener('DOMContentLoaded', function() {
    const imagenInput = document.getElementById("imagen");
    const imagenMuestra = document.getElementById("imagenmuestra");
    const fileName = document.getElementById("file-name");
    
    if (imagenInput && imagenMuestra) {
        imagenInput.addEventListener("change", function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validar tipo de archivo
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire('Error', 'Solo se permiten imágenes JPG, JPEG o PNG', 'error');
                    this.value = '';
                    return;
                }
                
                // Validar tamaño (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('Error', 'La imagen no debe superar los 2MB', 'error');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagenMuestra.src = event.target.result;
                    fileName.textContent = file.name;
                    fileName.style.color = '#28a745';
                };
                reader.readAsDataURL(file);
            } else {
                imagenMuestra.src = "img/default-user.png";
                fileName.textContent = 'Ningún archivo seleccionado';
                fileName.style.color = '#666';
            }
        });
    }
});
}
function mostrar(id_usuarios)
{
	$.post("../ajax/tecnicos.php?op=mostrar",{id_usuarios : id_usuarios}, function(data)
	{
		data = JSON.parse(data);
        $("#id_usuarios").val(data.id_usuarios);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#correo").val(data.correo);
        $("#password").val(""); // vacío, nunca muestres el hash
        $("#telefono").val(data.telefono);
        $("#estado").val(data.estado);
        $("#imagenmuestra").attr("src","../files/usuarios/tecnicos/"+data.imagen);
        $("#imagenactual").val(data.imagen);

        $("#modalTitle").text("Editar Técnico");
        var modal = new bootstrap.Modal(document.getElementById("tecnicoModal"));
        modal.show();
 	});
}

function desactivar(id_usuarios)
{
	Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de desactivar el técnico?</span>',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 18px;">Sí</span>',
        denyButtonText: '<span style="font-size: 18px;">No</span>',
        cancelButtonText: '<span style="font-size: 18px;">Cancelar</span>',
        width: '600px',
        customClass: {
            popup: "mi-alerta-personalizada",
            confirmButton: 'swal2-confirm',
            denyButton: 'swal2-deny',
            cancelButton: 'swal2-cancel'
        },
        didOpen: () => {
            const confirmButton = Swal.getConfirmButton();
            const denyButton = Swal.getDenyButton();
            const cancelButton = Swal.getCancelButton();
            
            confirmButton.style.padding = '10px 24px';
            denyButton.style.padding = '10px 24px';
            cancelButton.style.padding = '10px 24px';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/tecnicos.php?op=desactivar", {id_usuarios: id_usuarios}, function(e) {
                Swal.fire({
                    title: '<span style="font-size: 24px;">Técnico desactivado!</span>',
                    text: "",
                    icon: "success",
                    width: '600px', 
                    customClass: {
                        popup: "mi-alerta-personalizada",
                        confirmButton: 'swal2-confirm'
                    },
                    didOpen: () => {
                        const confirmButton = Swal.getConfirmButton();
                        confirmButton.style.padding = '10px 24px';
                    }
                });
                tabla.ajax.reload();
            });
        } else if (result.isDenied) {
            Swal.fire({
                title: '<span style="font-size: 24px;">Técnico no se desactivó</span>',
                text: "",
                icon: "info",
                width: '600px',
                customClass: {
                    popup: "mi-alerta-personalizada",
                    confirmButton: 'swal2-confirm'
                },
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    confirmButton.style.padding = '10px 24px';
                }
            });
        }
    });
}

//Función para activar registros
function activar(id_usuarios)
{
	Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de activar el técnico?</span>',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 18px;">Sí</span>',
        denyButtonText: '<span style="font-size: 18px;">No</span>',
        cancelButtonText: '<span style="font-size: 18px;">Cancelar</span>',
        width: '600px',
        customClass: {
            popup: "mi-alerta-personalizada",
            confirmButton: 'swal2-confirm',
            denyButton: 'swal2-deny',
            cancelButton: 'swal2-cancel'
        },
        didOpen: () => {
            const confirmButton = Swal.getConfirmButton();
            const denyButton = Swal.getDenyButton();
            const cancelButton = Swal.getCancelButton();
            
            confirmButton.style.padding = '10px 24px';
            denyButton.style.padding = '10px 24px';
            cancelButton.style.padding = '10px 24px';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/tecnicos.php?op=activar", {id_usuarios: id_usuarios}, function(e) {
                Swal.fire({
                    title: '<span style="font-size: 24px;">Técnico activado!</span>',
                    text: "",
                    icon: "success",
                    width: '600px', 
                    customClass: {
                        popup: "mi-alerta-personalizada",
                        confirmButton: 'swal2-confirm'
                    },
                    didOpen: () => {
                        const confirmButton = Swal.getConfirmButton();
                        confirmButton.style.padding = '10px 24px';
                    }
                });
                tabla.ajax.reload();
            });
        } else if (result.isDenied) {
            Swal.fire({
                title: '<span style="font-size: 24px;">Técnico no se activó</span>',
                text: "",
                icon: "info",
                width: '600px',
                customClass: {
                    popup: "mi-alerta-personalizada",
                    confirmButton: 'swal2-confirm'
                },
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    confirmButton.style.padding = '10px 24px';
                }
            });
        }
    });
}



init();