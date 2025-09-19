<?php 
//Incluímos inicialmente la conexión a la base de datos

/* use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src//SMTP.php';
require '../PHPMailer/src//Exception.php'; */

require "../config/Conexion.php";
if(strlen(session_id()) < 1)
	session_start();

Class Usuario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function validacionusuarioemail($email){
		// consulto quien tiene ese correo y lo obtengo como rspta
        $sql= "SELECT * FROM usuarios where correo = '$email'";
		$rspta = ejecutarConsultaSimpleFila($sql); // Obtener una fila como array asociativo

	    if ($rspta) {
        // Generar código aleatorio (6 caracteres)
        $codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        // Guardar el código en la base de datos
        $update = "UPDATE usuarios SET codigo = '$codigo' WHERE id_usuarios = '{$rspta['id_usuarios']}'";
        ejecutarConsulta($update);

        // Agregar el código al array de respuesta (opcional)
  		/*   $rspta['codigo_generado'] = $codigo; */
  		 // Consulta 2: volver a obtener los datos actualizados
		$sql2 = "SELECT * FROM usuarios WHERE correo = '$email'";
		$usuarioActualizado = ejecutarConsultaSimpleFila($sql2);

		     	// Llamar a la función de enviar correo
/* 				$this->enviarCorreo(
					$usuarioActualizado['nombres'], 
					$usuarioActualizado['apellidos'], 
					$usuarioActualizado['email'], 
					$codigo 
				); */
        return $usuarioActualizado; // envia los datos actulizados
		/* return array('codigo_generado' => $usuarioActualizado['codigo']); */
		} else {
		  return ["error" => "Correo no encontrado"];
		}
	}
	public function verificarCodigoSeguridad($email, $codigo) {
		$sql = "SELECT * FROM usuarios 
				WHERE  correo = '$email' 
				AND codigo = '$codigo'";
		return ejecutarConsultaSimpleFila($sql); // Retorna una fila si hay coincidencia
	}

	public function actualizarSoloClave($id_usuarios,$clavehash) {
		
		   $sql = "UPDATE usuarios 
            SET password = '$clavehash' 
            WHERE id_usuarios = '$id_usuarios'";

		return ejecutarConsulta($sql);
	}



}

?>