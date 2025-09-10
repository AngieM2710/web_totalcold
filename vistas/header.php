<?php
if(strlen(session_id()) < 1)
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../files/logo/slogan1.png">

    <title>TOTAL COLD</title>

    <!-- Custom fonts for this template -->
    <link href="../public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../public/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../public/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../public/css/estilos.css" rel="stylesheet">
    <link href="../public/css/tabla.css" rel="stylesheet">
    <!-- Para la última versión de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="../public/css/cardstyle.css" rel="stylesheet">
    <link href="../public/css/modal.css" rel="stylesheet">
</head>



<!-- <div id="bga">
    <canvas id="bubbleCanvas"></canvas>
</div> -->
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion contencolormenu" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="escritorio.php">
                <div class="sidebar-brand-icon ">
                <img src="../public/img/imagenes/logo.png"   class="tamimg" alt="">

                </div>
                <div class="sidebar-brand-text mx-3 letrasmenu"> TOTAL COLD <sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <?php 
           if (!empty($_SESSION['administrador']) && $_SESSION['administrador'] == 1)
            {
            ?>
            <?php
            $principal =['escritorio.php'];
            $pagesUsuarios = ['usuario.php', 'personal.php', 'clientes.php'];
            $pagesInformacion = ['categorias.php', 'productos.php', 'servicios.php'];
            $pagesHorarios = ['dias.php', 'horas.php', 'horarios.php'];
            $pagesDashboard = ['dashboard.php'];
            ?>

            <li class="nav-item menu <?= ($currentPage == $principal) ? 'active' : '' ?> ">
            <div class="bg-white py-2 collapse-inner rounded  princ">
                <a class="nav-link collapse-item " href="escritorio.php">
                    <i class="fas fa-fw fa-desktop "></i>
                    <span >Escritorio</span></a>
                    </div>
            </li>

<!--                <li class="nav-item menu <?= in_array($currentPage, $principal) ? 'active' : '' ?> ">
                 <div class="bg-white py-2 collapse-inner rounded divtransparente">
                <a class="nav-link  collapsed " href="escritorio.php" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-desktop"></i>
                    <span>Escritorio</span></a>
                </div>
            </li> -->
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item menu <?= in_array($currentPage, $pagesUsuarios) ? 'active' : '' ?> ">
                <a class="nav-link  collapsed" href="#"  data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="false" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Personas</span></a>

                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded divtransparente">
                      <!--   <h6 class="collapse-header">Opciones:</h6> -->
                        <a class="collapse-item primera" href="usuario.php"><i class="fa fa-user"></i> Usuarios</a>
                        <a class="collapse-item" href="personal.php"><i class="fa fa-person"></i> Personal</a>
                        <a class="collapse-item" href="clientes.php"><i class="fa fa-person-circle-plus"></i> Clientes</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item menu <?= in_array($currentPage, $pagesInformacion) ? 'active' : '' ?> ">
                <a class="nav-link collapsed" href="#"  data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-desktop"></i>
                    <span>Información</span></a>

                    <div id="collapseUtilities" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="py-2 collapse-inner rounded divtransparente">
                        <!-- <h6 class="collapse-header">Opciones:</h6> -->
                        <a class="collapse-item" href="categorias.php"><i class="fas fa-fw fa-desktop"></i>Categorías</a>
                        <a class="collapse-item" href="productos.php"><i class="fas fa-fw fa-tags"></i> Productos</a>
                        <a class="collapse-item" href="servicios.php"><i class="fas fa-fw fa-cut"></i> Servicios</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item menu  <?= in_array($currentPage, $pagesHorarios) ? 'active' : '' ?> ">
                <a class="nav-link  collapsed" href="#"  data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Horarios</span></a>

                    <div id="collapsePages" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded divtransparente">
                        <!-- <h6 class="collapse-header">Opciones:</h6> -->
                        <a class="collapse-item" href="dias.php"><i class="fas fa-fw fa-calendar"></i>
                        Días</a>
                        <a class="collapse-item" href="horas.php"><i class="fas fa-fw fa-clock"></i> Horas</a>
                        <a class="collapse-item" href="horarios.php"><i class="fas fa-fw fa-calendar"></i>
                        Asignaciones</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item menu  <?= in_array($currentPage, $pagesHorarios) ? 'active' : '' ?> ">
                <a class="nav-link  collapsed" href="#"  data-toggle="collapse" data-target="#collapseReservas"
                aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Reservas</span></a>

                    <div id="collapseReservas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded divtransparente">
                    <a class="collapse-item" href="tarifas.php"><i class="fas fa-fw fa-dollar"></i> Tarifas</a>
                        <a class="collapse-item" href="reservas.php"><i class="fas fa-fw fa-calendar"></i>
                        Nueva reserva</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item menu  <?= in_array($currentPage, $pagesHorarios) ? 'active' : '' ?> ">
                <a class="nav-link  collapsed" href="#"  data-toggle="collapse" data-target="#collapseVentas"
                aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Ventas</span></a>

                    <div id="collapseVentas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded divtransparente">
                        <a class="collapse-item" href="ventas.php"><i class="fas fa-fw fa-shopping-cart"></i>
                        Nueva venta</a>
                        <a class="collapse-item" href="cotizaciones.php"><i class="fas fa-fw fa-credit-card"></i>
                        Nueva cotización</a>
                    </div>
                </div>
            </li>


            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item menu  <?= in_array($currentPage, $pagesDashboard) ? 'active' : '' ?> ">
                <a class="nav-link  collapsed" href="#"  data-toggle="collapse" data-target="#collapseDashboard"
                aria-expanded="true" aria-controls="collapseDashboard">
                    <i class="fas fa-fw fa-pie-chart"></i>
                    <span>Dashboard</span></a>

                    <div id="collapseDashboard" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded divtransparente">
                        <!-- <h6 class="collapse-header">Opciones:</h6> -->
                        <a class="collapse-item" href="dashboard.php"><i class="fas fa-fw fa-line-chart"></i>
                        Ver Panel</a>
                    </div>
                </div>
            </li>

            <?php
            }else if($_SESSION['cliente'] == 1){
            ?>
            
            <li class="nav-item menu <?= ($currentPage == 'escritorio.php') ? 'active' : '' ?> ">
            <div class="bg-white py-2 collapse-inner rounded princ">
                <a class="nav-link collapse-item" href="escritorio.php">
                    <i class="fas fa-fw fa-desktop "></i>
                    <span >Escritorio</span></a>
                    </div>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            
            <li class="nav-item menu  <?= in_array($currentPage, $pagesHorarios) ? 'active' : '' ?> ">
                <a class="nav-link  collapsed" href="#"  data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Reservas</span></a>

                    <div id="collapsePages" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded divtransparente">
                        <a class="collapse-item" href="reservacliente.php"><i class="fas fa-fw fa-calendar"></i>
                        Nueva reserva</a>
                    </div>
                </div>
            </li>

             <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item menu  <?= in_array($currentPage, $pagesHorarios) ? 'active' : '' ?> ">
                <a class="nav-link  collapsed" href="#"  data-toggle="collapse" data-target="#collapseVentas"
                aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Cotizaciones</span></a>

                    <div id="collapseVentas" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded divtransparente">
                        
                        <a class="collapse-item" href="cotizaciones_cli.php"><i class="fas fa-fw fa-credit-card"></i>
                        Nueva cotización</a>
                    </div>
                </div>
            </li>   
            <?php
            }
            ?>

         
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        
                    </form>

                    <ul class="navbar-nav ml-auto">

                        
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow menu">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nombres'] . " " . $_SESSION['apellidos'];?></span>
                                <img class="img-profile rounded-circle"
                                src="../files/usuarios/<?php echo $_SESSION['imagen_us']; ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in salir"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="editarperfil.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                               
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../ajax/usuario.php?op=salir"  data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
         
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content stilemodal">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Listo para irte?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body modal-body2">Confirma para cerrar la sesión</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary botonR" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary botonS" href="login.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

<!-- jQuery -->
<script src="../public/vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap JS -->
<script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

<!-- Bootstrap Select JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<!-- Otros scripts -->
<script src="../public/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../public/js/sb-admin-2.min.js"></script>
<script src="../public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../public/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../public/js/demo/datatables-demo.js"></script>

</body>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Recupera el menú activo desde localStorage
    let activeMenu = localStorage.getItem("activeMenu");

    if (activeMenu) {
        // Expande el menú activo
        let element = document.getElementById(activeMenu);
        if (element) {
            element.classList.add("show");
        }
    }

    // Agrega un evento para detectar cuándo se expande un menú
    let collapsibles = document.querySelectorAll("[data-toggle='collapse']");
    collapsibles.forEach(item => {
        item.addEventListener("click", function () {
            let targetId = this.getAttribute("data-target").substring(1); // Remueve el "#" del ID
            localStorage.setItem("activeMenu", targetId);
        });
    });

    // Marcar el enlace activo basado en la URL actual
    let currentUrl = window.location.pathname.split("/").pop(); // Obtener el nombre del archivo actual
    let menuItems = document.querySelectorAll(".collapse-item");

    menuItems.forEach(item => {
        if (item.getAttribute("href") === currentUrl) {
            item.classList.add("active"); // Agrega la clase active al enlace actual
        }
    });

    // Solución para "Escritorio": Al hacer clic, limpiar localStorage y evitar activar el último menú
    let escritorioLink = document.querySelector(".nav-link[href='escritorio.php']");
    if (escritorioLink) {
        escritorioLink.addEventListener("click", function () {
            localStorage.removeItem("activeMenu"); // Limpiar el menú guardado
        });
    }
});
</script>
<!-- <script src="../public/js/demo/canvas-smoke.js"></script> -->

</html>