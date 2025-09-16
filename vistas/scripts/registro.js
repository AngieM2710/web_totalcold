var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    //Eventos para validar en tiempo real
    $('#cedula').on('input', function() {
        validarrequeridos();
    });

    $('#nombres').on('input', function() {
        validarrequeridos();
    });

    $('#apellidos').on('input', function() {
        validarrequeridos();
    });

    $('#login').on('input', function() {
        validarrequeridos();
    });

    $('#clave').on('input', function() {
        validarrequeridos();
    });

    // Llamar a la función al iniciar para establecer el estado inicial
    validarrequeridos();
}

function validarrequeridos() {
    const cedula = $('#cedula').val().trim();
    const isCedulaValid = cedula !== '';
    const nombres = $('#nombres').val().trim();
    const isNombresValid = nombres !== '';
    const apellidos = $('#apellidos').val().trim();
    const isApellidosValid = apellidos !== '';

    // Habilitar o deshabilitar el botón
    $('#siguiente1-btn').prop('disabled', !(isCedulaValid && isNombresValid && isApellidosValid));

    // Mostrar u ocultar el mensaje de error para Cédula
    if (!isCedulaValid) {
        $('#cedula-error').show();
    } else {
        $('#cedula-error').hide();
    }

    // Mostrar u ocultar el mensaje de error para Nombres
    if (!isNombresValid) {
        $('#nombres-error').show();
    } else {
        $('#nombres-error').hide();
    }

    if (!isApellidosValid) {
        $('#apellidos-error').show();
    } else {
        $('#apellidos-error').hide();
    }

    /* SEGUNDO STEP ------------------------ */
    const usuario = $('#login').val().trim();
    const isUsuarioValid = usuario !== '';
    const contrasena = $('#clave').val().trim();
    const isContrasenaValid = contrasena !== '';

     // Habilitar o deshabilitar el botón
     $('#siguiente2-btn').prop('disabled', !(isUsuarioValid && isContrasenaValid));

     // Mostrar u ocultar el mensaje de error para Cédula
     if (!isUsuarioValid) {
         $('#usuario-error').show();
         $('#login-repetido').hide();
     } else {
         $('#usuario-error').hide();
         $('#login-repetido').hide();
     }
 
     // Mostrar u ocultar el mensaje de error para Nombres
     if (!isContrasenaValid) {
         $('#contrasena-error').show();
         $('#clave-error').hide();
     } else {
         $('#contrasena-error').hide();
         $('#clave-error').hide();
     }
}

function validarTelefono(input) {
    var telefono = input.value.trim();
    
    if (telefono.length !== 10) {
        $('#tlf-error').show();
        input.focus();
		$("#telefono").val("");
    } else {
        $('#tlf-error').hide();
    }
}

function validarlogin(){
    id_us=$("#id_us").val();
    login=$("#login").val();
    $.post("../ajax/usuario.php?op=validacionlogin",
    {"login":login, "id_us":id_us},
    function(data)
    {
    if (data!="null")
    {
        $("#login").val("");
        $('#login-repetido').show();
    }
    else
    {
        $('#login-repetido').hide();

    }
});
}

function validarLongitud(input) {
    var clave = input.value.trim();
    
    if (clave.length < 8) {
        $('#clave-error').show();
        input.focus();
		$("#clave").val("");
        validarrequeridos();
    } else {
        $('#clave-error').hide();
    }
}

function limpiar()
{
	$("#cedula").val("");
	$("#nombres").val("");
	$("#apellidos").val("");
	$("#telefono").val("");
	$("#direccion").val("");
	$("#email").val("");
	$("#login").val("");
	$("#clave").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#id_us").val("");
	$("#id_permiso").val("");
	$('#id_permiso').selectpicker('refresh');
	$("#imagenmuestra").hide();
}

function validar(){
    cedula=$("#cedula").val();

    if(cedula.length == 10){
        
        var digito_region = cedula.substring(0,2);
        
        if( digito_region >= 1 && digito_region <=24 ){
        
        var ultimo_digito   = cedula.substring(9,10);

        var pares = parseInt(cedula.substring(1,2)) + parseInt(cedula.substring(3,4)) + parseInt(cedula.substring(5,6)) + parseInt(cedula.substring(7,8));

        var numero1 = cedula.substring(0,1);
        var numero1 = (numero1 * 2);
        if( numero1 > 9 ){ var numero1 = (numero1 - 9); }

        var numero3 = cedula.substring(2,3);
        var numero3 = (numero3 * 2);
        if( numero3 > 9 ){ var numero3 = (numero3 - 9); }

        var numero5 = cedula.substring(4,5);
        var numero5 = (numero5 * 2);
        if( numero5 > 9 ){ var numero5 = (numero5 - 9); }

        var numero7 = cedula.substring(6,7);
        var numero7 = (numero7 * 2);
        if( numero7 > 9 ){ var numero7 = (numero7 - 9); }

        var numero9 = cedula.substring(8,9);
        var numero9 = (numero9 * 2);
        if( numero9 > 9 ){ var numero9 = (numero9 - 9); }

        var impares = numero1 + numero3 + numero5 + numero7 + numero9;

        var suma_total = (pares + impares);

        var primer_digito_suma = String(suma_total).substring(0,1);

        var decena = (parseInt(primer_digito_suma) + 1)  * 10;

        var digito_validador = decena - suma_total;

        if(digito_validador == 10)
            var digito_validador = 0;

        if(digito_validador == ultimo_digito){
            cedula=$("#cedula").val();
            id_us=$("#id_us").val();
            $.post("../ajax/usuario.php?op=validacionusuario",
            {"cedula":cedula, "id_us":id_us},
            function(data)
            {
            if (data!="null")
            {
                $("#cedula").val("");
                $('#cedula-repetida').show();
                $('#cedula-error').hide();
            }
            else
            {
                $('#cedula-error').hide();
                $('#cedula-repetida').hide();
            }
        });
        }else{
            $("#cedula").val("");
            $('#cedula-error').show();
            $('#cedula-repetida').hide();
        }
        
        }else{
        $("#cedula").val("");
        $('#cedula-error').show();
        $('#cedula-repetida').hide();
    }
    }else{
        $("#cedula").val("");
        $('#cedula-error').show();
        $('#cedula-repetida').hide();
    }  
}

//Función mostrar formulario
function mostrarform(flag)
{
	$("#id_us").val("");
	//limpiar();
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

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: [		          
		            /* 'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf' */
		        ],
		"ajax":
				{
					url: '../ajax/registro.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"language": {
            "lengthMenu": "Mostrar : _MENU_ registros",
            "buttons": {
            "copyTitle": "Tabla Copiada",
            "copySuccess": {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/registro.php?op=guardaryeditar",
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

function mostrar(id_us)
{
	$.post("../ajax/registro.php?op=mostrar",{id_us : id_us}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		
		$("#id_us").val(data.id_us);
		$("#cedula").val(data.cedula);
		$("#nombres").val(data.nombres);
		$("#apellidos").val(data.apellidos);
		$("#telefono").val(data.telefono);
		$("#direccion").val(data.direccion);
		$("#email").val(data.email);
		$("#login").val(data.login);
		$("#clave").val(data.clave);
		$("#id_permiso").val(data.id_permiso);
		$('#id_permiso').selectpicker('refresh');
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen_us);
		$("#imagenactual").val(data.imagen_us);

 	});
 	$.post("../ajax/registro.php?op=permisos&id="+id_us,function(r){
	        $("#permisos").html(r);
	});
}

function desactivar(id_us)
{
	Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de desactivar el usuario?</span>',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 18px;">Sí</span>',
        denyButtonText: '<span style="font-size: 18px;">No</span>',
        cancelButtonText: '<span style="font-size: 18px;">Cancelar</span>',
        width: '600px',
        customClass: {
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
            $.post("../ajax/registro.php?op=desactivar", {id_us: id_us}, function(e) {
                Swal.fire({
                    title: '<span style="font-size: 24px;">Usuario desactivado!</span>',
                    text: "",
                    icon: "success",
                    width: '600px', 
                    customClass: {
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
                title: '<span style="font-size: 24px;">Usuario no se desactivó</span>',
                text: "",
                icon: "info",
                width: '600px',
                customClass: {
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
function activar(id_us)
{
	Swal.fire({
        title: '<span style="font-size: 24px;">¿Está seguro de activar el usuario?</span>',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 18px;">Sí</span>',
        denyButtonText: '<span style="font-size: 18px;">No</span>',
        cancelButtonText: '<span style="font-size: 18px;">Cancelar</span>',
        width: '600px',
        customClass: {
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
            $.post("../ajax/registro.php?op=activar", {id_us: id_us}, function(e) {
                Swal.fire({
                    title: '<span style="font-size: 24px;">Usuario activado!</span>',
                    text: "",
                    icon: "success",
                    width: '600px', 
                    customClass: {
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
                title: '<span style="font-size: 24px;">Usuario no se activó</span>',
                text: "",
                icon: "info",
                width: '600px',
                customClass: {
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