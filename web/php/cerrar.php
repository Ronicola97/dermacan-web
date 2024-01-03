<?php
include("../../db/conexion.php");
// Destruir la sesión
session_destroy();

// Redirigir a otra página después de cerrar la sesión
header("Location: ../../index.php");
exit();
?>