<?php
require_once "../modelos/Clientes.php"; // Asegúrate de que esta ruta sea correcta

$clientes = new Clientes();

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

    // agregar más 'case' para guardar, editar, mostrar, etc.
}
?>