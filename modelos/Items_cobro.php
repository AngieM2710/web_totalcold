<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
if(strlen(session_id()) < 1)
	session_start();

Class Items
{
	//Implementamos nuestro constructor
	public function __construct()
	{	}

	public function listar(){
		$sql="SELECT * FROM items_cobro Order by id_servicios";
		return ejecutarConsulta($sql);		
	}

	public function mostrar($id_item_cobro){
		$sql="SELECT * FROM items_cobro where 
		id_item_cobro  = '$id_item_cobro'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function insertar($id_servicios,$nombre)	{
		$sql="INSERT INTO items_cobro (id_servicios,nombre,estado)
        VALUES ('$id_servicios','$nombre','1')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_item_cobro,$id_servicios,$nombre)	{
		$sql="UPDATE items_cobro SET nombre ='$nombre',id_servicios='$id_servicios'
		WHERE id_item_cobro='$id_item_cobro'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id_item_cobro)	{
		$sql="UPDATE items_cobro SET estado ='0' WHERE id_item_cobro='$id_item_cobro'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_item_cobro)	{
		$sql="UPDATE items_cobro SET estado='1' WHERE id_item_cobro='$id_item_cobro'";
		return ejecutarConsulta($sql);
	}

}

?>