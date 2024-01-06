<?php
include("../db/conexion.php");

date_default_timezone_set("America/Guayaquil");

$dueno = $_GET['cedula'];

$arreglo = array();

$sql = "select m.id_pet, m.nombre_pet, m.ruta_imagen, fd.id_fcder,fd.fecha_fcder, d.enf_diag, d.id_diag  
from mascota m, ficha_dermatologica fd, diagnostico d 
where (m.cedula_usu='$dueno' and m.id_pet = fd.id_mas and fd.id_fcder = d.id_fcder) 
order by fd.fecha_fcder desc limit 20 
";

$respuesta = $conn->query($sql);
$no_filas = $respuesta->num_rows;
if($no_filas>0){
	while ($filas = $respuesta->fetch_assoc()) {
        
        $historial_data = array(
            'id_pet' => $filas['id_pet'],
            'nombre_pet' => $filas['nombre_pet'],
            'ruta_imagen' => $filas['ruta_imagen'],
            'id_fcder' => $filas['id_fcder'],
            'fecha_fcder' => $filas['fecha_fcder'],
            'diagnostico' => $filas['enf_diag'],
            'id_diag' => $filas['id_diag'],
        );
        
        array_push($arreglo, $historial_data);
    }
	echo json_encode($arreglo,JSON_UNESCAPED_UNICODE);
}
else{
	echo json_encode($arreglo,JSON_UNESCAPED_UNICODE);	
}

$conn->close();


?>