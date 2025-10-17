<?php
require_once "../modelos/Ordenes.php";
$orden = new Ordenes();

// Mapeo limpio y coherente
    $id_orden = isset($_POST["id_orden"]) ? limpiarCadena($_POST["id_orden"]) : "";
    $id_cliente = isset($_POST["id_cli"]) ? limpiarCadena($_POST["id_cli"]) : "";
    $id_usuarios = isset($_POST["id_tec"]) ? limpiarCadena($_POST["id_tec"]) : "";
    $fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
    $direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
    $tipo_pago = isset($_POST["tipo_pago"]) ? limpiarCadena($_POST["tipo_pago"]) : "";
    $observaciones = isset($_POST["observaciones"]) ? limpiarCadena($_POST["observaciones"]) : "";

// Llega como JSON el arreglo de equipos con sus servicios
    $equipos_json = isset($_POST["equipos"]) ? $_POST["equipos"] : "[]";
    $serviciosOrden = isset($_POST["serviciosOrden"]) ? $_POST["serviciosOrden"] : "[]";


switch ($_GET["op"]) {

    case 'guardaryeditar':
        if (empty($id_orden)) {
            // NUEVA ORDEN
            $rspta = $orden->insertar($id_cliente, $id_usuarios, $fecha, $direccion, $tipo_pago, $observaciones, $equipos_json, $serviciosOrden);
            echo $rspta ? "Orden registrada correctamente" : "No se pudo registrar la orden";
        } else {
            // EDITAR ORDEN
            $rspta = $orden->editar($id_orden, $id_cliente, $id_usuarios, $fecha, $direccion, $tipo_pago, $observaciones, $equipos_json, $serviciosOrden );
            echo $rspta ? "Orden actualizada correctamente" : "No se pudo actualizar la orden";
        }
        break;
/* 
    case 'mostrar':
        $rspta = $orden->mostrar($id_orden);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $orden->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->id_orden,
                "1" => $reg->cliente,
                "2" => $reg->tecnico,
                "3" => $reg->fecha,
                "4" => $reg->descripcion,
                "5" => '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->id_orden . ')"><i class="fa fa-pencil"></i></button>'
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
        break;

        case 'obtener_orden_completa':
        $id_orden = isset($_POST["id_orden"]) ? limpiarCadena($_POST["id_orden"]) : "";
        $data = $orden->obtenerOrdenCompleta($id_orden);
        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró la orden.']);
        }
        break;

        case 'actualizar_estado_servicio':
        $id_equipo_servicio = isset($_POST["id_equipo_servicio"]) ? limpiarCadena($_POST["id_equipo_servicio"]) : "";
        $estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";

        $rspta = $orden->actualizarEstadoServicio($id_equipo_servicio, $estado); 
        echo $rspta ? json_encode(['success' => true]) : json_encode(['success' => false, 'message' => 'Fallo la BD']);
        break;
}
?>
