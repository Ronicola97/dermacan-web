<?php
include("../bd/conexion.php");
$db = DataBase::connect();
date_default_timezone_set("America/Guayaquil");


$nom = $_POST['nombre'];
$ape = $_POST['apellido'];
$cel = $_POST['celular'];
$email = $_POST['email'];
$pass = $_POST['contrasenia'];



$sentencia = "INSERT INTO `cliente`(`nombre`, `apellido`, `celular`, 
            `email`, `contrasenia`) 
		VALUES ('$nom','$ape','$cel',
            '$email', '$pass')";
$respuesta = $db->query($sentencia);

if($respuesta==true){
	echo 'Se ha registrado con éxito';
}else{
	echo 'Se ha producido un error';
}


?>