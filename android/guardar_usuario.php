<?php
include("../db/conexion.php");

date_default_timezone_set("America/Guayaquil");


$dni = $_POST['cedula'];
$nom = $_POST['nombre'];
$ape = $_POST['apellidos'];
$email = $_POST['email'];
$pass = $_POST['contrasenia'];
$cel = $_POST['celular'];
$estado = 1;

$resultado = mysqli_query($conn, "select * FROM usuario WHERE (cedula_usu = '$dni' OR mail_usu = '$email')");
$numerodefilas = mysqli_num_rows($resultado);

if ($numerodefilas > 0) {
	    $existe = "existe";
	    echo $existe;
	}else{
        $query = "INSERT into usuario (cedula_usu, nombre_usu, apellido_usu, mail_usu, contrasenia_usu, celular_usu, estado_usu) VALUES ('$dni','$nom','$ape','$email','$pass','$cel','$estado')";
        $result = mysqli_query($conn, $query);
        if ($result){
            echo "grabado";
        }else {
            echo "error No guardo";
        }
    
	}
$conn->close();
?>