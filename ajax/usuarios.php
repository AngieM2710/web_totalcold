<?php
ob_start();
if (strlen(session_id()) < 1) {
    session_start();
}

require_once "../modelos/Usuarios.php";
$usuarios = new Usuario();

// Funciones auxiliares para hashing
function generarHash($clave) {
    return password_hash($clave, PASSWORD_BCRYPT);
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

    case 'guardaryeditar':
        // Manejo de imagen
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] == 4) {
            $imagen = $_POST["imagen_actual"] ?? "default-user.png";
        } else {
            $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
            $permitidos = ["jpg", "jpeg", "png"];
            if (in_array(strtolower($ext), $permitidos)) {
                $imagen = round(microtime(true)) . '.' . $ext;
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);

                // Eliminar la imagen anterior si no es la predeterminada
                if (!empty($_POST["imagen_actual"]) && $_POST["imagen_actual"] !== "default-user.png" && file_exists("../files/usuarios/" . $_POST["imagen_actual"])) {
                    unlink("../files/usuarios/" . $_POST["imagen_actual"]);
                }
            } else {
                $imagen = $_POST["imagen_actual"] ?? "default-user.png";
            }
        }

        // Generar hash solo si se envió nueva contraseña
        $password_hash = !empty($password) ? generarHash($password) : null;

        if (empty($id_usuarios)) {
            // Registro de usuario
            $rspta = $usuarios->insertar($nombre, $apellido, $correo, $password_hash, $telefono, $imagen, $estado);
            echo $rspta ? "Usuario registrado" : "No se pudo registrar el usuario";
        } else {
            // Actualización
            $rspta = $usuarios->editar(
                $id_usuarios,
                $nombre,
                $apellido,
                $correo,
                $password_hash ?? $password, // si no hay nueva contraseña, mantiene la anterior
                $telefono,
                $imagen,
                $estado
            );
            echo $rspta ? "Usuario actualizado" : "No se pudo actualizar el usuario";
        }
        break;

    case 'mostrar':
        $rspta = $usuarios->mostrar($id_usuarios);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $usuarios->listar();
        $data = [];
        while ($reg = $rspta->fetch_object()) {
            $data[] = [
                "0" => ($reg->estado) ?
                    '<a href="#" onclick="mostrar(' . $reg->id_usuarios . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="desactivar(' . $reg->id_usuarios . ')" class="btn btn-danger btn-circle"><i class="fas fa-times"></i></a>' :
                    '<a href="#" onclick="mostrar(' . $reg->id_usuarios . ')" class="btn btn-info btn-circle"><i class="fas fa-eye"></i></a>' .
                    ' <a href="#" onclick="activar(' . $reg->id_usuarios . ')" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a>',
                "1" => $reg->nombre,
                "2" => $reg->apellido,
                "3" => $reg->correo,
                "4" => $reg->telefono,
                "5" => "<img src='../files/usuarios/" . $reg->imagen . "' style='height:40px;width:40px;border-radius:50%;' alt='Imagen de usuario'>",
                "6" => ($reg->estado) ? '<span class="label bg-green">Activo</span>' : '<span class="label bg-red">Inactivo</span>'
            ];
        }

        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($results);
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
        }
        echo json_encode($fetch);
        break;

    case 'salir':
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        break;
}

ob_end_flush();
?>
