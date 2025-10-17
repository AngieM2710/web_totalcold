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

/*    public function insertar($id_cliente, $id_usuarios, $fecha, $direccion, $tipo_pago, $observaciones, $equipos_json, $serviciosOrden) {
    $sql = "INSERT INTO orden (id_cliente, id_usuarios, fecha, direccion, tipo_pago, observaciones, estado)
            VALUES ('$id_cliente', '$id_usuarios', '$fecha', '$direccion', '$tipo_pago', '$observaciones', 0)";
    $id_orden_new = ejecutarConsulta_retornarID($sql);

    if ($id_orden_new) {
        $equipos = json_decode($equipos_json, true);
        foreach ($equipos as $eq) {
            $id_equipo = $eq['id_equipo'];
            $sql2 = "INSERT INTO equipo_orden (id_orden, id_equipo) VALUES ('$id_orden_new', '$id_equipo')";
            $id_equipo_orden_new = ejecutarConsulta_retornarID($sql2);

            foreach ($eq['servicios'] as $serv) {
                // Valor opcional si lo agregas más adelante
                $sql3 = "INSERT INTO equipo_servicio (id_equipo_orden, id_servicios) VALUES ('$id_equipo_orden_new', '$serv')";
                ejecutarConsulta($sql3);
            }
        }
        return true;
    } else {
        return false;
    }
} */



    public function editar($id_orden, $id_cli, $id_tec, $fecha, $descripcion, $equipos_json) {
        $sql = "UPDATE orden SET id_cli='$id_cli', id_tec='$id_tec', fecha='$fecha', descripcion='$descripcion'
                WHERE id_orden='$id_orden'";
        ejecutarConsulta($sql);

        // Borrar relaciones viejas
        ejecutarConsulta("DELETE FROM orden_equipo WHERE id_orden='$id_orden'");

        // Reinsertar equipos y servicios
        $equipos = json_decode($equipos_json, true);
        foreach ($equipos as $eq) {
            $nombre_equipo = $eq['nombre'];
            $sql2 = "INSERT INTO orden_equipo (id_orden, nombre_equipo) VALUES ('$id_orden', '$nombre_equipo')";
            $id_equipo_new = ejecutarConsulta_retornarID($sql2);

            foreach ($eq['servicios'] as $serv) {
                $sql3 = "INSERT INTO equipo_servicio (id_orden_equipo, nombre_servicio) VALUES ('$id_equipo_new', '$serv')";
                ejecutarConsulta($sql3);
            }
        }

        return true;
    }

    public function mostrar($id_orden) {
        $sql = "SELECT * FROM orden WHERE id_orden='$id_orden'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar() {
        $sql = "SELECT o.id_orden, c.nombre AS cliente, t.nombre AS tecnico, o.fecha, o.descripcion 
                FROM orden o 
                INNER JOIN cliente c ON o.id_cli = c.id_cli
                INNER JOIN tecnico t ON o.id_tec = t.id_tec
                ORDER BY o.id_orden DESC";
        return ejecutarConsulta($sql);
    }
<<<<<<< HEAD


    // ============================================================
    // NUEVO MÉTODO: Obtener Orden Completa con Equipos y Servicios
    // ============================================================
    public function obtenerOrdenCompleta($id_orden) {
        $sqlOrden = "SELECT o.id_orden, o.id_cliente, o.id_usuarios, o.fecha, 
                            o.direccion, o.tipo_pago, o.observaciones, o.costos, o.estado,
                            CONCAT(c.nombre, ' ', c.apellido) AS cliente
                     FROM orden o
                     INNER JOIN cliente c ON o.id_cliente = c.id_cliente
                     WHERE o.id_orden = '$id_orden'";
        $orden = ejecutarConsultaSimpleFila($sqlOrden);
        if (!$orden) return false;

        // 🔹 Obtener equipos asociados
        $sqlEquipos = "SELECT eo.id_equipo_orden, e.id_equipo, e.modelo, e.marca, e.capacidad
                       FROM equipo_orden eo
                       INNER JOIN equipos e ON eo.id_equipo = e.id_equipo
                       WHERE eo.id_orden = '$id_orden'";
        $equipos = ejecutarConsulta($sqlEquipos);

        $equipos_array = [];
        while ($eq = $equipos->fetch_object()) {
            // 🔹 Para cada equipo, obtener sus servicios
            $sqlServicios = "SELECT es.id_equipo_servicio, s.descripcion, es.valor, es.estado_es
                             FROM equipo_servicio es
                             INNER JOIN servicios s ON es.id_servicios = s.id_servicios
                             WHERE es.id_equipo_orden = '$eq->id_equipo_orden'";
            $servicios = ejecutarConsulta($sqlServicios);

            $servicios_array = [];
            while ($sv = $servicios->fetch_object()) {
                $servicios_array[] = [
                    "id_equipo_servicio" => $sv->id_equipo_servicio,
                    "nombre_servicio"    => $sv->descripcion,
                    "valor"              => $sv->valor,
                    "estado"             => $sv->estado_es
                ];
            }

            $equipos_array[] = [
                "id_equipo_orden" => $eq->id_equipo_orden,
                "id_equipo"       => $eq->id_equipo,
                "modelo"          => $eq->modelo,
                "marca"           => $eq->marca,
                "capacidad"       => $eq->capacidad,
                "servicios"       => $servicios_array
            ];
        }

        return [
            "orden"   => $orden,
            "equipos" => $equipos_array
        ];
    }

    public function actualizarEstadoOrden($id_orden, $estado) {
    $sql = "UPDATE orden SET estado = '$estado' WHERE id_orden = '$id_orden'";
    return ejecutarConsulta($sql);
    }

    public function actualizarEstadoServicio($id_equipo_servicio, $estado) {
        // Asumiendo que el campo de estado del servicio se llama 'estado_es'
        $sql = "UPDATE equipo_servicio SET estado_es = '$estado' WHERE id_equipo_servicio = '$id_equipo_servicio'";
        return ejecutarConsulta($sql);
    }



=======
>>>>>>> parent of 3cfc510 (c)
}




?>
