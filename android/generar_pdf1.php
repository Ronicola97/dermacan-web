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
    $html = "<table><thead><tr><th>Nombre Mascota</th><th>Fecha Diagnostico</th><th>Diagnostico</th></tr></thead><tbody>";
    foreach ($resultados as $registro) {
        $html .= "<tr><td>$nombre_pet</td><td>$fecha_fcder</td><td>$enf_diag</td></tr>";
    }
    $html .= "</tbody></table>";
    $pdf->loadHtml($html);

    // Guardar el archivo PDF
    $pdf->render();
    $pdf->stream("reporte.pdf");


}else{
    echo "error";
}


$conn->close();
?>    