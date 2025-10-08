<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
if(strlen(session_id()) < 1)
	session_start();

Class Agenda
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}



	public function listar()
	{
		$sql="SELECT
            o.*, 
            e.marca AS Marca, 
            i.descripcion AS servicio,
            CONCAT(t.nombre, ' ', t.apellido) AS nombre_tecnico,
            CONCAT(c.nombre, ' ', c.apellido) AS nombre_cliente,
            o.estado AS estado_orden
            -- Puedes agregar más campos de otras tablas si los necesitas individualmente
        FROM 
            orden o, 
            cliente c, 
            usuarios t, 
            equipo_orden eo,
            equipos e,
            equipo_servicio es,
            items i

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
            es.id_servicios = i.id_servicios"; 
		return ejecutarConsulta($sql);		
	}

}

?>