var tabla;
function init(){
    /* mostrarform(false); */
    listar();
    capturarimg();
    mostrarTotalTecnicos();
    $("#formulario").on("submit", function(e){
        guardaryeditar(e);    
    });
}

function mostrarTotalTecnicos(){
    $.post("../ajax/usuarios.php?op=total", function(data){
        data = JSON.parse(data);
        // Actualizas el span en tu vista
        $("#totalTecnicos").text(data.total);
        $("#activos").text(data.activos);
        $("#inactivos").text(data.inactivos);
    });
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

// Modal 
function abrirModal1(tipo) {
  limpiar();
  if (tipo === "agregar") {
    $("#modalTitle").text("Registro de Usuario");
    var modal = new bootstrap.Modal(document.getElementById("modal"));
    modal.show();
  }
}

// Listar usuarios
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
					url: '../ajax/usuarios.php?op=listar',
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


// Reemplaza tu script actual por este más robusto
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
// Guardar y editar
function guardaryeditar(e)
{
	e.preventDefault(); 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/usuarios.php?op=guardaryeditar",
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


// Mostrar usuario
function mostrar(id_usuarios){

    $.post("../ajax/usuarios.php?op=mostrar",{id_usuarios: id_usuarios}, function(data){
        data = JSON.parse(data); 
          
        $("#id_usuarios").val(data.id_usuarios);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#correo").val(data.correo);
        $("#password").val("");
        /* $("#password").val(data.password); */
        $("#telefono").val(data.telefono);
        $("#estado").val(data.estado);
     /*    $("#imagenmuestra").show(); */
        $("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
        $("#imagenactual").val(data.imagen);
        
        $("#modalTitle").text("Editar Usuario");
        var modal = new bootstrap.Modal(document.getElementById("modal"));
        modal.show();
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

// Mostrar formulario
/* function mostrarform(flag){
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
} */

// Cancelar formulario
/* function cancelarform(){
    limpiar();
    mostrarform(false);
} */
init();
