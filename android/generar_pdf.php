<?php
include("../db/conexion.php");

date_default_timezone_set("America/Guayaquil");


$id_pet = $_POST['id_pet'];
$id_fcder = $_POST['id_fcder'];
$id_diag = $_POST['id_diag'];

echo $id_pet.$id_fcder.$id_diag;


$conn->close();
?>