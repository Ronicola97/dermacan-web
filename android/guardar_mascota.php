<?php
include("../db/conexion.php");

date_default_timezone_set("America/Guayaquil");

$n_perro = $_POST['name_mascota'];
$n_prop = $_POST['cedula_dueno'];
$direc = $_POST['dir_mascota'];
$tipo_raza = $_POST['tipo_raza'];
$date = $_POST['nacimiento'];
$estado = 1;

// Decodificar la imagen en Base64
$imagen_base64 = $_POST['imagen_mascota'];
$imagen_decodificada = base64_decode($imagen_base64);

// Configurar la conexión con Google Cloud Storage
require 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;

$storage = new StorageClient([
    'keyFilePath' => 'adept-portal-397013-1d8a23b30297.json', // Ruta a tu archivo de credenciales
    'projectId' => 'adept-portal-397013',
]);
$bucketName = 'dermacan-storage';

// Subir imagen a GCS
$temp_file = tempnam(sys_get_temp_dir(), 'gcs_temp_file');
file_put_contents($temp_file, $imagen_decodificada);

$bucket = $storage->bucket($bucketName);
$object = $bucket->upload(
    fopen($temp_file, 'r'),
    [
        'name' => uniqid().'.jpg', // Nombre único para el archivo
		'predefinedAcl' => 'publicRead'
    ]
);
$imageUrl = $object->info()['mediaLink']; // Obtener la URL de la imagen en GCS

// Insertar datos en la base de datos
$sentencia = "INSERT INTO mascota(nombre_pet, direccion_pet, fnaci_pet, raz_pet, cedula_usu, estado_pet, ruta_imagen) 
        VALUES ('$n_perro','$direc', '$date', '$tipo_raza', '$n_prop', '$estado', '$imageUrl')";
$respuesta = $conn->query($sentencia);

if ($respuesta == true) {
    echo 'grabado';
} else {
    echo 'Se ha producido un error';
}

// Eliminar el archivo temporal
unlink($temp_file);

$conn->close();
?>