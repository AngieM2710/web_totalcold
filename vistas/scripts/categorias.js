var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e){
		guardaryeditar(e);	
	})
}

function limpiar()
{
    $("#id_servicios").val("");
	$("#descripcion").val("");
}

function mostrarform(flag)
{
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btnreporte").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btnreporte").show();
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
					url: '../ajax/categorias.php?op=listar',
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


function guardaryeditar(e)
{
	e.preventDefault(); 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/categorias.php?op=guardaryeditar",
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
	          mostrarform(false);
	          tabla.ajax.reload();
	    }

	});
	limpiar();
}

function mostrar(id_servicios)
{
	$.post("../ajax/categorias.php?op=mostrar",{id_servicios : id_servicios}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
        // Poner valores en el form
		$("#id_servicios").val(data.id_servicios);
		$("#descripcion").val(data.descripcion);
 	});
}

function desactivar(id_servicios)
{
	Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de desactivar la categoría?</span>',
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
            $.post("../ajax/categorias.php?op=desactivar", {id_servicios: id_servicios}, function(e) {
                Swal.fire({
                    title: '<span style="font-size: 24px;">Categoría desactivada!</span>',
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
                title: '<span style="font-size: 24px;">Categoría no se desactivó</span>',
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
function activar(id_servicios)
{
	Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de activar la categoría?</span>',
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
            $.post("../ajax/categorias.php?op=activar", {id_servicios: id_servicios}, function(e) {
                Swal.fire({
                    title: '<span style="font-size: 24px;">Categoría activada!</span>',
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
                title: '<span style="font-size: 24px;">Categoría no se activó</span>',
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

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

init();