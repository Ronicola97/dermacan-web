<?php
include("conexion.php");

date_default_timezone_set("America/Guayaquil");

$n_perro = $_POST['name_mascota'];
$n_prop = '09'
$direc = $_POST['dir_mascota'];
$tipo_raza = $_POST['tipo_raza'];
$date = $_POST['nacimiento'];
$estado = 1;

if ($numerodefilas > 0) {
    $existe = "existe";
    echo $existe;
}else{
$sentencia = "INSERT INTO mascota(nombre_pet, direccion_pet, fnaci_pet, 
            pet, estado_pet, raza_id, cedula_usu) 
		VALUES ('$n_perro','$direc', '$date','1', '$tipo_raza', '$n_prop')";
$respuesta = $conn->query($sentencia);
}

if($respuesta==true){
	echo 'grabado';
}else{
	echo 'Se ha producido un error';
}


?>