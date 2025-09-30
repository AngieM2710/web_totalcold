var tabla;

//Función que se ejecuta al inicio
function init(){
    /* mostrarform(false); */
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);    
    });
}

// Modal 
function abrirModal1(tipo) {
  limpiar();
  if (tipo === "agregar") {
    $("#modalTitle").text("Registro de Cliente");
    var modal = new bootstrap.Modal(document.getElementById("modal"));
    modal.show();
  }
}



// Listar clientes
function listar() {
    tabla = $('#tbllistado').DataTable({   // <--- aquí
        "lengthMenu": [[5, 10, 25, 75, 100], [5, 10, 25, 75, 100]],
        "aProcessing": true,
        "aServerSide": true,
        "dom": '<"row"<"col-sm-9"l><"col-sm-3"f>>rtip',
        "ajax": {
            url: '../ajax/clientes.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e){ console.log(e.responseText); }
        },
        "language": {
            "lengthMenu": "Mostrar : _MENU_ registros",
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
                tabla.search(this.value).draw();  // ahora sí existe .search()
            });
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });
}

function mostrar(id_cliente)
{
	$.post("../ajax/clientes.php?op=mostrar",{id_cliente : id_cliente}, function(data, status)
	{
		data = JSON.parse(data);
        console.log("esto va a presentar en el nav:", data);	
		/* mostrarform(true); */
        // Poner valores en el form
        $("#id_cliente").val(data.id_cliente);
        $("#cedula").val(data.cedula);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#correo").val(data.correo);
        $("#telefono").val(data.telefono);
        $("#estado").val(data.estado);

        $("#modalTitle").text("Editar Cliente");
        var modal = new bootstrap.Modal(document.getElementById("modal"));
        modal.show();

 	});
}

function guardaryeditar(e)
{
	e.preventDefault(); 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/clientes.php?op=guardaryeditar",
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
            //  Cerrar el modal
            var modal = bootstrap.Modal.getInstance(document.getElementById("modal"));
            if (modal) {
                modal.hide();
            }

	        //   mostrarform(false);
	        tabla.ajax.reload();
            limpiar();

            $("#btnGuardar").prop("disabled", false);
            }
            
        });
}

function limpiar(){
    $("#id_cliente").val("");
    $("#cedula").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#telefono").val("");
    $("#correo").val("");
    $("#estado").val("1");
}
	

function desactivar(id_cliente)
{
	Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de desactivar el cliente?</span>',
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
            $.post("../ajax/clientes.php?op=desactivar", {id_cliente: id_cliente}, function(e) {
                Swal.fire({
                    title: '<span style="font-size: 24px;">Cliente desactivado!</span>',
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
                title: '<span style="font-size: 24px;">Cliente no se desactivó</span>',
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
function activar(id_cliente)
{
	Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de activar el cliente?</span>',
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
            $.post("../ajax/clientes.php?op=activar", {id_cliente: id_cliente}, function(e) {
                Swal.fire({
                    title: '<span style="font-size: 24px;">Cliente activado!</span>',
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
                title: '<span style="font-size: 24px;">Cliente no se activó</span>',
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
// Cancelar formulario
/* function cancelarform(){
    limpiar();
    mostrarform(false);
} */

/* // Mostrar formulario
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
 */
init();
