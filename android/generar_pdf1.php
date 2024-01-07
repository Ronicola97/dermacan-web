<?php
include("../db/conexion.php");

use Dompdf\Dompdf;

require 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;

date_default_timezone_set("America/Guayaquil");


$id_pet = $_POST['id_pet'];
$id_fcder = $_POST['id_fcder'];
$id_diag = $_POST['id_diag'];


$sql = "select * 
from ficha_dermatologica fd, diagnostico d, mascota m, usuario u
where (
fd.id_fcder = '$id_fcder' and
m.id_pet = fd.id_mas and fd.id_fcder = d.id_fcder and u.cedula_usu = m.cedula_usu
);";
$result = $conn->query($sql);

if ($result->num_rows > 0){

    while ($row = $result->fetch_assoc()) {
        $nombre_pet = $row['nombre_pet'];
        $fecha_fcder = $row['fecha_fcder'];
        $enf_diag = $row['enf_diag'];
        
    }

    // Convertir los datos a un formato PDF
    $pdf = new DOMPDF();
    $html = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Ficha Dermatológica - Síntomas</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: left;
                padding: 20px;
            }
            .header {
                text-align: center;
                border-bottom: 2px solid black;
                padding-bottom: 10px;
            }
            h1 {
                margin-top: 20px;
                margin-bottom: 30px;
            }
            .section {
                margin-bottom: 30px;
            }
            .section-title {
                font-size: 1.2em;
                margin-bottom: 10px;
                border-bottom: 1px solid #ccc;
                padding-bottom: 5px;
            }
            .symptom {
                margin-top: 15px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Ficha Dermatológica - Síntomas</h1>
        </div>
        
        <div class="section">
            <h2 class="section-title">Datos Generales del Paciente</h2>
            <p><strong>Nombre del Paciente:</strong> Nombre de tu mascota</p>
            <p><strong>Edad:</strong> Edad de tu mascota</p>
            <p><strong>Especie:</strong> Especie de tu mascota</p>
        </div>
        
        <div class="section">
            <h2 class="section-title">Síntomas Presentes</h2>
            
            <div class="symptom">
                <strong>1. Tipo de Síntoma:</strong> Descripción o nombre del síntoma
                <p>Detalles adicionales sobre el síntoma, como localización, duración, etc.</p>
            </div>
            
            <div class="symptom">
                <strong>2. Tipo de Síntoma:</strong> Descripción o nombre del síntoma
                <p>Detalles adicionales sobre el síntoma, como localización, duración, etc.</p>
            </div>
            
            <!-- Puedes agregar más síntomas según sea necesario -->
        </div>
        
        <div class="section">
            <h2 class="section-title">Observaciones Adicionales</h2>
            <p>Notas o comentarios adicionales sobre los síntomas observados.</p>
        </div>
        
    </body>
    </html>';

    // Guardar el archivo PDF
    $pdfData = $pdf->loadHtml($html);

    $storage = new StorageClient([
        'keyFilePath' => 'adept-portal-397013-1d8a23b30297.json', // Ruta a tu archivo de credenciales
        'projectId' => 'adept-portal-397013',
    ]);
    $bucketName = 'dermacan-storage';

    //nombre del pdf
    $objectName = $id_pet . '_' . $id_fcder . '_' . $id_diag . '_DOMPDF.pdf';

    $bucket = $storage->bucket($bucketName);

    // Verificar si el objeto ya existe en el bucket
    if ($bucket->object($objectName)->exists()) {
        // El archivo ya existe, devuelve su URL existente o realiza cualquier otra acción que necesites
        $object = $bucket->object($objectName);
    
        // Obtener el URL del objeto existente
        $pdfUrl = $object->signedUrl(new \DateTime('tomorrow')); // Genera una URL firmada válida hasta mañana
        echo $pdfUrl;
    } else {
        // El archivo no existe, procede a crear y subir el nuevo archivo PDF
        // ... (tu código existente para crear el PDF)
        $object = $bucket->upload(
            $pdfData,
            [
                'name' => $objectName,
                'predefinedAcl' => 'publicRead'
            ]
        );

        $pdfUrl = $object->info()['mediaLink'];
        echo $pdfUrl;
    }


}else{
    echo "error";
}


$conn->close();
?>