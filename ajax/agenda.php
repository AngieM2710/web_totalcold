<?php
ob_start();
if (strlen(session_id()) < 1) {
    session_start(); //Validamos si existe o no la sesión
}
require_once "../modelos/Agenda.php";
$agenda = new Agenda();

/* $id_orden = isset($_POST["od_orden"])? limpiarCadena($_POST["id_ps"]):"";
$id_cat = isset($_POST["id_cat"])? limpiarCadena($_POST["id_cat"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$precio_venta = isset($_POST["precio_venta"])? limpiarCadena($_POST["precio_venta"]):"";
$imagenprod = isset($_POST["imagenprod"])? limpiarCadena($_POST["imagenprod"]):"";
 */
switch ($_GET["op"]) {

case 'listarcard':
    $rspta = $agenda->listar(); 
    $data = array();

    while ($reg = $rspta->fetch_object()) {
        // Determinar la etiqueta de estado según valor 0 o 1
        $estado_label = '';
        if ($reg->estado_orden == 0) {
            $estado_label = '<span class="card-status pendiente">Pendiente</span>';
        } elseif ($reg->estado_orden == 1) {
            $estado_label = '<span class="card-status terminado">Terminado</span>';
        } else {
            $estado_label = '<span class="card-status desconocido">Desconocido</span>';
        }

        // Si tienes un campo fecha/hora separado, ajusta aquí
        $hora = isset($reg->fecha) ? date("h:i A", strtotime($reg->fecha)) : '';
        $fecha = isset($reg->fecha) ? date("d/m/Y", strtotime($reg->fecha)) : '';
        
        $data[] = array(
            "fecha" => $fecha,
            "hora" => $hora,
            "cliente" => $reg->nombre_cliente,
            "servicio" => $reg->servicios,
            "estado" => $estado_label
        );
    }

    echo json_encode(["aaData" => $data]);

    break;
        case 'listartab':
            $rspta = $agenda->listar();
            $data = array(); 
        
            while ($reg = $rspta->fetch_object()) { 
                $data[] = array(
                    "5" => ($reg->estado_orden) ?
                        '<a href="#" onclick="mostrar('.$reg->id_orden.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                        ' <a href="#" onclick="desactivar('.$reg->id_orden.')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>' :
                        '<a href="#" onclick="mostrar('.$reg->id_orden.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                        ' <a href="#" onclick="activar('.$reg->id_orden.')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>',
                    "0" => $reg->fecha . ' ' . $reg->hora,
                    "1" => $reg->nombre_cliente,  
                    "2" => $reg->nombre_tecnico,
                    "3" => $reg->servicios,// Asegurar formato de precio      
                    "4" => ($reg->estado_orden) ? '<span class="label bg-green">Terminado</span>' : '<span class="label bg-red">Pendiente</span>'
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
            


}
ob_end_flush();
