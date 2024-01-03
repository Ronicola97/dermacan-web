<?php
include("../../db/conexion.php"); // No need for session_start() here
date_default_timezone_set("America/Guayaquil");

$email = $_POST['usuario'] ?? ''; // Handle missing input
$pass = $_POST['clave'] ?? '';  // Handle missing input

// Prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM usuario WHERE mail_usu=? AND contrasenia_usu=?");
if (!$stmt) {
  die("Error preparing statement: " . $conn->error); // Handle statement error
}
$stmt->bind_param("ss", $email, $pass);
$stmt->execute();
$result = $stmt->get_result();

// Check for successful login
if ($result->num_rows > 0) {
  $fila = $result->fetch_assoc();
  $_SESSION['cedula_usu'] = $fila['cedula_usu'];
  $_SESSION['nombre'] = $fila['nombre_usu'];
  $_SESSION['apellido'] = $fila['apellido_usu'];
  $_SESSION['mail'] = $fila['mail_usu'];
  $_SESSION['estado'] = $fila['estado_usu'];
  header("location:../admin.php");
} else {
  header("location:../index.php");
}

$stmt->close(); // Close statement
?>
