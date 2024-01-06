<?php
include("../db/conexion.php");
require('fpdf/fpdf.php');

date_default_timezone_set("America/Guayaquil");


$id_pet = $_POST['id_pet'];
$id_fcder = $_POST['id_fcder'];
$id_diag = $_POST['id_diag'];


$sql = "select * 
from ficha_dermatologica fd, diagnostico d, mascota m 
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

    $pdf->Cell(0, 10, 'Historial Médico', 0, 1, 'C');

    while ($row = $result->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Nombre Mascota: ' . $row['nombre_pet'], 0, 1);
        $pdf->Cell(0, 10, 'Fecha: ' . $row['fecha_fcder'], 0, 1);
        $pdf->Cell(0, 10, 'Diagnóstico: ' . $row['enf_diag'], 0, 1);
    }

    $pdf->Output();

    
}


$conn->close();
?>