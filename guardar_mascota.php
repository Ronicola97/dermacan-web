<?php
include("../bd/conexion.php");
$db = DataBase::connect();
date_default_timezone_set("America/Guayaquil");

$n_perro = $_POST['nombre_perro'];
$n_prop = $_POST['nombre_prop'];
$direc = $_POST['direccion'];
$tipo_raza = $_POST['raza'];
$date = $_POST['fecha'];

if ($numerodefilas > 0) {
    $existe = "existe";
    echo $existe;
}else{
$sentencia = "INSERT INTO `mascota`(`pet_name`, `pet_dir`, `pet_nacimiento`, 
            `pet_estado`, `raza_id`, `usu_cedula`) 
		VALUES ('$n_perro','$direc', '$date','1', '$tipo_raza', '$n_prop')";
$respuesta = $db->query($sentencia);
}

if($respuesta==true){
	echo 'Se ha registrado con éxito';
}else{
	echo 'Se ha producido un error';
}


?>