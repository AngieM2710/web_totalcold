<?php
require_once "../modelos/Clientes.php"; // Asegúrate de que esta ruta sea correcta

$clientes = new Clientes();

switch ($_GET["op"]) {
    case 'listar':
        $rspta = $clientes->listar(); // Asume que tienes un método 'listar()' en tu modelo Clientes
        $data = [];

        while ($reg = $rspta->fetch_object()) {
            $data[] = [
                // Columna de acciones y botones
                "0" => '<a href="#" onclick="mostrar(' . $reg->id_cliente . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>',
                // Las siguientes columnas deben coincidir con los datos que quieres mostrar
                "1" => $reg->cedula,
                "2" => $reg->nombre,
                "3" => $reg->apellido,
                "4" => $reg->telefono,
                "5" => $reg->correo,
                "6" => ($reg->estado) ? '<span class="label bg-green">Activo</span>' : '<span class="label bg-red">Inactivo</span>'
            ];
        }

        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($results);
        break;

    // agregar más 'case' para guardar, editar, mostrar, etc.
}
?>