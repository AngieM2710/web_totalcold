<?php
ob_start();
if (strlen(session_id()) < 1) {
    session_start();
}

require_once "../modelos/Usuarios.php";
$usuarios = new Usuario();

// Funciones auxiliares para hashing
function generarHash($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verificarClave($claveIngresada, $hashAlmacenado) {
    return password_verify($claveIngresada, $hashAlmacenado);
}

// Limpieza de entradas
$id_usuarios = isset($_POST["id_usuarios"]) ? limpiarCadena($_POST["id_usuarios"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$apellido = isset($_POST["apellido"]) ? limpiarCadena($_POST["apellido"]) : "";
$correo = isset($_POST["correo"]) ? limpiarCadena($_POST["correo"]) : "";
$password = isset($_POST["password"]) ? limpiarCadena($_POST["password"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";

switch ($_GET["op"]) {
    case 'listar':
        /*  echo("estamos aqui en listar"); */
        $rspta = $usuarios->listar();
        $data= Array(); 
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => "<img src='../files/usuarios/" . $reg->imagen . "' style='height:40px;width:40px;border-radius:50%;' alt='Imagen de usuario'>",
                "1" => $reg->nombre,
                "2" => $reg->apellido,
                "3" => $reg->correo,
                "4" => $reg->telefono,
                "5" => ($reg->estado) ? '<span class="label bg-green">Activo</span>' : '<span class="label bg-red">Inactivo</span>',
                "6" => ($reg->estado) ?
                    '<a href="#" onclick="mostrar(' . $reg->id_usuarios . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="desactivar(' . $reg->id_usuarios . ')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>' :
                    '<a href="#" onclick="mostrar(' . $reg->id_usuarios . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="activar(' . $reg->id_usuarios . ')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>'              
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
    break;

    case 'guardaryeditar':
        // Manejo de imagen
        if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
        {
            $imagen=$_POST["imagenactual"];
        }else{
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"],"../files/usuarios/" . $imagen);
            }
        }

        // Generar hash solo si se envió nueva contraseña
        $password_hash = !empty($password) ? generarHash($password) : null;
        if (empty($id_usuarios)) {
            // Registro
            $rspta = $usuarios->insertar($nombre, $apellido, $correo, $password_hash, $telefono, $imagen, $estado);
            echo $rspta ? "Usuario registrado" : "No se pudo registrar el usuario";
        } else {
            // Actualización
            $rspta = $usuarios->editar(
                $id_usuarios,
                $nombre,
                $apellido,
                $correo,
                $password_hash ?? $password, // aquí solo mandas si hay hash nuevo
                $telefono,
                $imagen,
                $estado
            );
            /* echo("rsouesta" $rspta); */
            echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
        }
        break;

    case 'mostrar':
        $rspta = $usuarios->mostrar($id_usuarios);
        echo json_encode($rspta);
        break;

    case 'desactivar':
        $rspta = $usuarios->desactivar($id_usuarios);
        echo $rspta ? "Usuario desactivado" : "No se pudo desactivar";
        break;

    case 'activar':
        $rspta = $usuarios->activar($id_usuarios);
        echo $rspta ? "Usuario activado" : "No se pudo activar";
        break;

    case 'verificar':
        $correo_login = $_POST['correo'];
        $password_login = $_POST['password'];

        $rspta = $usuarios->verificar($correo_login);
        $fetch = $rspta->fetch_object();

        if ($fetch && verificarClave($password_login, $fetch->password)) {
            $_SESSION['id_usuarios'] = $fetch->id_usuarios;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['apellido'] = $fetch->apellido;
            $_SESSION['correo'] = $fetch->correo;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['estado'] = $fetch->estado;

             //Obtenemos los permisos del usuario
            $marcados = $usuarios->listarmarcados($fetch->id_usuarios);

            //Declaramos el array para almacenar todos los permisos marcados
            $valores = array();

            //Almacenamos los permisos marcados en el array
            while($per = $marcados->fetch_object())
            {
                array_push($valores, $per->id_permiso);
            }

            //Determinamos los accesos del usuario
            in_array(1, $valores)?$_SESSION['administrador']=1:$_SESSION['administrador']=0;
            in_array(2, $valores)?$_SESSION['tecnico']=1:$_SESSION['tecnico']=0;
          
            // Al iniciar sesión correctamente
            $cookie_name = "usuario_login";
            $cookie_value = $fetch->id_usuarios; // <-- CORRECTO
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 30 días
            // Luego, puedes devolver el objeto JSON al frontend
            echo json_encode([
                'status' => 'ok',
                'usuario' => $fetch
            ]);

        }else {
            echo json_encode(['status' => 'error', 'msg' => 'Credenciales incorrectas']);
        }
      /*   echo json_encode($fetch); */
        break;

    case 'salir':
        session_unset();
        session_destroy();
         // Borrar cookie
        /* setcookie("usuario_login", "", time() - 3600, "/"); */
        header("Location: ../index.php");
    break;
    
    case 'total':
        $rspta = $usuarios->totalTecnicos();
        echo json_encode($rspta);
    break;
}

ob_end_flush();
?>