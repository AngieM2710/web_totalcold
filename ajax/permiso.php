<?php
require_once "../modelos/Permiso.php";
$r= new Permiso();

$id_permiso = isset($_POST["id_permiso"])? limpiarCadena($_POST["id_permiso"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";

switch ($_GET["op"]){
    case 'guardaryeditar':
        //si el id esta vacio --empty
        if(empty($id_permiso)){
            $rspta= $r->insertar($nombre);
            echo $rspta ? "Permiso registrado" : "No se pudo registrar el permiso";
        }
        else{
            $rspta= $r->editar($id_permiso,$nombre);
            echo $rspta ? "Permiso actualizado" : "No se pudo actualizar el permiso";
        }   
        break;

    case 'mostrar':
        $rspta=$r->mostrar($id_permiso,$nombre);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta=$r->listar();
        $data= Array(); //se declara un array
        while($reg=$rspta->fetch_object()){ //recorre los registros de la tabla
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id_permiso.')"><i class="fa fa-pencil-square-o"></i></button>',
                "1"=>$reg->nombre
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


}

?>