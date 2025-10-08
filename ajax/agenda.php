<?php
ob_start();
if (strlen(session_id()) < 1){
    session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Agenda.php";
$agenda= new Agenda();

/* $id_orden = isset($_POST["od_orden"])? limpiarCadena($_POST["id_ps"]):"";
$id_cat = isset($_POST["id_cat"])? limpiarCadena($_POST["id_cat"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$precio_venta = isset($_POST["precio_venta"])? limpiarCadena($_POST["precio_venta"]):"";
$imagenprod = isset($_POST["imagenprod"])? limpiarCadena($_POST["imagenprod"]):"";
 */
switch ($_GET["op"]){

        case 'listarcard':
            $rspta = $agenda->listar(); // usa tu función listar() del modelo
            $data = array();

            while ($reg = $rspta->fetch_object()) {
                // Determinar la etiqueta de estado
                $estado_label = '';
                switch (strtolower($reg->estado_orden)) {
                    case 'pendiente':
                        $estado_label = '<span class="card-status pendiente">Pendiente</span>';
                        break;
                    case 'en_curso':
                        $estado_label = '<span class="card-status encurso">En Curso</span>';
                        break;
                    case 'terminado':
                        $estado_label = '<span class="card-status terminado">Terminado</span>';
                        break;
                    default:
                        $estado_label = '<span class="card-status desconocido">Desconocido</span>';
                }
                $data[] = array(
                    "hora" => date("h:i A", strtotime($reg->fecha)), // asegúrate que tu campo de hora exista
                    "cliente" => $reg->nombre_cliente,
                    "servicio" => $reg->servicio,
                    "estado" => $estado_label
                );
            }
            echo json_encode(["aaData" => $data]);
        break;
/*         case 'listarcard':
            $rspta = $agenda->lista();
            $data = array(); 
        
            while ($reg = $rspta->fetch_object()) { 
                $data[] = array(
                    "0" => ($reg->estado_ps) ?
                        '<a href="#" onclick="mostrar('.$reg->id_ps.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                        ' <a href="#" onclick="desactivar('.$reg->id_ps.')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>' :
                        '<a href="#" onclick="mostrar('.$reg->id_ps.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                        ' <a href="#" onclick="activar('.$reg->id_ps.')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>',
                    "1" => $reg->categoria,
                    "2" => $reg->descripcion,  
                    "3" => number_format($reg->precio_venta, 2), // Asegurar formato de precio      
                    "4" => $reg->imagenprod ? "../files/agenda/" . $reg->imagenprod : "../public/img/imagenes/default.png", // Manejo de imagen nula
                    "5" => ($reg->estado_ps) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>'
                );
            }
        
            $results = array(
                "sEcho" => 1, 
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
        
            echo json_encode($results);
            break; */

}
ob_end_flush();
?>