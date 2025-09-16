<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../files/logo/slogan1.png">
    
    <title>Registro</title>

    <link href="../public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../public/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <link href="../public/css/contraseña.css" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="row">
                <div class="col-lg-4 d-none d-lg-block left-panel">
                    <div class="image-overlay">
                        <img src="../public/img/imagenes/logo.png" class="logo-image" alt="Logo de TOTAL COLD">
                        <h2 class="letras-slogan">Gestión Técnica Inteligente</h2>
                    </div>
                </div>

                <div class="col-lg-8 p-5 right-panel">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4 letras3">Regístrate</h1>
                        <p class="description-text">Completa los siguientes pasos para crear tu cuenta.</p>
                    </div>

                    <div class="stepper">
                        <div class="step active" id="step1">
                            <div class="step-icon">1</div>
                            <div class="step-title">Datos Personales</div>
                        </div>
                        <div class="step" id="step2">
                            <div class="step-icon">2</div>
                            <div class="step-title">Credenciales</div>
                        </div>
                        <div class="step" id="step3">
                            <div class="step-icon">3</div>
                            <div class="step-title">Confirmación</div>
                        </div>
                    </div>
                    
                    <hr class="my-3">

                    <form name="formulario" id="formulario" method="POST" class="user">
                        <div class="step-content active" id="content1">
                            <div class="form-group input-group-new">
                                <label for="cedula">Cédula</label>
                                <input type="hidden" name="id_us" id="id_us">
                                <input onchange="validar()" required type="number" class="form-control inpt" name="cedula" id="cedula" placeholder="Cédula" maxlength="10">
                                <div id="cedula-error" class="error-message">Cédula inválida</div>
                                <div id="cedula-repetida" class="error-message">Cédula ya está registrada</div>
                            </div>
                            <div class="form-group input-group-new">
                                <label for="nombres">Nombres</label>
                                <input required type="text" onkeydown="return /[a-z, ]/i.test(event.key)" class="form-control inpt" name="nombre" id="nombre" maxlength="100" placeholder="Nombres">
                                <div id="nombres-error" class="error-message">Nombres requeridos</div>
                            </div>
                            <div class="form-group input-group-new">
                                <label for="apellidos">Apellidos</label>
                                <input required type="text" onkeydown="return /[a-z, ]/i.test(event.key)" class="form-control inpt" name="apellido" id="apellido" maxlength="20" placeholder="Apellidos">
                                <div id="apellidos-error" class="error-message">Apellidos requeridos</div>
                            </div>
                            <div class="form-group input-group-new">
                                <label for="telefono">Teléfono</label>
                                <input onchange="validarTelefono(this);" type="number" class="form-control inpt" name="telefono" id="telefono" maxlength="10" placeholder="Teléfono">
                                <div id="tlf-error" class="error-message">Teléfono inválido</div>
                            </div>
                            <div class="form-group input-group-new">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control inpt" name="email" id="email" placeholder="Email" maxlength="100">
                                <div id="email-error" class="error-message">Email requerido</div>
                            </div>
                            <div class="form-group input-group-new">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control inpt" name="direccion" id="direccion" placeholder="Dirección" maxlength="70">
                                <div id="direccion-error" class="error-message">Dirección requerida</div>
                            </div>
                            <div class="form-group input-group-new">
                                <label>Foto:</label>
                                <input class="form-control inpt" type="file" name="imagen_us" id="imagen_us" accept="image/x-png,image/gif,image/jpeg">
                            </div>
                            <button type="button" id="siguiente1-btn" class="btn btn-primary btn-user btn-block letrasboton" onclick="nextStep(1)">Siguiente</button>
                        </div>
                        
                        <div class="step-content" id="content2">
                            <div class="form-group input-group-new">
                                <label for="login">Usuario</label>
                                <input onchange="validarlogin()" type="text" class="form-control inpt" name="login" id="login" maxlength="10" placeholder="Login">
                                <div id="login-repetido" class="error-message">Login ya está registrado</div>
                                <div id="usuario-error" class="error-message">Usuario requerido</div>
                            </div>
                            <div class="form-group input-group-new">
                                <label for="clave">Contraseña</label>
                                <input onchange="validarLongitud(this)" type="password" class="form-control inpt" name="clave" id="clave" maxlength="64" placeholder="Clave">
                                <div id="contrasena-error" class="error-message">Contraseña requerida</div>
                                <div id="clave-error" class="error-message">Clave debe tener mín 8 caracteres</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-user botonR" onclick="prevStep(2)">Regresar</button>
                                <button type="button" id="siguiente2-btn" class="btn btn-primary btn-user botonS" onclick="nextStep(2)">Siguiente</button>
                            </div>
                        </div>

                        <div class="step-content text-center" id="content3">
                            <h4 class="h4 text-gray-900 mb-4 letras3">¡Ya casi te registras!</h4>
                            <p class="description-text">Presiona el botón para crear tu usuario.</p>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-user botonR" onclick="prevStep(3)">Regresar</button>
                                <button type="submit" id="btnGuardar" class="btn btn-primary btn-user">Registrarme</button>
                            </div>
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
    <script src="scripts/registro.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function nextStep(currentStep) {
            document.getElementById('step' + currentStep).classList.remove('active');
            document.getElementById('step' + (currentStep + 1)).classList.add('active');
            document.getElementById('content' + currentStep).classList.remove('active');
            document.getElementById('content' + (currentStep + 1)).classList.add('active');
        }
        
        function prevStep(currentStep) {
            document.getElementById('step' + currentStep).classList.remove('active');
            document.getElementById('step' + (currentStep - 1)).classList.add('active');
            document.getElementById('content' + currentStep).classList.remove('active');
            document.getElementById('content' + (currentStep - 1)).classList.add('active');
        }
    </script>
</body>
</html>