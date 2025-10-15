<?php
ob_start();
if (strlen(session_id()) < 1){
    session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Items_cobro.php";
require_once "../modelos/Categorias.php";

$items= new Items();
$id_item_cobro  = isset($_POST["id_item_cobro"])? limpiarCadena($_POST["id_item_cobro"]):"";
$id_servicios  = isset($_POST["id_servicios"])? limpiarCadena($_POST["id_servicios"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$estado = isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";


switch ($_GET["op"]){
    
    case 'listar':
        $rspta = $items->listar();
        $data = Array(); 
        
        while ($reg = $rspta->fetch_object()) { 
            
            // --- Lógica simplificada para asignar el estilo ---
            $id_servicio_actual = (int) $reg->id_servicios;
            
            // Creamos la cadena de clase dinámica (ej: "service_1", "service_2", etc.)
            $clase_dinamica = "service_" . $id_servicio_actual;
            
            // Generamos el contenido de la columna 0, usando la clase dinámica y un estilo base
            $contenido_columna_servicio = '<span class="label ' . $clase_dinamica . '">'.$id_servicio_actual.'</span>';
            
            // Si el ID de servicio no tiene una clase CSS definida (ej: service_7), 
            // simplemente se mostrará el número, a menos que definas una clase genérica.
            // ----------------------------------------------------
            
            $data[] = array(
                "0" => $contenido_columna_servicio,
                "1" => $reg->nombre,
                "2" => ($reg->estado) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>',
                "3" => ($reg->estado) ?
                    ' <a href="#" onclick="mostrar(' . $reg->id_item_cobro . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="desactivar(' . $reg->id_item_cobro . ')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>' :
                    ' <a href="#" onclick="mostrar(' . $reg->id_item_cobro . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="activar(' . $reg->id_item_cobro . ')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>',
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

    case 'guardaryeditar':
        if(empty($id_item_cobro)){
            $rspta= $items->insertar($id_servicios,$nombre);
            echo $rspta ? "items registrada" : "No se pudo registrar la items";
        }
        else{
            $rspta= $items->editar($id_item_cobro,$id_servicios,$nombre);
            echo $rspta ? "items actualizada" : "No se pudo actualizar la items";
        }   
    break;

    case 'mostrar':
		$rspta=$items->mostrar($id_item_cobro);
		echo json_encode($rspta);
    break;

    case 'desactivar':
            $rspta=$items->desactivar($id_item_cobro);
            echo $rspta ? "items desactivada" : "items no se puede desactivar";
    break;

    case 'activar':
            $rspta=$items->activar($id_item_cobro);
            echo $rspta ? "items activada" : "items no se puede activar";
    break;
    
    case 'selectServicios':
        $rspta = $items->listar();
        while ($reg = $rspta->fetch_object()){
            echo '<option value="'.$reg->id_servicios.'">'
            .$reg->nombre.'</option>';
        }
    break;

}
ob_end_flush();
?>