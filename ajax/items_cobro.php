<?php
ob_start();
if (strlen(session_id()) < 1){
    session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Items_cobro.php";
$items= new Items();
$id_item_cobro  = isset($_POST["id_item_cobro"])? limpiarCadena($_POST["id_item_cobro"]):"";
$id_servicios  = isset($_POST["id_servicios"])? limpiarCadena($_POST["id_servicios"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$estado = isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";


switch ($_GET["op"]){
    
    case 'listar':
        $rspta=$items->listar();
        $data= Array(); 
        while($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "1"=>$reg->nombre,  
                "0"=>$reg->id_servicios,               
                "2"=>($reg->estado)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>',
                "3"=>($reg->estado)?
                    ' <a href="#" onclick="mostrar('.$reg->id_item_cobro.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>'.
                    ' <a href="#" onclick="desactivar('.$reg->id_item_cobro.')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>':
                    ' <a href="#" onclick="mostrar('.$reg->id_item_cobro.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>'.
                    ' <a href="#" onclick="activar('.$reg->id_item_cobro.')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>',
            );
        }
          $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), //enviar total de registros al datatable
            "iTotalDisplayRecords"=>count($data), //envio total de registros a visualizar
            "aaData"=>$data
        );
        echo json_encode($results);
    break;

    case 'guardaryeditar':
        if(empty($id_item_cobro)){
            $rspta= $items->insertar($id_servicios,$nombre);
            echo $rspta ? "Ítem de cobro registrado" : "No se pudo registrar el ítem de cobro";
        }
        else{
            $rspta= $items->editar($id_item_cobro,$id_servicios,$nombre);
            echo $rspta ? "Ítem de cobro actualizado" : "No se pudo actualizar el ítem de cobro";
        }   
    break;

    case 'mostrar':
		$rspta=$items->mostrar($id_item_cobro);
		echo json_encode($rspta);
    break;

    case 'desactivar':
            $rspta=$items->desactivar($id_item_cobro);
            echo $rspta ? "Ítem de cobro desactivado" : "Ítem de cobro no se puede desactivar";
    break;

    case 'activar':
            $rspta=$items->activar($id_item_cobro);
            echo $rspta ? "Ítem de cobro activado" : "Ítem de cobro no se puede activar";
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