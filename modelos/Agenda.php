<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
if(strlen(session_id()) < 1)
	session_start();
date_default_timezone_set('America/Guayaquil');
Class Agenda
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

public function listar($fecha_inicio = '', $fecha_fin = '', $id_tec = '', $id_cli = '', $estado = '')
{
    // Filtros dinámicos
    $where = [];
    
    if ($fecha_inicio && $fecha_fin) $where[] = "o.fecha >= '$fecha_inicio' AND o.fecha <= '$fecha_fin'";
    if ($id_tec) $where[] = "o.id_usuarios = $id_tec";
    if ($id_cli) $where[] = "o.id_cliente = $id_cli";
    if ($estado) {
        if ($estado == 'Pendiente') $where[] = "o.estado = 0";
        elseif ($estado == 'Terminado') $where[] = "o.estado = 1";
    }

    $whereSQL = count($where) ? ' AND '.implode(' AND ', $where) : '';

    $sql = "SELECT
                o.*, 
                e.marca AS Marca, 
                i.descripcion AS servicio,
                CONCAT(t.nombre, ' ', t.apellido) AS nombre_tecnico,
                CONCAT(c.nombre, ' ', c.apellido) AS nombre_cliente,
                o.estado AS estado_orden,
                GROUP_CONCAT(DISTINCT i.descripcion SEPARATOR ', ') AS servicios
            FROM 
                orden o
                JOIN cliente c ON o.id_cliente = c.id_cliente
                JOIN usuarios t ON o.id_usuarios = t.id_usuarios
                JOIN equipo_orden eo ON o.id_orden = eo.id_orden
                JOIN equipos e ON eo.id_equipo = e.id_equipo
                JOIN equipo_servicio es ON eo.id_equipo_orden = es.id_equipo_orden
                JOIN servicios i ON es.id_servicios = i.id_servicios
            WHERE 1=1 $whereSQL
            GROUP BY o.id_orden
            ORDER BY o.fecha ASC, o.id_orden ASC;";

    return ejecutarConsulta($sql);        
}

public function obtenerDetalleOrden($id_orden)
{
    $sql = "SELECT 
                o.id_orden,  
                o.fecha,  
                o.id_cliente,
                o.id_usuarios,
                o.direccion,
                o.observaciones,
                o.costos,
                o.tipo_pago,
                o.estado AS estado_orden,

                e.id_equipo, 
                e.marca,
                e.modelo,
                e.capacidad,

                i.id_servicios,
                i.descripcion AS servicio,
                es.valor,
                es.id_equipo_servicio AS id_detalle_servivio_orden,
                 eo.id_equipo_orden  -- ✅ Agregado
            FROM orden o
            INNER JOIN equipo_orden eo ON o.id_orden = eo.id_orden
            INNER JOIN equipos e ON eo.id_equipo = e.id_equipo
            INNER JOIN equipo_servicio es ON eo.id_equipo_orden = es.id_equipo_orden
            INNER JOIN servicios i ON es.id_servicios = i.id_servicios
            WHERE o.id_orden = '$id_orden'
            ORDER BY e.id_equipo, i.id_servicios";

    return ejecutarConsulta($sql);
}

/* public function obtenerDetalleOrden($id_orden)
{
    $sql = "SELECT 
                o.id_orden,  
                o.fecha,  
                o.id_cliente,
                o.id_usuarios,
                o.direccion,
                o.observaciones,
                o.costos,
                o.tipo_pago,
                o.estado AS estado_orden,

                eo.id_equipo_orden,  -- ✅ Agregado

                e.id_equipo, 
                e.marca,
                e.modelo,
                e.capacidad,

                i.id_servicios,
                i.descripcion AS servicio,
                es.valor,
                es.id_equipo_servicio AS id_detalle_orden
            FROM orden o
            INNER JOIN equipo_orden eo ON o.id_orden = eo.id_orden
            INNER JOIN equipos e ON eo.id_equipo = e.id_equipo
            INNER JOIN equipo_servicio es ON eo.id_equipo_orden = es.id_equipo_orden
            INNER JOIN servicios i ON es.id_servicios = i.id_servicios
            WHERE o.id_orden = '$id_orden'
            ORDER BY eo.id_equipo_orden, i.id_servicios";

    return ejecutarConsulta($sql);
} */


/* 
	public function listar()
	{
		$sql="SELECT
            o.*, 
            e.marca AS Marca, 
            i.descripcion AS servicio,
            CONCAT(t.nombre, ' ', t.apellido) AS nombre_tecnico,
            CONCAT(c.nombre, ' ', c.apellido) AS nombre_cliente,
            o.estado AS estado_orden,
            GROUP_CONCAT(DISTINCT i.descripcion SEPARATOR ', ') AS servicios
            -- Puedes agregar más campos de otras tablas si los necesitas individualmente
        FROM 
            orden o, 
            cliente c, 
            usuarios t, 
            equipo_orden eo,
            equipos e,
            equipo_servicio es,
            servicios i

        WHERE 
            o.id_cliente = c.id_cliente -- 1. Orden y Cliente
            AND
            o.id_usuarios = t.id_usuarios -- 2. Orden y Técnico
            AND
            o.id_orden = eo.id_orden -- 3. Orden y Equipo_Orden (Equipos asignados a la orden)
            AND
            eo.id_equipo = e.id_equipo -- 4. Equipo_Orden y Equipos (Detalles del equipo)
            AND
            eo.id_equipo_orden = es.id_equipo_orden -- 5. Orden/Equipo y Equipo_Servicio (Servicios dados a ese equipo en esa orden)
            AND
            -- 6. Equipo_Servicio y Items (Detalles del servicio)
            es.id_servicios = i.id_servicios
            ORDER BY 
            o.fecha ASC, o.id_orden ASC;";
             
		return ejecutarConsulta($sql);		
	} */


}

?>