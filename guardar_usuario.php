<?php
include("conexion.php");
$db = DataBase::connect();
date_default_timezone_set("America/Guayaquil");


$dni = $_POST['cedula'];
$nom = $_POST['nombre'];
$ape = $_POST['apellidos'];
$email = $_POST['email'];
$pass = $_POST['contrasenia'];
$cel = $_POST['celular'];
$estado = 1;

$resultado = mysqli_query($link, "select * FROM usuarios WHERE usu_cedula = '$dni'");
$numerodefilas = mysqli_num_rows($resultado);

if ($numerodefilas > 0) {
	    $existe = "existe";
	    echo $existe;
	}else{
        $query = "INSERT into usuario (usu_cedula, usu_nombre, usu_apellidos, usu_mail, usu_contrasenia, usu_celular, usu_estado) VALUES ('$dni','$nom','$ape','$email','$pass','$cel','$estado')";
        $result = mysqli_query($link, $query);
        if ($result){
            echo "Datos agregados a la base.";
        }else {
            echo "error No guardo";
        }
    
	}
$link->close();
?>