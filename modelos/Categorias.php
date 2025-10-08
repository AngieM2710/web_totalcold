<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
if(strlen(session_id()) < 1)
	session_start();

Class Categorias
{
	//Implementamos nuestro constructor
	public function __construct()
	{	}

	public function listar(){
		$sql="SELECT * FROM servicios ";
		return ejecutarConsulta($sql);		
	}

	public function mostrar($id_servicios){
		$sql="SELECT * FROM servicios where 
		id_servicios  = '$id_servicios'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function insertar($descripcion)	{
		$sql="INSERT INTO servicios (descripcion, estado)
        VALUES ('$descripcion','1')";
		return ejecutarConsulta($sql);
	}

	public function editar($id_servicios,$descripcion)	{
		$sql="UPDATE servicios SET descripcion ='$descripcion'
		WHERE id_servicios='$id_servicios'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($id_servicios)	{
		$sql="UPDATE servicios SET estado ='0' WHERE id_servicios='$id_servicios'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_servicios)	{
		$sql="UPDATE servicios SET estado='1' WHERE id_servicios='$id_servicios'";
		return ejecutarConsulta($sql);
	}

}

?>