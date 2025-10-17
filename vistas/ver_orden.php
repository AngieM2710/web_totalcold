<?php
ob_start();
session_start();
date_default_timezone_set('America/Guayaquil');

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
} else {
    require 'header.php';
    if ($_SESSION['administrador'] == 1 || $_SESSION['tecnico'] == 1) {

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo '<div class="container mt-4"><div class="alert alert-danger" role="alert">ID de Orden no válido.</div></div>';
            exit();
        }

        $id_orden = $_GET['id'];
?>

        <!-- ======================= -->
        <!-- CONTENIDO PRINCIPAL -->
        <!-- ======================= -->
<div class="container-fluid">
    <h3 class="mb-4 text-gray-800 letrastitulo">Órden #<?php echo htmlspecialchars($id_orden); ?></h3>

    <div class="row">

        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div id="infoClienteContainer" class="card-body"> 
                    <h5 class="text-info mb-3"><i class="fas fa-user"></i> Información del Cliente</h5>
                    <p class="text-center text-muted">Cargando datos del cliente...</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow-sm border-0 mb-3">
                <div id="serviciosContainer" class="card-body"> 
                    <h5 class="text-info mb-3"><i class="fas fa-tools"></i> Servicios Asignados</h5>
                    <p class="text-center text-muted">Cargando equipos y servicios...</p>
                </div>
            </div>
        </div>

    </div></div><!-- fin container-fluid -->


        
        <!-- <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <h3 class="mb-4 text-gray-800 letrastitulo">Órden #<?php echo htmlspecialchars($id_orden); ?></h3>
            </div>
            <div class="orden-container">


            </div>
        </div> -->



        <!-- ======================= -->
        <!-- SCRIPT -->
        <!-- ======================= -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function abrirDetalleServicio(nombre) {
                document.getElementById('tituloServicioModal').textContent = nombre;
                $('#modalDetalleServicio').modal('show');
            }
        </script>

        <script>
          const ID_ORDEN = <?php echo json_encode($id_orden); ?>;
        </script>
        <script type="text/javascript" src="scripts/ver_orden.js"></script>

<?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
}
ob_end_flush();
?>