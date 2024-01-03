<?php
session_start();
date_default_timezone_set("America/Guayaquil");
// Destruir la sesión
session_destroy();

// Redirigir a otra página después de cerrar la sesión
header("Location: ../../index.php");
exit();
?>