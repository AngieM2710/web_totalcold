<?php
require "../config/Conexion.php";

Class Permiso {
    public function __construct()
    {
        
    }

    //insertar aula
    public function insertar($nombre)
    {
        $sql= "INSERT INTO permisos (nombre) 
        VALUES ('$nombre')" ;
        return ejecutarConsulta($sql);
    }

    //editar tipo de usuario
    public function editar($id_permiso,$nombre)
    {
        $sql="UPDATE permisos SET nombre='$nombre' where id_permiso='$id_permiso'";
        return ejecutarConsulta($sql);
    }

    //muestra los datos de un registro a modificar
    public function mostrar($id_permiso)
    {
        $sql="SELECT * FROM permisos WHERE id_permiso ='$id_permiso'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar(){
        $sql="SELECT * FROM permisos where id_permiso != 5";
        return ejecutarConsulta($sql);
    }

    public function select(){
        $sql="SELECT * FROM permisos";
        return ejecutarConsulta($sql);
    }
}


?>