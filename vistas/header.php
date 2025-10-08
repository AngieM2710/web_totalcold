<?php
require_once "../modelos/Usuarios.php";
if(strlen(session_id()) < 1)session_start();

$currentPage = basename($_SERVER['PHP_SELF']); 
include 'menu_items.php';
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

    <link href="../public/css/menu2.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../public/css/contenido.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../public/css/tablas.css?v=<?php echo time(); ?>" rel="stylesheet">

</head>

    <body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="usuarios.php">
                <div class="sidebar-brand-icon">
                <img src="../public/img/imagenes/lg.png" class="tamimg" alt="">
                </div>
                <div class="sidebar-brand-text mx-3"> TOTAL COLD<sup></sup></div>
            </a>

            
            <?php if (!empty($menuItems)): ?>
                <?php foreach ($menuItems as $index => $menu): ?>
                    <li class="nav-item menu <?= in_array($currentPage, $menu['pages']) ? 'active' : '' ?>">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" 
                        data-target="#collapse<?= $index ?>" aria-expanded="true">
                            <i class="fas fa-fw <?= $menu['icon'] ?>"></i>
                            <span><?= $menu['title'] ?></span>
                        </a>

                        <div id="collapse<?= $index ?>" class="collapse" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded divtransparente">
                                <?php foreach ($menu['submenu'] as $item): ?>
                                    <a class="collapse-item <?= ($currentPage == $item['href']) ? 'active' : '' ?>" href="<?= $item['href'] ?>">
                                        <i class="fas fa-fw <?= $item['icon'] ?>"></i> <?= $item['text'] ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </li>
                    <hr class="sidebar-divider d-none d-md-block">
                <?php endforeach; ?>
            <?php endif; ?>

            
           <!--  <hr class="sidebar-divider d-none d-md-block"> -->
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
                                <img class="img-profile rounded-circle" src="<?php echo $ruta. $_SESSION['imagen']; ?>">
                                
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="editarperfil.php">
                                    <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-400"></i> Perfil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../ajax/usuario.php?op=salir" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Cerrar sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Resto del código permanece igual -->
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
                                <a class="btn btn-primary botonS" href="../ajax/usuarios.php?op=salir">Cerrar sesión</a>
                            </div>
                        </div>
                    </div>
                </div>


                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


                <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
                <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.0/i18n/datepicker-es.min.js"></script>


                <script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

                <script src="../public/vendor/jquery-easing/jquery.easing.min.js"></script>
                <script src="../public/js/sb-admin-2.min.js"></script>

                <script src="../public/vendor/datatables/jquery.dataTables.min.js"></script>
                <script src="../public/vendor/datatables/dataTables.bootstrap4.min.js"></script>
                <script src="../public/js/demo/datatables-demo.js"></script>

<!--                 <script src="../public/vendor/jquery/jquery.min.js"></script>
                <script src="../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
                <script src="../public/vendor/jquery-easing/jquery.easing.min.js"></script>
                <script src="../public/js/sb-admin-2.min.js"></script>
                <script src="../public/vendor/datatables/jquery.dataTables.min.js"></script>
                <script src="../public/vendor/datatables/dataTables.bootstrap4.min.js"></script>
                <script src="../public/js/demo/datatables-demo.js"></script>
 -->
<!--                 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
                <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.0/i18n/datepicker-es.min.js"></script> -->

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                    let currentUrl = window.location.pathname.split("/").pop();

                    let menuItems = document.querySelectorAll(".nav-link, .collapse-item");
                    menuItems.forEach(item => {
                    if (item.getAttribute("href") === currentUrl) {
                    item.classList.add("active");
                    let parentCollapse = item.closest(".collapse");
                    if (parentCollapse) {
                    parentCollapse.classList.add("show");
                    let parentLink = document.querySelector(`[data-target="#${parentCollapse.id}"]`);
                    if (parentLink) {
                    parentLink.closest('.nav-item').classList.add("active");
                    }
                    }
                    }
                    });

                    if (currentUrl === 'escritorio.php') {
                    document.querySelectorAll(".nav-item").forEach(item => item.classList.remove("active"));
                    }
                    });
                </script>

    </body>
</html>