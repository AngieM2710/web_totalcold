<?php
ob_start();
if (strlen(session_id()) < 1) {
    session_start();
}

require_once "../modelos/Equipos.php";
$equipos = new Equipos();

// Limpieza de entradas
$id_equipo = isset($_POST["id_equipo"]) ? limpiarCadena($_POST["id_equipo"]) : "";
$codigo_interno = isset($_POST["codigo_interno"]) ? limpiarCadena($_POST["codigo_interno"]) : "";
$marca = isset($_POST["marca"]) ? limpiarCadena($_POST["marca"]) : "";
$modelo = isset($_POST["modelo"]) ? limpiarCadena($_POST["modelo"]) : "";
$capacidad = isset($_POST["capacidad"]) ? limpiarCadena($_POST["capacidad"]) : "";

switch ($_GET["op"]) {

    case 'guardaryeditar':

        if (empty($id_equipo)) {
            // Registro de equipo
            $rspta = $equipos->insertar($codigo_interno, $marca, $modelo, $capacidad);
            echo $rspta ? "Equipo registrado" : "No se pudo registrar el equipo";
        } else {
            // Actualización
            $rspta = $equipos->editar(
                $id_equipo,
                $codigo_interno,
                $marca,
                $modelo,
                $capacidad
            );
            echo $rspta ? "Equipo actualizado" : "No se pudo actualizar el equipo";
        }
        break;

    case 'mostrar':
        $rspta = $equipos->mostrar($id_equipo);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $equipos->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $data[] = [
                "0" => ($reg->estado_equipo) ?
                    '<a href="#" onclick="mostrar(' . $reg->id_equipo . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="desactivar(' . $reg->id_equipo . ')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>' :
                    '<a href="#" onclick="mostrar(' . $reg->id_equipo . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="activar(' . $reg->id_equipo . ')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>',
                "1" => $reg->codigo_interno,
                "2" => $reg->marca,
                "3" => $reg->modelo,
                "4" => $reg->capacidad,
                "5" => ($reg->estado_equipo) ? '<span class="label bg-green">Activo</span>' : '<span class="label bg-red">Inactivo</span>'
            ];
        }

        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($results);
        break;

    case 'desactivar':
        $rspta = $equipos->desactivar($id_equipo);
        echo $rspta ? "Equipo desactivado" : "No se pudo desactivar";
        break;

    case 'activar':
        $rspta = $equipos->activar($id_equipo);
        echo $rspta ? "Equipo activado" : "No se pudo activar";
        break;
}

ob_end_flush();
?>
