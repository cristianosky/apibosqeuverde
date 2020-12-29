<?php 
    require_once "clases/conexion/conexion.php";


    $conexion = new conexion;

    $query = "Select * from plantas";

    print_r($conexion->obtenerDatos($query));

?>