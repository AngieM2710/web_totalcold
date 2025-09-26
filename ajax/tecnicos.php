<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
if (strlen(session_id()) < 1){
    session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Tecnicos.php";
$tecnicos= new Tecnicos();

function generarHash($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Limpieza de entradas
$id_usuarios = isset($_POST["id_usuarios"]) ? limpiarCadena($_POST["id_usuarios"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$apellido = isset($_POST["apellido"]) ? limpiarCadena($_POST["apellido"]) : "";
$correo = isset($_POST["correo"]) ? limpiarCadena($_POST["correo"]) : "";
$password = isset($_POST["password"]) ? limpiarCadena($_POST["password"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";


switch ($_GET["op"]){
    
    case 'listar':
        /* echo("estamos aqui en listar"); */
        $rspta = $tecnicos->listar();
        $data= Array(); 
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => "<img src='../files/usuarios/tecnicos/" . $reg->imagen . "' style='height:40px;width:40px;border-radius:50%;' alt='Imagen de usuario'>",
                "1" => $reg->nombre,
                "2" => $reg->apellido,
                "3" => $reg->correo,
                "4" => $reg->telefono,
                "5" => ($reg->estado) ? '<span class="label bg-green">Activo</span>' : '<span class="label bg-red">Inactivo</span>',
                "6" => ($reg->estado) ?
                    '<a href="#" onclick="mostrar(' . $reg->id_usuarios . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="desactivar(' . $reg->id_usuarios . ')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>' :
                    '<a href="#" onclick="mostrar(' . $reg->id_usuarios . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="activar(' . $reg->id_usuarios . ')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>'              
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
        if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
        {
            $imagen=$_POST["imagenactual"];
        }else{
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"],"../files/usuarios/tecnicos/" . $imagen);
            }
        }

        // Generar hash solo si se envió nueva contraseña
        $password_hash = !empty($password) ? generarHash($password) : null;
        if (empty($id_usuarios)) {
            // Registro
            $rspta = $tecnicos->insertar($nombre, $apellido, $correo, $password_hash, $telefono, $imagen, $estado);
            echo $rspta ? "Usuario registrado" : "No se pudo registrar el usuario";
        } else {
            // Actualización
            $rspta = $tecnicos->editar(
                $id_usuarios,
                $nombre,
                $apellido,
                $correo,
                $password_hash ?? $password, // aquí solo mandas si hay hash nuevo
                $telefono,
                $imagen,
                $estado
            );
            /* echo("rsouesta" $rspta); */
            echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
        }
    break;

    case 'mostrar':
		$rspta=$tecnicos->mostrar($id_usuarios);
		echo json_encode($rspta);
    break;

    case 'desactivar':
            $rspta=$tecnicos->desactivar($id_usuarios);
            echo $rspta ? "Categoría desactivada" : "Categoría no se puede desactivar";
    break;

    case 'activar':
            $rspta=$tecnicos->activar($id_usuarios);
            echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
    break;

}
ob_end_flush();
?>