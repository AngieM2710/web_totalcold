<?php
 ini_set('display_errors', 1); 
 ini_set('display_startup_errors', 1); 
 error_reporting(E_ALL); 
 ob_start(); 
 if (strlen(session_id()) < 1) 
    session_start(); 
require_once "../modelos/Contrasena.php"; 
$usuarios = new Usuario(); 

$id_usuarios = isset($_POST["id_usuarios"])? limpiarCadena($_POST["id_usuarios"]):"";
$email = isset($_POST["correo"])? limpiarCadena($_POST["correo"]):"";
$clave = isset($_POST["password"])? limpiarCadena($_POST["password"]):""; 
$codigo = isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):""; 

switch ($_GET["op"]){ 
    case 'emailUsuario': 
        $rspta = $usuarios->validacionusuarioemail($email); 
        echo json_encode($rspta);
    break; 
    case 'verificarCodigo': 
        $rspta = $usuarios->verificarCodigoSeguridad($email, $codigo); 
        echo json_encode($rspta); 
    // Si existe devuelve el usuario, si no devuelve null 
    break;
    case 'guardaryeditar': 
        // Encriptar la contraseña con password_hash 
        $clavehash = password_hash($clave, PASSWORD_BCRYPT);
        error_log("ID recibido: " . $id_usuarios); 
        error_log("Clave recibida: " . $clave); 
        $rspta = $usuarios->actualizarSoloClave($id_usuarios,$clavehash); 
        echo json_encode($rspta); break; 
     }
         
ob_end_flush();
?>