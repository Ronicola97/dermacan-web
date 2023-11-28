<?php
include("conexion.php");
$db = DataBase::connect();
date_default_timezone_set("America/Guayaquil");

$email = $_GET['email'];
$pass = $_GET['contrasenia'];
$arreglo = array();

$sql = "select * from usuarios where usu_mail='".$email."' and usu_contrasenia='".$pass."';


$respuesta = $db->query($sql);
$no_filas = $respuesta->num_rows;
if($no_filas>0){
	while($filas = $respuesta->fetch_assoc()){
		array_push($arreglo, $filas);
	}
	echo json_encode($arreglo,JSON_UNESCAPED_UNICODE);
}
else{
	echo json_encode($arreglo,JSON_UNESCAPED_UNICODE);	
}

?>