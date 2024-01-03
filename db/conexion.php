<?php
session_start();

$DB_HOST = $_ENV["DB_HOST"];
$DB_USER = $_ENV["DB_USER"];
$DB_PASSWORD = $_ENV["DB_PASSWORD"];
$DB_NAME = $_ENV["DB_NAME"];
$DB_PORT = $_ENV["DB_PORT"];

    //crear conexion BD
    $conn = new mysqli("$DB_HOST","$DB_USER","$DB_PASSWORD","$DB_NAME","$DB_PORT");

    //Comprobar conexion
    if ($conn->connect_error){
        die("Conexion Fallida".$conn->connect_error);
    }
?>