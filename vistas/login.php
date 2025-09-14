<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="../files/usuarios/1757525262.jpg?v=<?php echo time(); ?>">

  <title>TOTAL COLD</title>

  <link href="../public/vendor/fontawesome-free/css/all.min.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../public/css/sb-admin-2.min.css?v=<?php echo time(); ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link href="../public/css/login.css?v=<?php echo time(); ?>" rel="stylesheet">
</head> 

<body class="bg-gradient-primary contencolor">

  <div class="container centrar">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5 stilocard shadowlg">
          <div class="card-body p-0 stilocard">
            <div class="row">
              <div class="col-lg-12 d-none d-lg-block bg-login-image">
                <div class="centrar2">
                  <img src="../public/img/imagenes/logo.png?v=<?php echo time(); ?>" class="img" alt="">
                </div>
              </div>
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4 letras3">Bienvenido</h1>
                  </div>

                  <form method="post" id="frmAcceso" class="user">
                    <div class="form-group">
                      
                        <input type="text" id="emailusuario" name="email" class="form-control form-control-user letras4" placeholder="Ingrese su Email">
                      
                        <div id="username-error" style="color: red; display: none; font-size: 0.8em; margin-left: 10px;">Usuario requerido</div>
                    </div>
                    <div class="form-group" style="position: relative;">
                      
                        <input type="password" id="clavea" name="clavea" class="form-control form-control-user letras4" placeholder="Ingrese su contraseña">
                      
                        <span class="toggle-password" onclick="togglePassword('clavea')" style="position:absolute; top:10px; right:15px; cursor:pointer; color: #555;">👁️</span>
                      <div id="password-error" style="color: red; display: none; font-size: 0.8em; margin-left: 10px;">Contraseña requerida</div>
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
                    <a class="small letrasA" href="recuperarcontraseña.php">¿Olvidaste la contraseña?</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>

  <!-- Spinner oculto al inicio -->
  <div id="loading-spinner" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; flex-direction:column; align-items:center; justify-content:center;">
    <div class="spinner-border text-light" role="status">
      <span class="visually-hidden"></span>
    </div>
    <p class="text-white mt-2">Cargando...</p>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../public/vendor/jquery/jquery.min.js?v=<?php echo time(); ?>"></script>
  <script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js?v=<?php echo time(); ?>"></script>
  <script src="../public/vendor/jquery-easing/jquery.easing.min.js?v=<?php echo time(); ?>"></script>
  <!-- <script src="../public/js/sb-admin-2.min.js?v=<?php echo time(); ?>"></script> -->
  <script src="scripts/login.js?v=<?php echo time(); ?>"></script>
  <script>
    function togglePassword(idInput) {
      const input = document.getElementById(idInput);
      input.type = input.type === "password" ? "text" : "password";
    }
  </script>

</body>
</html>