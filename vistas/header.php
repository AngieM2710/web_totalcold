<?php
if(strlen(session_id()) < 1)
session_start();
// Se incluye el archivo para manejar las clases activas
$currentPage = basename($_SERVER['PHP_SELF']); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../public/img/imagenes/logototalcold.png">

    <title>TOTAL COLD</title>

    <link href="../public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="../public/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../public/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="../public/css/menu.css?v=<?php echo time(); ?>" rel="stylesheet">

</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="escritorio.php">
                <div class="sidebar-brand-icon">
                    <!-- <img src="../public/img/imagenes/logototalcold.png" class="tamimg" alt=""> -->
                </div>
                <div class="sidebar-brand-text mx-3"> TOTAL COLD<sup></sup></div>
            </a>

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
            } else if ($_SESSION['tecnico'] == 1) {
                // Lógica del menú para clientes
                ?>
                <li class="nav-item <?= ($currentPage == 'escritorio.php') ? 'active' : '' ?>">
                    <a class="nav-link" href="escritorio.php">
                        <i class="fas fa-fw fa-desktop"></i>
                        <span>Escritorio</span>
                    </a>
                </li>
                <hr class="sidebar-divider d-none d-md-block">

                <li class="nav-item <?= ($currentPage == 'reservacliente.php') ? 'active' : '' ?>">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                        <i class="fas fa-fw fa-calendar"></i>
                        <span>Reservas</span>
                    </a>
                    <div id="collapsePages" class="collapse <?= ($currentPage == 'reservacliente.php') ? 'show' : '' ?>" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item <?= ($currentPage == 'reservacliente.php') ? 'active' : '' ?>" href="reservacliente.php"><i class="fas fa-fw fa-calendar"></i> Nueva reserva</a>
                        </div>
                    </div>
                </li>

                <hr class="sidebar-divider d-none d-md-block">
                
                <li class="nav-item <?= ($currentPage == 'cotizaciones_cli.php') ? 'active' : '' ?>">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVentas" aria-expanded="true" aria-controls="collapsePages">
                        <i class="fas fa-fw fa-shopping-cart"></i>
                        <span>Cotizaciones</span>
                    </a>
                    <div id="collapseVentas" class="collapse <?= ($currentPage == 'cotizaciones_cli.php') ? 'show' : '' ?>" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item <?= ($currentPage == 'cotizaciones_cli.php') ? 'active' : '' ?>" href="cotizaciones_cli.php"><i class="fas fa-fw fa-credit-card"></i> Nueva cotización</a>
                        </div>
                    </div>
                </li>

            <?php
            }
            ?>
            
            <hr class="sidebar-divider d-none d-md-block">
            
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow menu">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido'];?></span>
                                <img class="img-profile rounded-circle" src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="editarperfil.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Perfil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../ajax/usuario.php?op=salir" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Cerrar sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>

                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                <script src="../public/vendor/jquery/jquery.min.js"></script>

                <script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

                <script src="../public/vendor/jquery-easing/jquery.easing.min.js"></script>
                <script src="../public/js/sb-admin-2.min.js"></script>
                <script src="../public/vendor/datatables/jquery.dataTables.min.js"></script>
                <script src="../public/vendor/datatables/dataTables.bootstrap4.min.js"></script>
                <script src="../public/js/demo/datatables-demo.js"></script>

                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let currentUrl = window.location.pathname.split("/").pop();
                    
                    // Marcar el enlace activo
                    let menuItems = document.querySelectorAll(".nav-link, .collapse-item");
                    menuItems.forEach(item => {
                        if (item.getAttribute("href") === currentUrl) {
                            item.classList.add("active");
                            // Si es un submenú, expandir el menú padre
                            let parentCollapse = item.closest(".collapse");
                            if (parentCollapse) {
                                parentCollapse.classList.add("show");
                                // Marcar el padre con la clase active para el estilo del enlace
                                let parentLink = document.querySelector(`[data-target="#${parentCollapse.id}"]`);
                                if (parentLink) {
                                    parentLink.closest('.nav-item').classList.add("active");
                                }
                            }
                        }
                    });

                    // Si estás en 'escritorio.php', asegúrate de que solo ese enlace esté activo
                    if (currentUrl === 'escritorio.php') {
                         document.querySelectorAll(".nav-item").forEach(item => item.classList.remove("active"));
                         document.querySelector("a[href='escritorio.php']").closest('.nav-item').classList.add('active');
                    }
                });
                </script>

            </body>
            </html>