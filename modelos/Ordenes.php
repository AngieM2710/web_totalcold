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
}
?>
