<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
if(strlen(session_id()) < 1)
	session_start();

Class Tecnicos
{
	//Implementamos nuestro constructor
	public function __construct()
	{	}

	public function listar(){
		$sql="SELECT u.*, p.*, up.*
		FROM usuarios u, permisos p, usuarios_permisos up 
		WHERE up.id_usuario = u.id_usuarios and up.id_permiso = p.id_permiso and p.id_permiso=2";
		return ejecutarConsulta($sql);		
	}

	public function mostrar($id_usuarios){
		$sql="SELECT u.*, p.*, up.*
			FROM usuarios u, permisos p, usuarios_permisos up 
			WHERE up.id_usuario = u.id_usuarios 
			AND up.id_permiso = p.id_permiso 
			AND p.id_permiso=2 
			AND u.id_usuarios='$id_usuarios'";   // OJO, mejor poner el alias
		return ejecutarConsultaSimpleFila($sql);
	}

	public function insertar($nombre, $apellido, $correo, $password_hash, $telefono, $imagen, $estado) {
        $sql = "INSERT INTO usuarios (nombre, apellido, correo, password, telefono, imagen, estado)
				VALUES ('$nombre','$apellido','$correo','$password_hash','$telefono','$imagen','1')";
		$id_usuario_new=ejecutarConsulta_retornarID($sql);

		$sql_detalle = "INSERT INTO usuarios_permisos(id_usuario, id_permiso) VALUES('$id_usuario_new', '2')";
		return ejecutarConsulta($sql_detalle);
	}

    // Editar usuario
   public function editar($id_usuarios, $nombre, $apellido, $correo, $password_hash, $telefono, $imagen, $estado) {
			$sql = "UPDATE usuarios SET
					nombre='$nombre',
					apellido='$apellido',
					correo='$correo',
					telefono='$telefono',
					imagen='$imagen',
					estado='$estado'";

			if(!empty($password_hash)){
				$sql .= ", password='$password_hash'";
			}

			$sql .= " WHERE id_usuarios='$id_usuarios'";
			return ejecutarConsulta($sql);
	}

	public function desactivar($id_usuarios)	{
		$sql="UPDATE usuarios SET estado ='0' WHERE id_usuarios='$id_usuarios'";
		return ejecutarConsulta($sql);
	}

	public function activar($id_usuarios)	{
		$sql="UPDATE usuarios SET estado='1' WHERE id_usuarios='$id_usuarios'";
		return ejecutarConsulta($sql);
	}

}

?>