<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="../public/img/imagenes/logo.png?v=<?php echo time(); ?>">

  <title>TOTAL COLD</title>

  <link href="../public/vendor/fontawesome-free/css/all.min.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../public/css/sb-admin-2.min.css?v=<?php echo time(); ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link href="../public/css/login.css?v=<?php echo time(); ?>" rel="stylesheet">
</head> 

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="row">
                <div class="col-lg-6 d-none d-lg-block left-panel">
                    <div class="image-overlay">
                        <img src="../public/img/imagenes/logo.png?v=<?php echo time(); ?>" class="logo-image" alt="Logo de TOTAL COLD">
                        <h2 class="letras-slogan">Gestión Técnica Inteligente</h2>
                    </div>
                </div>
                
                <div class="col-lg-6 p-5 right-panel">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4 letras3">Bienvenido</h1>
                        <p class="description-text">Accede a tus tareas y servicios del día.</p>
                    </div>

                    <form method="post" id="frmAcceso" class="user login-form-new">
                        <div class="form-group input-group-new">
                            <label for="emailusuario">Usuario o Email</label>
                            <input type="text" id="emailusuario" name="email" class="form-control form-control-user letras4" placeholder="Ingrese su Email">
                            <div id="username-error" class="error-message">Usuario requerido</div>
                        </div>
                        <div class="form-group input-group-new" style="position: relative;">
                            <label for="clavea">Contraseña</label>
                            <input type="password" id="clavea" name="clavea" class="form-control form-control-user letras4" placeholder="Ingrese su contraseña">
                                <i onclick="togglePassword('clavea')" class="fas fa-eye" 
                                style="position: absolute; right: 15px; top: 72%; transform: translateY(-50%); cursor: pointer; color: #999999a0;"></i>

                            <div id="password-error" class="error-message">Contraseña requerida</div>
                        </div>

                        <button type="submit" id="submit-btn" class="btn btn-primary btn-user btn-block letrasboton botonS" disabled>
                            Ingresar
                        </button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <a class="small letrasA" href="registro.php">Crea tu cuenta</a>
                    </div>
                    <div class="text-center">
                        <a class="small letrasA" href="contraseña.php">¿Olvidaste la contraseña?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loading-spinner" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; flex-direction:column; align-items:center; justify-content:center;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
        <p class="text-white mt-2">Cargando...</p>
    </div>

    <script src="../public/vendor/jquery/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js?v=<?php echo time(); ?>"></script>
    <script src="../public/vendor/jquery-easing/jquery.easing.min.js?v=<?php echo time(); ?>"></script>
    <script src="scripts/login.js?v=<?php echo time(); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function togglePassword(idInput) {
            const input = document.getElementById(idInput);
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>