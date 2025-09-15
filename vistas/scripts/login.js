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
            "password": $("#clavea").val() 
        }, function(data) {
            const elapsed = Date.now() - startTime;
            const remaining = Math.max(minDuration - elapsed, 0);

            setTimeout(function() {
                $("#loading-spinner").fadeOut(200);
                if (data != "null") {
                    console.log("✅ Login correcto:", data);
                    window.location.href = "usuarios.php";
                } else {
                    console.log("❌ Credenciales incorrectas");
                }
            }, remaining);
            
        }).fail(function() {
            $("#loading-spinner").fadeOut(200);
            console.log("⚠️ Error al conectar con el servidor");
        });
    });
});
