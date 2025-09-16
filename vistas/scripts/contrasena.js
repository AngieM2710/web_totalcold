var tabla;
//Función que se ejecuta al inicio
function init() {
    $("#formulario").on("submit", function(e) {
        e.preventDefault(); 
        validarYActualizar();
    });

    //Eventos para validar en tiempo real
    $('#email').on('input', function() {
        validarrequeridos();
    });
    $('#codigo').on('input', function() {
        validarrequeridos();
    });
    $('#clave1').on('input', function() {
        validarrequeridos();
    });
    $('#clave2').on('input', function() {
        validarrequeridos();
    });  
    // Llamar a la función al iniciar para establecer el estado inicial
    validarrequeridos();
    document.getElementById("step1").classList.add("active");
    document.getElementById("content1").classList.add("active");
}
// Función para avanzar
function nextStep(step) {
    document.getElementById(`content${step}`).classList.remove('active');
    document.getElementById(`step${step}`).classList.remove('active');
    document.getElementById(`content${step + 1}`).classList.add('active');
    document.getElementById(`step${step + 1}`).classList.add('active');
  }
  // Función para retroceder
  function prevStep(step) {
    document.getElementById(`content${step}`).classList.remove('active');
    document.getElementById(`step${step}`).classList.remove('active');
    document.getElementById(`content${step - 1}`).classList.add('active');
    document.getElementById(`step${step - 1}`).classList.add('active');
  }


function validarrequeridos() {
    // --- EMAIL ---
    const email = $('#email').val().trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isEmpty = email === '';
    const isEmailValid = emailRegex.test(email);

    if (isEmpty) {
        $('#email-error').show();
        $('#email-error1').hide();
        $('#siguiente1-btn').prop('disabled', true);
    } else if (!isEmailValid) {
        $('#email-error').hide();
        $('#email-error1').show();
        $('#siguiente1-btn').prop('disabled', true);
    } else {
        $('#email-error').hide();
        $('#email-error1').hide();
        $('#siguiente1-btn').prop('disabled', false);
    }

    // --- CÓDIGO ---
    const codigo = $('#codigo').val().trim();
    const iscodValid = codigo !== '';
    $('#siguiente2-btn').prop('disabled', !iscodValid);
    if (!iscodValid) $('#codigo-error').show();
    else $('#codigo-error').hide();

    // --- CONTRASEÑAS ---
    const clave = $('#clave1').val().trim();
    const clave2 = $('#clave2').val().trim();

    if (clave.length >= 8) $('#clave-error').hide();
    else $('#clave-error').show();

    if (clave === clave2 && clave2.length >= 8) $('#clave-error1').hide();
    else $('#clave-error1').show();

    // Botón actualizar
    const isClaveValid = clave.length >= 8 && clave === clave2;
    $('#btnGuardar').prop('disabled', !isClaveValid);
}

function validarCorreo() {
    let correo = $("#email").val().trim();
    if (correo !== '') {
        $.post("../ajax/contrasena.php?op=emailUsuario",
            { "correo": correo },
            function (data) {
                if (data && data !== null) {
                    let usuario = JSON.parse(data);
                    /* console.log("codigo:", usuario.codigo); */

                    // Indicamos que el correo es válido
                    if(usuario.error) {
                        alert(usuario.error);
                        $("#correo-validado").val("0");
                    } else {
                        console.log("codigo:", usuario.codigo);
                        $("#correo-validado").val("1");
                        $("#email-oculto").val(usuario.correo); 
                        $("#id-oculto").val(usuario.id_usuarios); 
                        nextStep(1);
                    }
                    validarrequeridos();
                } else {
                    alert("Correo no encontrado en el sistema.");
                    $("#correo-validado").val("0");
                    validarrequeridos();
                }
            });
    } else {
        $('#correo-error').show();
        $("#correo-validado").val("0");
        validarrequeridos();
    }
}



function validarCodigo() {
    const email = $("#email-oculto").val().trim();
    const codigo = $("#codigo").val().trim(); // código que escribió el usuario

    if (codigo !== '' && email !== '') {
        $.post("../ajax/contrasena.php?op=verificarCodigo",
            { "correo": email, "codigo": codigo },
            function (data) {
                if (data !== "null") {
                    let resultado = JSON.parse(data);
                    console.log("Código correcto:");
                    nextStep(2);
                } else {
                    $('#codigo-error').show();
                    $('#siguiente2-btn').prop('disabled', true);
                    alert("codigo no encontrado en el sistema.");
                }
            });
    } else {
        $('#codigo-error').show();
        $('#siguiente2-btn').prop('disabled', true);
    }
}
function togglePassword(idInput) {
    const input = document.getElementById(idInput);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}

    function validarYActualizar() {
        const clave = $("#clave1").val().trim();
        const clave2 = $("#clave2").val().trim();
        const id_usuario = $("#id-oculto").val().trim();
        console.log("id_usuarios ",id_usuario);

        // Ocultar mensajes de error al iniciar
        $("#clave-error").hide();
        $("#clave-error1").hide();

        if (clave === '' || clave2 === '') {
            if (clave.length < 8) {
                $("#clave-error").show();
            }
            if (clave !== clave2) {
                $("#clave-error1").show();
            }
            return; // No seguimos si están vacías
        }
        if (clave.length < 8) {
            $("#clave-error").show();
            return;
        }
        if (clave !== clave2) {
            $("#clave-error1").show();
            return;
        }

        // Crear FormData con solo la contraseña y el id
        const formData = new FormData();
        formData.append("password", clave);
        formData.append("id_usuarios", id_usuario); /// Solo el id de usuario y la nueva contraseña
        actualizarClave(formData);
    }

    function actualizarClave(formData) {
        $("#btnGuardar").prop("disabled",true);
         /* var formData = new FormData($("#formulario")[0]);   */
            $.ajax({
               /*url: "../ajax/recuperarcontraseña.php?op=actualizarClave", */
               url: "../ajax/contrasena.php?op=guardaryeditar",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    let resultado = JSON.parse(data);
                    console.log("Cambio de contraseña :" ,resultado );
                    Swal.fire({
                        title: '<span style="font-size: 24px;">Contraseña actualizada correctamente</span>',
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
                    }).then(() => {
                                  // Vaciar campos
                            $("#clave1").val('');
                            $("#clave2").val('');
                            $("#id-oculto").val('');
                            $("#btnGuardar").prop("disabled", false);

                            // Redireccionar
                            window.location.href = "login.php"; // o a sección4.html
                    })
                }
            });
    }    

init();