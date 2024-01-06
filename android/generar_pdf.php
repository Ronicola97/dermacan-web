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

    $objectName = $id_pet . '_' . $id_fcder . '_' . $id_diag . '.pdf';

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