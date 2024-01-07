<?php
include("../db/conexion.php");

date_default_timezone_set("America/Guayaquil");

$dueno = $_GET['cedula'];

$arreglo = array();

$sql = "select * from mascota where cedula_usu='$dueno'";

$respuesta = $conn->query($sql);
$no_filas = $respuesta->num_rows;
if($no_filas>0){
	while ($filas = $respuesta->fetch_assoc()) {
        // Asumiendo que la columna 'url_imagen' almacena la URL de la imagen en GCS
        $imagen_url = $filas['ruta_imagen'];

		//calculo edad
        $fechaNacimiento = $row['fnaci_pet'];
        $fechaActual = date('Y-m-d');

        // Convertir las fechas a objetos DateTime para un cálculo más preciso
        $fechaNacimiento = new DateTime($fechaNacimiento);
        $fechaActual = new DateTime($fechaActual);

        // Calcular la diferencia entre las fechas
        $diferencia = $fechaNacimiento->diff($fechaActual);

        // Obtener la edad en años y meses restantes
        $edadAnios = $diferencia->y; // años
        $edadMesesRestantes = $diferencia->m;

        $edad = $edadAnios."años ".$edadMesesRestantes." meses";


        // Si deseas agregar más campos o modificar la estructura de los datos, puedes hacerlo aquí
        $mascota_data = array(
            'id_pet' => $filas['id_pet'],
            'nombre_pet' => $filas['nombre_pet'],
            'direccion_pet' => $filas['direccion_pet'],
            'fnaci_pet' => $edad,
            'raz_pet' => $filas['raz_pet'],
            'cedula_usu' => $filas['cedula_usu'],
            'estado_pet' => $filas['estado_pet'],
            'ruta_imagen' => $imagen_url // Agregamos la URL de la imagen desde GCS
        );
        
        array_push($arreglo, $mascota_data);
    }
	echo json_encode($arreglo,JSON_UNESCAPED_UNICODE);
}
else{
	echo json_encode($arreglo,JSON_UNESCAPED_UNICODE);	
}

$conn->close();


?>