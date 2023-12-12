<?php
include("conexion.php");

date_default_timezone_set("America/Guayaquil");

$n_perro = $_POST['name_mascota'];
$n_prop = $_POST['cedula_dueno'];
$direc = $_POST['dir_mascota'];
$tipo_raza = $_POST['tipo_raza'];
$date = $_POST['nacimiento'];
$estado = 1;


$sentencia = "INSERT INTO mascota(nombre_pet, direccion_pet, fnaci_pet, raz_pet, cedula_usu,estado_pet) 
		VALUES ('$n_perro','$direc', '$date', '$tipo_raza', '$n_prop','$estado')";
$respuesta = $conn->query($sentencia);


if($respuesta==true){
	echo 'grabado';
}else{
	echo 'Se ha producido un error';
}


?>