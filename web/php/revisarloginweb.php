<?php
session_start();
include("../../db/conexion.php");
date_default_timezone_set("America/Guayaquil");

$email = $_POST['usuario'];
$pass = $_POST['clave'];

$band = false;

$sql = "select * from usuario where mail_usu='$email' and contrasenia_usu='$pass'";


$respuesta = $conn->query($sql);

while($fila = $respuesta->fetch_array()){
    $_SESSION['cedula_usu'] = $fila['cedula_usu'];
    $_SESSION['nombre']= $fila['nombre_usu'];
    $_SESSION['apellido']= $fila['apellido_usu']; 
    $_SESSION['mail']= $fila['mail_usu'];    
    
    
    $band = true;
}

if ($band) {
    header("location:../admin.php");
}
else header("location:../index.php");


?>