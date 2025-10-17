$(document).ready(function() {
    function validateForm() {
        const username = $('#emailusuario').val().trim();
        const password = $('#clavea').val().trim();
        const isUsernameValid = username !== '';
        const isPasswordValid = password !== '';
        
        $('#submit-btn').prop('disabled', !(isUsernameValid && isPasswordValid));
        
        $('#username-error').toggle(!isUsernameValid);
        $('#password-error').toggle(!isPasswordValid);
    }

    // Validación inicial
    validateForm();
    // Escuchar cambios
    $('#emailusuario, #clavea').on('input', validateForm);

    // Ocultar spinner al inicio
    $("#loading-spinner").hide();

    $("#frmAcceso").on('submit', function(e) {
        e.preventDefault();
        validateForm();

        if ($('#submit-btn').is(':disabled')) return;

        $("#loading-spinner").css({
            'display': 'flex',
            'align-items': 'center',
            'justify-content': 'center'
        });

        const startTime = Date.now();
        const minDuration = 1000;

        $.post("../ajax/usuarios.php?op=verificar", { 
            "correo": $("#emailusuario").val(), 
            "password": $("#clavea").val() ,
            "recordar": true // Forzamos a true para probar

        }, function(data) {
            const elapsed = Date.now() - startTime;
            const remaining = Math.max(minDuration - elapsed, 0);

            setTimeout(function() {
                $("#loading-spinner").fadeOut(200);
/*                 if (data != "null") {
                    console.log("✅ Login correcto:", data);
                    window.location.href = "usuarios.php";
                } else {
                    console.log("❌ Credenciales incorrectas");
                } */
                    try {
                        // Intentar parsear como JSON
                        let response;
                        if (typeof data === 'string') {
                            response = JSON.parse(data);
                        } else {
                            response = data;
                        }
                        
                        if (response.status === 'ok') {
                            console.log("✅ Login correcto, redirigiendo...");
                            if(response.usuario.id_permiso==1){
                                window.location.href = "usuarios.php";
                            }else{
                                window.location.href = "agenda_tecnico.php";
                            }
                            //console.log("Usuario:", response.usuario.id_permiso);
                        } else {
                            console.log("❌ Error:", response.msg);
                            alert("Error: " + (response.msg || 'Credenciales incorrectas'));
                        }
                    } catch (e) {
                        console.log("❌ Error parseando respuesta:", e, "Data:", data);
                        alert("Error en la respuesta del servidor");
                    }
            }, remaining);
            
        }).fail(function() {
            $("#loading-spinner").fadeOut(200);
            console.log("⚠️ Error al conectar con el servidor");
        });
    });
});
