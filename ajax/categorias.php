<?php
ob_start();
if (strlen(session_id()) < 1){
    session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Categorias.php";
$cat= new Categorias();

$id_servicios  = isset($_POST["id_servicios"])? limpiarCadena($_POST["id_servicios"]):"";
$id_categoria = isset($_POST["id_categoria"])? limpiarCadena($_POST["id_categoria"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$estado = isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";


switch ($_GET["op"]){
    
    case 'listar':
        $rspta=$cat->listar();
        $data= Array(); 
        while($reg=$rspta->fetch_object()){ 
            $data[]=array(
                "0"=>$reg->descripcion,               
                "1"=>($reg->estado)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>',
                "2"=>($reg->estado)?
                    ' <a href="#" onclick="mostrar('.$reg->id_servicios.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>'.
                    ' <a href="#" onclick="desactivar('.$reg->id_servicios.')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>':
                    ' <a href="#" onclick="mostrar('.$reg->id_servicios.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>'.
                    ' <a href="#" onclick="activar('.$reg->id_servicios.')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>',
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
        if(empty($id_servicios)){
            $rspta= $cat->insertar($descripcion);
            echo $rspta ? "Categoría registrada" : "No se pudo registrar la categoría";
        }
        else{
            $rspta= $cat->editar($id_servicios,$descripcion);
            echo $rspta ? "Categoría actualizada" : "No se pudo actualizar la categoría";
        }   
    break;

    case 'mostrar':
		$rspta=$cat->mostrar($id_servicios);
		echo json_encode($rspta);
    break;

    case 'desactivar':
            $rspta=$cat->desactivar($id_servicios);
            echo $rspta ? "Categoría desactivada" : "Categoría no se puede desactivar";
    break;

    case 'activar':
            $rspta=$cat->activar($id_servicios);
            echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
    break;

}
ob_end_flush();
?>