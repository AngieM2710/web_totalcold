<?php
require "../config/Conexion.php";
if(strlen(session_id()) < 1)
    session_start();

class Equipos
{
    public function __construct() {}

    // Insertar equipo
    public function insertar($codigo_interno, $marca, $modelo, $capacidad) {
        $sql = "INSERT INTO equipos (codigo_interno, marca, modelo, capacidad, estado_equipo)
                VALUES ('$codigo_interno','$marca','$modelo','$capacidad','1')";
        return ejecutarConsulta($sql);
    }

    // Editar usuario
    public function editar($id_equipo, $codigo_interno, $marca, $modelo, $capacidad) {
        $sql = "UPDATE equipos SET
                codigo_interno='$codigo_interno',
                marca='$marca',
                modelo='$modelo',
                capacidad='$capacidad'
                WHERE id_equipo='$id_equipo'";
        return ejecutarConsulta($sql);
    }

    // Activar usuario
    public function activar($id_equipo){
        $sql = "UPDATE equipos SET estado_equipo='1' WHERE id_equipo='$id_equipo'";
        return ejecutarConsulta($sql);
    }

    // Desactivar usuario
    public function desactivar($id_equipo){
        $sql = "UPDATE equipos SET estado_equipo='0' WHERE id_equipo='$id_equipo'";
        return ejecutarConsulta($sql);
    }

    // Mostrar un usuario
    public function mostrar($id_equipo){
        $sql = "SELECT * FROM equipos WHERE id_equipo='$id_equipo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar todos los usuarios
    public function listar(){
        $sql = "SELECT * FROM equipos";
        return ejecutarConsulta($sql);
    }
}
?>
