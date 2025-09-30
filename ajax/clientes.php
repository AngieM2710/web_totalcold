<?php
require_once "../modelos/Clientes.php"; // Asegúrate de que esta ruta sea correcta

$clientes = new Clientes();

// Limpieza de entradas
$id_cliente = isset($_POST["id_cliente"]) ? limpiarCadena($_POST["id_cliente"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$apellido = isset($_POST["apellido"]) ? limpiarCadena($_POST["apellido"]) : "";
$correo = isset($_POST["correo"]) ? limpiarCadena($_POST["correo"]) : "";
$cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";


switch ($_GET["op"]) {
    case 'listar':
        $rspta = $clientes->listar(); // Asume que tienes un método 'listar()' en tu modelo Clientes
        $data = Array(); 

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                // Columna de acciones y botones
              
                // Las siguientes columnas deben coincidir con los datos que quieres mostrar
                "0" => $reg->cedula,
                "1" => $reg->nombre,
                "2" => $reg->apellido,
                "3" => $reg->correo,
                "4" => $reg->telefono,
                "5" => ($reg->estado) ? '<span class="label bg-green">Activo</span>' : '<span class="label bg-red">Inactivo</span>',
                "6" => ($reg->estado) ?
                    '<a href="#" onclick="mostrar(' . $reg->id_cliente . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="desactivar(' . $reg->id_cliente . ')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>' :
                    '<a href="#" onclick="mostrar(' . $reg->id_cliente . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="activar(' . $reg->id_cliente . ')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>' 

                /* "6" => '<a href="#" onclick="mostrar(' . $reg->id_cliente . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>',*/
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
        if (empty($id_cliente)) {
            // Registro
            $rspta = $clientes->insertar($cedula ,$nombre, $apellido, $telefono, $correo, $estado);
            echo $rspta ? "Usuario registrado" : "No se pudo registrar el usuario";
        } else {
            // Actualización
            $rspta = $clientes->editar(
                $id_cliente,
                $cedula,
                $nombre,
                $apellido,
                $telefono,
                $correo,
                $estado
            );
            echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
        }
    break;

    case 'mostrar':
		$rspta=$clientes->mostrar($id_cliente);
		echo json_encode($rspta);
    break;

    case 'desactivar':
            $rspta=$clientes->desactivar($id_cliente);
            echo $rspta ? "Categoría desactivada" : "Categoría no se puede desactivar";
    break;

    case 'activar':
            $rspta=$clientes->activar($id_cliente);
            echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
    break;
    
/*     case 'buscar':
        $texto = isset($_POST["texto"])? limpiarCadena($_POST["texto"]):"";
        $rspta = $clientes->buscar($texto);
        $data = [];
        while ($reg = $rspta->fetch_object()){
            $data[] = [
                "id" => $reg->id_cliente,
                "nombre" => $reg->nombre,
                "apellido" => $reg->nombre
            ];
        }
        echo json_encode($data);
    break; */
    case 'selectClientes':
        $rspta = $clientes->listar();
        while ($reg = $rspta->fetch_object()){
            echo '<option value="'.$reg->id_cliente.'">'.$reg->nombre.' '.$reg->apellido.'</option>';
        }
    break;

}
?>