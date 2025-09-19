<?php
require "../config/Conexion.php";
if(strlen(session_id()) < 1)
    session_start();

class Usuario
{
    public function __construct() {}

    // Insertar usuario
    public function insertar($nombre, $apellido, $correo, $password_hash, $telefono, $imagen, $estado = 1) {
        $sql = "INSERT INTO usuarios (nombre, apellido, correo, password, telefono, imagen, estado)
                VALUES ('$nombre','$apellido','$correo','$password_hash','$telefono','$imagen','$estado')";
        return ejecutarConsulta($sql);
    }

    // Editar usuario
    public function editar($id_usuarios, $nombre, $apellido, $correo, $password_hash, $telefono, $imagen, $estado) {
        // Solo actualizar password si viene uno nuevo
        $sqlPassword = '';
        if(!empty($password_hash)){
            $sqlPassword = ", password='$password_hash'";
        }

        $sql = "UPDATE usuarios SET
                nombre='$nombre',
                apellido='$apellido',
                correo='$correo',
                telefono='$telefono',
                imagen='$imagen',
                estado='$estado'
                $sqlPassword
                WHERE id_usuarios='$id_usuarios'";
        return ejecutarConsulta($sql);
    }

    // Activar usuario
    public function activar($id_usuarios){
        $sql = "UPDATE usuarios SET estado='1' WHERE id_usuarios='$id_usuarios'";
        return ejecutarConsulta($sql);
    }

    // Desactivar usuario
    public function desactivar($id_usuarios){
        $sql = "UPDATE usuarios SET estado='0' WHERE id_usuarios='$id_usuarios'";
        return ejecutarConsulta($sql);
    }

    // Mostrar un usuario
    public function mostrar($id_usuarios){
        $sql = "SELECT * FROM usuarios WHERE id_usuarios='$id_usuarios'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar todos los usuarios
    public function listar(){
        $sql = "SELECT * FROM usuarios";
        return ejecutarConsulta($sql);
    }

    // Verificar login
   /*  public function verificar($correo){
        // Solo selecciona el usuario; la verificación del password se hace en AJAX/controlador
        $sql = "SELECT * FROM usuarios WHERE correo='$correo' AND estado='1'";
        return ejecutarConsulta($sql);
    } */

    
	public function verificar($correo)
    {
    	$sql="SELECT u.*, p.*, up.*
		FROM usuarios u, permisos p, usuarios_permisos up 
		WHERE up.id_usuario = u.id_usuarios and up.id_permiso = p.id_permiso and
	    u.correo='$correo' and u.estado = '1'"; 
    	return ejecutarConsulta($sql);  
    }

    public function listarmarcados($id_usuarios)
	{
		$sql="SELECT * FROM usuarios_permisos WHERE id_usuario='$id_usuarios'";
		return ejecutarConsulta($sql);
	}

}
?>
