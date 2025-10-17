<?php
require "../config/Conexion.php";

class Ordenes {

    public function __construct(){}
    public function insertar($id_cliente, $id_usuarios, $fecha, $direccion, $tipo_pago, $observaciones, $equipos_json, $serviciosOrden_json) {
    $sql = "INSERT INTO orden (id_cliente, id_usuarios, fecha, direccion, tipo_pago, observaciones, estado)
            VALUES ('$id_cliente', '$id_usuarios', '$fecha', '$direccion', '$tipo_pago', '$observaciones', 0)";
    $id_orden_new = ejecutarConsulta_retornarID($sql);

    if (!$id_orden_new) return false;

    $equipos = json_decode($equipos_json, true);
    $serviciosOrden = json_decode($serviciosOrden_json, true);

    // Mapeamos cantidad de equipos por servicio (para dividir precio)
    $cantidades = [];
    foreach ($serviciosOrden as $s) {
        $cantidades[$s['id']] = $s['cantidad'];
    }

    $total_orden = 0;

    foreach ($equipos as $eq) {
        $id_equipo = $eq['id_equipo'];
        $sql2 = "INSERT INTO equipo_orden (id_orden, id_equipo) VALUES ('$id_orden_new', '$id_equipo')";
        $id_equipo_orden_new = ejecutarConsulta_retornarID($sql2);

        foreach ($eq['servicios'] as $id_serv) {
            // Buscar precio total del servicio
            $precio_serv = 0;
            foreach ($serviciosOrden as $s) {
                if ($s['id'] == $id_serv) {
                    $precio_serv = $s['precio'];
                    break;
                }
            }

            // Dividir el precio entre los equipos que lo tienen
            $cantidad_equipos = $cantidades[$id_serv] ?? 1;
            $valor_unitario = $precio_serv / $cantidad_equipos;

            $sql3 = "INSERT INTO equipo_servicio (id_equipo_orden, id_servicios, valor)
                     VALUES ('$id_equipo_orden_new', '$id_serv', '$valor_unitario')";
            ejecutarConsulta($sql3);

            $total_orden += $valor_unitario;
        }
    }

    // Actualizar costo total de la orden
    ejecutarConsulta("UPDATE orden SET costos = '$total_orden' WHERE id_orden = '$id_orden_new'");

    return true;
}



    public function editar($id_orden, $id_cliente, $id_usuarios, $fecha, $direccion, $tipo_pago, $observaciones, $equipos_json, $serviciosOrden_json)
    {
        try {
            // 1. Actualizamos datos básicos de la orden
            $sql = "UPDATE orden 
                    SET id_cliente='$id_cliente', 
                        id_usuarios='$id_usuarios', 
                        fecha='$fecha', 
                        direccion='$direccion', 
                        tipo_pago='$tipo_pago', 
                        observaciones='$observaciones'
                    WHERE id_orden='$id_orden'";
            ejecutarConsulta($sql);

            // 2. Decodificamos JSON
            $equipos = json_decode($equipos_json, true);
            $serviciosOrden = json_decode($serviciosOrden_json, true);

            // ⚠️ Si los JSON llegan vacíos, no seguimos ni borramos nada
            if (empty($equipos) || empty($serviciosOrden)) {
                return ['success' => true, 'message' => 'No hay datos de equipos o servicios'];
            }
            if (!empty($equipos) && !empty($serviciosOrden)) {
                // ✅ 3. Ahora sí eliminamos relaciones previas
                ejecutarConsulta("DELETE FROM equipo_servicio WHERE id_equipo_orden IN (SELECT id_equipo_orden FROM equipo_orden WHERE id_orden='$id_orden')");
                ejecutarConsulta("DELETE FROM equipo_orden WHERE id_orden='$id_orden'");
            }


            // 4. Calculamos cantidades
            $cantidades = [];
            foreach ($serviciosOrden as $s) {
                $cantidades[$s['id']] = $s['cantidad'];
            }

            $total_orden = 0;

            // 5. Insertamos nuevamente
            foreach ($equipos as $eq) {
                $id_equipo = $eq['id_equipo'];
                $sql2 = "INSERT INTO equipo_orden (id_orden, id_equipo) VALUES ('$id_orden', '$id_equipo')";
                $id_equipo_orden_new = ejecutarConsulta_retornarID($sql2);

                foreach ($eq['servicios'] as $id_serv) {
                    $precio_serv = 0;
                    foreach ($serviciosOrden as $s) {
                        if ($s['id'] == $id_serv) {
                            $precio_serv = $s['precio'];
                            break;
                        }
                    }

                    $cantidad_equipos = $cantidades[$id_serv] ?? 1;
                    $valor_unitario = $precio_serv / $cantidad_equipos;

                    $sql3 = "INSERT INTO equipo_servicio (id_equipo_orden, id_servicios, valor)
                            VALUES ('$id_equipo_orden_new', '$id_serv', '$valor_unitario')";
                    ejecutarConsulta($sql3);

                    $total_orden += $valor_unitario;
                }
            }

            // 6. Actualizamos el total
            ejecutarConsulta("UPDATE orden SET costos = '$total_orden' WHERE id_orden = '$id_orden'");

            return ['success' => true, 'message' => 'Orden actualizada correctamente', 'id_orden' => $id_orden];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al editar: ' . $e->getMessage()];
        }
    }

// // esta edita orden si existe un ca,bio , luego elimina los qeuipos y servico para regenerarlos e insertarlode vuelta solo cambia de posicion pero la logia se mantiene
//     public function editar($id_orden, $id_cliente, $id_usuarios, $fecha, $direccion, $tipo_pago, $observaciones, $equipos_json, $serviciosOrden_json)
//     {
//         try {
//             // 1. Actualizamos la orden
//             $sql = "UPDATE orden 
//                     SET id_cliente='$id_cliente', 
//                         id_usuarios='$id_usuarios', 
//                         fecha='$fecha', 
//                         direccion='$direccion', 
//                         tipo_pago='$tipo_pago', 
//                         observaciones='$observaciones'
//                     WHERE id_orden='$id_orden'";
//             ejecutarConsulta($sql);

//             // 2. Eliminamos relaciones previas
//             ejecutarConsulta("DELETE FROM equipo_servicio WHERE id_equipo_orden IN (SELECT id_equipo_orden FROM equipo_orden WHERE id_orden='$id_orden')");
//             ejecutarConsulta("DELETE FROM equipo_orden WHERE id_orden='$id_orden'");

//             // 3. Decodificamos JSON
//             $equipos = json_decode($equipos_json, true);
//             $serviciosOrden = json_decode($serviciosOrden_json, true);

//             // Validaciones básicas
//             if (empty($equipos) || empty($serviciosOrden)) {
//                 return ['success' => false, 'message' => 'No hay datos de equipos o servicios'];
//             }

//             // 4. Calculamos cantidades
//             $cantidades = [];
//             foreach ($serviciosOrden as $s) {
//                 $cantidades[$s['id']] = $s['cantidad'];
//             }

//             $total_orden = 0;

//             // 5. Insertamos nuevamente
//             foreach ($equipos as $eq) {
//                 $id_equipo = $eq['id_equipo'];
//                 $sql2 = "INSERT INTO equipo_orden (id_orden, id_equipo) VALUES ('$id_orden', '$id_equipo')";
//                 $id_equipo_orden_new = ejecutarConsulta_retornarID($sql2);

//                 foreach ($eq['servicios'] as $id_serv) {
//                     $precio_serv = 0;
//                     foreach ($serviciosOrden as $s) {
//                         if ($s['id'] == $id_serv) {
//                             $precio_serv = $s['precio'];
//                             break;
//                         }
//                     }

//                     $cantidad_equipos = $cantidades[$id_serv] ?? 1;
//                     $valor_unitario = $precio_serv / $cantidad_equipos;

//                     $sql3 = "INSERT INTO equipo_servicio (id_equipo_orden, id_servicios, valor)
//                             VALUES ('$id_equipo_orden_new', '$id_serv', '$valor_unitario')";
//                     ejecutarConsulta($sql3);

//                     $total_orden += $valor_unitario;
//                 }
//             }

//             // 6. Actualizamos total
//             ejecutarConsulta("UPDATE orden SET costos = '$total_orden' WHERE id_orden = '$id_orden'");

//             return ['success' => true, 'message' => 'Orden actualizada correctamente', 'id_orden' => $id_orden];
//         } catch (Exception $e) {
//             return ['success' => false, 'message' => 'Error al editar: ' . $e->getMessage()];
//         }
//     }

}
?>
