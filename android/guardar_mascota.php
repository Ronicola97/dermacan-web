<?php
include("../db/conexion.php");

date_default_timezone_set("America/Guayaquil");

$n_perro = $_POST['name_mascota'];
$n_prop = $_POST['cedula_dueno'];
$direc = $_POST['dir_mascota'];
$tipo_raza = $_POST['tipo_raza'];
$date = $_POST['nacimiento'];
$estado = 1;

// Obtener la imagen codificada en Base64 desde el POST
$imagen_base64 = $_POST['imagen_mascota'];

// Decodificar la imagen Base64
$imagen_binaria = base64_decode($imagen_base64);

// Verifica si el directorio no existe y créalo si es necesario
if (!is_dir('../imagenes')) {
    mkdir('../imagenes', 0777, true); // Esto crea el directorio con permisos de lectura, escritura y ejecución para todos
}

// Guardar la imagen en una ruta específica en tu servidor (por ejemplo, en una carpeta llamada 'imagenes')
$ruta_imagen = '../imagenes/' . uniqid() . '.jpg';  // Aquí estás generando un nombre único para la imagen
$resultado_guardado = file_put_contents($ruta_imagen, $imagen_binaria);


if ($resultado_guardado !== false) {
    // Insertar los datos de la mascota en la base de datos
    $sentencia = "INSERT INTO mascota(nombre_pet, direccion_pet, fnaci_pet, raz_pet, cedula_usu, estado_pet, ruta_imagen) 
                    VALUES ('$n_perro', '$direc', '$date', '$tipo_raza', '$n_prop', '$estado', '$ruta_imagen')";
    $respuesta = $conn->query($sentencia);

    if ($respuesta === true) {
        echo 'grabado';
    } else {
        echo 'Se ha producido un error al insertar en la base de datos';
    }
} else {
    echo 'Se ha producido un error al guardar la imagen';
}
$conn->close();

?>