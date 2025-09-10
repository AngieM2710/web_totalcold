<?php 
require_once "global.php";

$conexion=new mysqli(DB_HOST, DB_USERNAME,DB_PASSWORD,DB_NAME);

mysqli_query( $conexion, 'SET NAMES "'.DB_ENCODE.'"');

//Si tenemos un posible error en la conexión, lo mostramos
if (mysqli_connect_errno())
{
	printf("Falló conexión a la base de datos: %s\n", mysqli_conect_error());
	exit();
}

if(!function_exists('ejecutarConsulta')){
	function ejecutarConsulta($sql){
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	function ejecutarConsulta2($sql, $params = []) {
        global $conexion;
        $stmt = $conexion->prepare($sql);
        if ($params) {
            $types = str_repeat('s', count($params)); 
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt;
    }

	function ejecutarConsultaSimpleFila($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$row = $query->fetch_assoc();
		return $row;	
	}

	function ejecutarConsulta_retornarID($sql){
		global $conexion;
		$query = $conexion->query($sql);
		return $conexion->insert_id;
	}

	function ejecutarConsultaCantidad($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$campo = $query->fetch_array();
		return $campo['cantidad'];
	}

	function ejecutarConsultaID10($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$campo = $query->fetch_array();
		return $campo['id_info'];
	}

	function ejecutarConsultaCliente($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$campo = $query->fetch_array();
		return $campo['id_cli'];
	}

	function limpiarCadena($str){
		global $conexion;
		$str = mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str);
	}

	function ejecutarConsultaContar($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$empleados = $query->fetch_all(MYSQLI_ASSOC);
		return $empleados;
	}

	function ejecutarConsultaContarNum($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$num = $query->fetch_all(MYSQLI_ASSOC);
		return $num;
	}

	function ejecutarConsultaContarNum3($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$num3 = $query->fetch_all(MYSQLI_ASSOC);
		return $num3;
	}

	function ejecutarConsultaContar2($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$empleados = $query->fetch_all(MYSQLI_ASSOC);
		return $stockP;
	}

	function ejecutarConsultaID1($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$campo = $query->fetch_array();
		return $campo['id_cliente'];
	}

	function ejecutarConsultaIDrest($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$campo = $query->fetch_array();
		return $campo['id_rest'];
	}

	function ejecutarConsultaIDart($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$campo = $query->fetch_array();
		return $campo['id_art'];
	}

	function ejecutarConsultaID8($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$campo = $query->fetch_array();
		return $campo['mesactual'];
	}

	function ejecutarConsultaTotal($sql){
		global $conexion;
		$query = $conexion->query($sql);
		$campo = $query->fetch_array();
		return $campo['total'];
	}
}
?>