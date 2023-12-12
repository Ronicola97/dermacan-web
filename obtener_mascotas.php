<?php
include("conexion.php");

date_default_timezone_set("America/Guayaquil");

$dueno = $_GET['cedula'];

$arreglo = array();

$sql = "select * from mascota where cedula_usu='$dueno'";


$respuesta = $conn->query($sql);
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

$conn->close();


?>