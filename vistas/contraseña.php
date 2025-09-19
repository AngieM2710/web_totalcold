<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../public/img/imagenes/logototalcold.png?v=<?php echo time(); ?>">
    
    <title>Recuperar Contraseña</title>

    <link href="../public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../public/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">    
    <link href="../public/css/login.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../public/css/contraseña.css?v=<?php echo time(); ?>" rel="stylesheet">
    
</head>

<body>
    <div id="bg">
      <canvas id="canvasprueba"></canvas>
    </div>
    <div class="login-container">
        <div class="login-card">
            <div class="row">
                <div class="col-lg-6 d-none d-lg-block left-panel">
                    <div class="image-overlay">
                        <img src="../public/img/imagenes/logototalcold.png" class="logo-image" alt="Logo de TOTAL COLD">
                        <h2 class="letras-slogan">Gestión Técnica Inteligente</h2>
                    </div>
                </div>

                <div class="col-lg-6 p-5 right-panel">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4 letras3">Restablecer Contraseña</h1>
                        <p class="description-text">Este proceso consta de tres pasos para proteger tu cuenta.</p>
                    </div>

                    <div class="stepper">
                        <div class="step active" id="step1">
                            <div class="step-icon">1</div>
                            <div class="step-title letragenera l3">Correo</div>
                        </div>
                        <div class="step" id="step2">
                            <div class="step-icon">2</div>
                            <div class="step-title letragenera l3">Código</div>
                        </div>
                        <div class="step" id="step3">
                            <div class="step-icon">3</div>
                            <div class="step-title letragenera l3">Nueva Clave</div>
                        </div>
                    </div> 

                    <hr class="my-3">

                    <form name="formulario" id="formulario" method="POST" class="user">
                        <div class="step-content active" id="content1">
                            <div class="form-group input-group-new">
                                <label for="email">Ingresa tu correo electrónico</label>
                                <input type="email" class="form-control inpt" name="email" id="email" placeholder="Email" maxlength="100">
                                <div id="email-error" class="error-message">Email requerido</div>
                                <div id="email-error1" class="error-message">Formato de email inválido</div>
                            </div>
                            <input type="hidden" id="email-oculto" name="email">
                            <input type="hidden" id="id-oculto" name="id_usuarios">
                            <button onclick="validarCorreo()" type="button" id="siguiente1-btn" class="btn btn-primary btn-user btn-block letrasboton botonS" disabled>
                                Siguiente
                            </button>
                        </div>

                        <div class="step-content" id="content2">
                            <div class="form-group input-group-new">
                                <label for="codigo">Código de seguridad</label>
                                <input type="text" class="form-control inpt" name="codigo" id="codigo" maxlength="10" placeholder="Código de seguridad" required>
                                <div id="codigo-error" class="error-message">Código requerido</div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-user botonR" onclick="prevStep(2)">Regresar</button>
                            <button type="button" id="siguiente2-btn" class="btn btn-primary btn-user botonS" onclick="validarCodigo()" disabled>
                                Siguiente
                            </button>
                        </div>

                        <div class="step-content" id="content3">
                            <div class="form-group input-group-new">
                                <label for="clave1">Nueva Contraseña</label>
                                <input type="password" class="form-control inpt" name="clave1" id="clave1" maxlength="64" placeholder="Nueva Clave" required>
                                    <i class="fa fa-eye toggle-password-new2" onclick="togglePassword('clave1')" 
                                     style="position: absolute; right: 60px; top: 53%; transform: translateY(-50%); cursor: pointer;"></i>
                                <div id="clave-error" class="error-message">Clave debe tener mín 8 caracteres</div>
                            </div>
                            <div class="form-group input-group-new">
                                <label for="clave2">Repita la Contraseña</label>
                                <input type="password" class="form-control inpt" name="clave2" id="clave2" maxlength="64" placeholder="Repita la Clave" required>
                                <i class="fa fa-eye toggle-password-new2" onclick="togglePassword('clave2')" 
                                     style="position: absolute; right: 60px; top: 71%; transform: translateY(-50%); cursor: pointer;"></i>

                             <!--    <span class="toggle-password-new" onclick="togglePassword('clave2')">👁️</span> -->
                                <div id="clave-error1" class="error-message">Las contraseñas no coinciden</div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-user botonR" onclick="prevStep(3)">Regresar</button>
                            <button type="submit" id="btnGuardar" class="btn btn-primary btn-user botonS">
                                Actualizar Contraseña
                            </button>
                        </div>
                    </form>

                    <hr class="my-3">
                    <div class="text-center">
                        <a class="small letrasA" href="login.php">¿Ya tienes una cuenta? Inicia sesión aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../public/vendor/jquery/jquery.min.js"></script>
    <script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../public/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="scripts/contrasena.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../public/js/demo/c2.js"></script>

    <script>
        function togglePassword(idInput) {
            const input = document.getElementById(idInput);
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>