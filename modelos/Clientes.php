<?php
require "../config/Conexion.php";
if(strlen(session_id()) < 1)
    session_start();

class Clientes
{
    public function __construct() {}

    // Insertar cliente
    public function insertar($cedula, $nombre, $apellido, $telefono, $correo ,$estado) {
        $sql = "INSERT INTO cliente (cedula, nombre, apellido, telefono, correo, estado)
                VALUES ('$cedula','$nombre','$apellido','$telefono','$correo','1')";
        return ejecutarConsulta($sql);
    }

    // Editar usuario
    public function editar($id_cliente, $cedula, $nombre, $apellido, $telefono, $correo,$estado) {
        $sql = "UPDATE cliente SET
                cedula='$cedula',
                nombre='$nombre',
                apellido='$apellido',
                telefono='$telefono',
                correo='$correo',
                estado='$estado'
                WHERE id_cliente='$id_cliente'";
        return ejecutarConsulta($sql);
    }

    // Activar usuario
    public function activar($id_cliente){
        $sql = "UPDATE cliente SET estado='1' WHERE id_cliente='$id_cliente'";
        return ejecutarConsulta($sql);
    }

    // Desactivar usuario
    public function desactivar($id_cliente){
        $sql = "UPDATE cliente SET estado='0' WHERE id_cliente='$id_cliente'";
        return ejecutarConsulta($sql);
    }

    // Mostrar un usuario
    public function mostrar($id_cliente){
        $sql = "SELECT * FROM cliente WHERE id_cliente='$id_cliente'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar todos los usuarios
    public function listar(){
        $sql = "SELECT * FROM cliente";
        return ejecutarConsulta($sql);
    }
    
/*     public function buscar($texto){
        $sql="SELECT id_cliente, nombre, apellido
              FROM cliente 
              WHERE nombre LIKE '%$texto%' AND estado=1 
              LIMIT 10";
        return ejecutarConsulta($sql);
    } */
}
?>