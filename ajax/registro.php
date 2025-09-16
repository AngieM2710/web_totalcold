<?php
ob_start();
if (strlen(session_id()) < 1){
    session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Clientes.php";
$empleados= new Clientes();

$id_us = isset($_POST["id_us"])? limpiarCadena($_POST["id_us"]):"";
$cedula = isset($_POST["cedula"])? limpiarCadena($_POST["cedula"]):"";
$nombres = isset($_POST["nombres"])? limpiarCadena($_POST["nombres"]):"";
$apellidos = isset($_POST["apellidos"])? limpiarCadena($_POST["apellidos"]):"";
$email = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$login = isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave = isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen_us = isset($_POST["imagen_us"])? limpiarCadena($_POST["imagen_us"]):"";

switch ($_GET["op"]){
    case 'guardaryeditar':
        //si el id esta vacio --empty
        if(!file_exists($_FILES['imagen_us']['tmp_name']) || !is_uploaded_file($_FILES['imagen_us']['tmp_name']))
        {
            $imagen_us=$_POST["imagenactual"];
        }else{
            $ext = explode(".", $_FILES["imagen_us"]["name"]);
            if($_FILES['imagen_us']['type'] == "image/jpg" || $_FILES['imagen_us']['type'] == "image/jpeg" || $_FILES['imagen_us']['type'] == "image/png")
            {
                $imagen_us = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen_us"]["tmp_name"],"../files/usuarios/" . $imagen_us);
            }
        }

        //Hash SHA256 en la contraseña
        $clave1 = hash("MD5",$clave);
        $clavehash=hash("SHA256",$clave1);

        if(empty($id_us)){
            
            $rspta2 = $empleados->mostrar_login($login);
            $cantidad2 = count($rspta2);

            $rsptaced = $empleados->mostrar_cedula($cedula);
            $cantidadced = count($rsptaced);

            if($cantidad2 == 0){
                if(strlen($clave)<8){
                    echo $rspta2 ? "La contraseña debe tener mínimo 8 caracteres" : "La contraseña debe tener mínimo 8 caracteres";
                }else{
                        if($cantidadced == 0){
                            $rspta= $empleados->insertar($cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clavehash,$imagen_us);
                            echo $rspta ? "Usuario registrado" : "No se pudo registrar el usuario";
                        }else{
                            echo "Usuario ya está registrado en el sistema";
                        }
                }
            }else{
                echo "El nombre de usuario ya lo tiene otra persona";
            } 

           
        }
        else{
            $rspta3 = $empleados->mostrar_login2($login,$id_us);
            $cantidad3 = count($rspta3);
            $rspta4 = $empleados->mostrar_login($login);
            $cantidad4  = count($rspta4);

            $rsptaced2 = $empleados->mostrar_cedula2($cedula,$id_us);
            $cantidadced2 = count($rsptaced2);
            $rsptaced3 = $empleados->mostrar_cedula($cedula);
            $cantidadced3 = count($rsptaced3);

            if($cantidad3 == 1){
               
                    if(strlen($clave) == 64){
                        $rspta= $empleados->editar($id_us,$cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clave,$imagen_us);
                 echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
                 }else{
                     $rspta= $empleados->editar($id_us,$cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clavehash,$imagen_us);
                 echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
                 }
            }else if($cantidadced3 == 0){
                    if(strlen($clave_us) == 64){
                        $rspta= $empleados->editar($id_us,$cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clave,$imagen_us);
                 echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
                 }else{
                     $rspta= $empleados->editar($id_us,$cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clavehash,$imagen_us);
                 echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
                 }
               
            }else if($cantidad4 == 0){
               
                    if(strlen($clave_us) == 64){
                        $rspta= $empleados->editar($id_us,$cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clave,$imagen_us);
                 echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
                 }else{
                     $rspta= $empleados->editar($id_us,$cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clavehash,$imagen_us);
                 echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
                 }
                
                    if(strlen($clave_us) == 64){
                        $rspta= $empleados->editar($id_us,$cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clave,$imagen_us);
                 echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
                 }else{
                     $rspta= $empleados->editar($id_us,$cedula,$nombres,$apellidos,$email,$telefono,$direccion,$login,$clavehash,$imagen_us);
                 echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
                 }
                
            }else{
                echo "El nombre de usuario ya lo tiene otra persona";
            }
        }   
        break;

    case 'mostrar':
		$rspta=$empleados->mostrar($id_us);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
        break;

    case 'listar':

     $rspta=$empleados->listar();
        $data= Array(); //se declara un array
        while($reg=$rspta->fetch_object()){ //recorre los registros de la tabla
            $data[]=array(
                "0"=>($reg->estado_us)?
                ' <a href="#" onclick="mostrar('.$reg->id_us.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>'.
                ' <a href="#" onclick="desactivar('.$reg->id_us.')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>':
                ' <a href="#" onclick="mostrar('.$reg->id_us.')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>'.
                ' <a href="#" onclick="activar('.$reg->id_us.')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>',
                "1"=>$reg->cedula,
                "2"=>$reg->nombres,
                "3"=>$reg->apellidos,
                "4"=>$reg->telefono,
                "5"=>$reg->direccion,
                "6"=>$reg->email,
                "7"=>$reg->login,
                "8"=>$reg->permiso,
                "9" => "<img src='../files/usuarios/".$reg->imagen_us."' style='height: 50px; width: 50px; border-radius: 50%;' alt='Imagen de usuario'>",                
                "10"=>($reg->estado_us)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
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

case 'desactivar':
            $rspta=$empleados->desactivar($id_us);
            echo $rspta ? "Usuario desactivado" : "Usuario no se puede desactivar";
        break;


    case 'activar':
            $rspta=$empleados->activar($id_us);
            echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
        break;

case 'validacionusuario':
            $rspta=$empleados->validacionusuario($cedula);
            $fetch=$rspta->fetch_object();
            echo json_encode($fetch);
    
            break;
}
ob_end_flush();
?>