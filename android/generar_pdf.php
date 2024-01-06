<?php
include("../db/conexion.php");
require('fpdf/fpdf.php');
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

    // Crear instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'Historial Medico', 0, 1, 'C');

    while ($row = $result->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Nombre Mascota: ' . $row['nombre_pet'], 0, 1);
        $pdf->Cell(0, 10, 'Fecha: ' . $row['fecha_fcder'], 0, 1);
        $pdf->Cell(0, 10, 'Diagnóstico: ' . $row['enf_diag'], 0, 1);
    }

    $pdfData = $pdf->Output("", "S");

    $storage = new StorageClient([
        'keyFilePath' => 'adept-portal-397013-1d8a23b30297.json', // Ruta a tu archivo de credenciales
        'projectId' => 'adept-portal-397013',
    ]);
    $bucketName = 'dermacan-storage';

    $objectName = uniqid() . '.pdf';

    $bucket = $storage->bucket($bucketName);
    $object = $bucket->upload(
        $pdfData,
        [
            'name' => $objectName, 
            'predefinedAcl' => 'publicRead'
        ]
    );

    $pdfUrl = $object->info()['mediaLink'];

    echo $pdfUrl;

    
}else{
    echo "error";
}


$conn->close();
?>