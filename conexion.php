<?php
    $servername = "localhost";
    $username ="id20131810_aglyg";
    $password ="=ASgDn6{%1U/r^D2";
    $bd ="id20131810_cowapp";

    //crear conexion BD
    $conn = new mysqli($servername, $username, $password, $bd);

    //Comprobar conexion
    if ($conn->connect_error){
        die("Conexion Fallida".$conn->connect_error);
    }
?>