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

        //mascota
        $nombre_pet = $row['nombre_pet'];
        $raz_pet = $row['raz_pet'];
        

        //calculo edad
        $fechaNacimiento = $row['fnaci_pet'];
        $fechaActual = date('Y-m-d');
		$diferencia = strtotime($fechaActual) - strtotime($fechaNacimiento);
		$edadMeses = $diferencia / (30.5 * 86400);

        //ficha dermatologica
        $fecha_fcder = $row['fecha_fcder'];

        $alo_cabe = $row['alo_cabe'];
        $alo_ore = $row['alo_ore'];
        $alo_cue = $row['alo_cue'];
        $alo_lom = $row['alo_lom'];
        $alo_ext = $row['alo_ext'];
        $alo_abdo = $row['alo_abdo'];

        $pica_lev = $row['pica_lev'];
        $pica_mod = $row['pica_mod'];
        $pica_int = $row['pica_int'];

        $enrojecimiento = $row['enrojecimiento'];

        $cost_peq = $row['cost_peq'];
        $cost_med = $row['cost_med'];
        $cost_gran = $row['cost_gran'];

        $pg_lv = $row['pg_lv'];
        $pg_pron = $row['pg_pron'];
        $pg_gra = $row['pg_gra'];

        $pust_peq = $row['pust_peq'];
        $pust_gran = $row['pust_gran'];

        $mal_olor = $row['mal_olor'];
        $eritema = $row['eritema'];
        $sacu_cabe = $row['sacu_cabe'];
        $cerum_oid = $row['cerum_oid'];

        //diagnostico
        $enf_diag = $row['enf_diag'];
        $porcentaje_diag = $row['porcentaje_diag'];
        $porcentaje_diag = $porcentaje_diag*100;
        
    }

    $tablaSintomas = '';

    if($alo_cabe == 1 || $alo_ore == 1 || $alo_cue == 1 || $alo_lom == 1 || $alo_ext == 1 || $alo_abdo == 1){
        $tablaSintomas .='<h3 class="section-title">Alopecia</h3>';
    }
    $tablaSintomas .= '
    
        <table class="table">
            <thead>
            <tr>
            ';
    if($alo_cabe == 1){
        $tablaSintomas .='<th scope="col">Cabeza:</th>';
    }
    if($alo_ore == 1){
        $tablaSintomas .='<th scope="col">Orejas:</th>';
    }
    if($alo_cue == 1){
        $tablaSintomas .='<th scope="col">Cuello:</th>';
    }
    if($alo_lom == 1){
        $tablaSintomas .='<th scope="col">Lomo:</th>';
    }
    if($alo_ext == 1){
        $tablaSintomas .='<th scope="col">Extremidades:</th>';
    }
    if($alo_abdo == 1){
        $tablaSintomas .='<th scope="col">Abdomen:</th>';
    }
    $tablaSintomas .='</tr>
            </thead>
            <tbody>
            <tr>
            ';
    if($alo_cabe == 1){
        $tablaSintomas .='<td>'.$alo_cabe.'</td>';
    }
    if($alo_ore == 1){
        $tablaSintomas .='<td>'.$alo_ore.'</td>';
    }
    if($alo_cue == 1){
        $tablaSintomas .='<td>'.$alo_cue.'</td>';
    }
    if($alo_lom == 1){
        $tablaSintomas .='<td>'.$alo_lom.'</td>';
    }
    if($alo_ext == 1){
        $tablaSintomas .='<td>'.$alo_ext.'</td>';
    }
    if($alo_abdo == 1){
        $tablaSintomas .='<td>'.$alo_abdo.'</td>';
    }

    $tablaSintomas .='
            </tr>
            </tbody>';
    

    $tablaSintomas .='
    <h3 class="section-title">Picazón</h3>
    <ul class="custom-list">
        <li>';
    if($pica_lev == 1){
        $tablaSintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $tablaSintomas .='<input type="checkbox" id="checkbox2">';
    }
    
    $tablaSintomas .='<a href="#">Leve:</a>
        </li>
        <li>';
    if($pica_mod == 1){
        $tablaSintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $tablaSintomas .='<input type="checkbox" id="checkbox2">';
    }
    $tablaSintomas .='<a href="#">Moderada:</a>
        </li>
        <li>';
    if($pica_int == 1){
        $tablaSintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $tablaSintomas .='<input type="checkbox" id="checkbox2">';
    }
    $tablaSintomas .='<a href="#">Intensa:</a>
        </li>
    </ul>';

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
            padding: 10px;
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 10px;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .section-title {
            font-size: 1.5em;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #ffffff;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .custom-list {
            list-style-type: none;
            padding-left: 0;
            margin-top: 20px;
          }
          
          .custom-list li {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            margin-bottom: 5px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background-color: #f8f9fa;
          }
          
          .custom-list li:hover {
            background-color: #e9ecef;
            border-color: #dae0e5;
          }
          
          .custom-list li:last-child {
            margin-bottom: 0;
          }
          
          .custom-list li input[type="checkbox"] {
            margin-right: 10px;
          }
          
          .custom-list li a {
            text-decoration: none;
            color: #007bff;
          }
          
          .custom-list li a:hover {
            text-decoration: underline;
          }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Ficha Dermatológica - Diagnóstico</h1>
    </div>

    <div class="section">
        <h2 class="section-title">Datos de la Mascota</h2>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Nombre:</th>
                <th scope="col">Edad:</th>
                <th scope="col">Raza:</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>'.$nombre_pet.'</td>
                <td>'.$edadMeses.'</td>
                <td>'.$raz_pet.'</td>

            </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Síntomas Presentes</h2>
        '.$tablaSintomas.'
    </div>

    <div class="section">
        <h2 class="section-title">Diagnóstico</h2>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Enfermedad:</th>
                <th scope="col">Probabilidad:</th>
                <th scope="col">Fecha análisis:</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>'.$enf_diag.'</td>
                <td>'.$porcentaje_diag.'</td>
                <td>'.$fecha_fcder.'</td>

            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>';

    $objectName = $id_pet . '_' . $id_fcder . '_' . $id_diag . '_DOMPDF.pdf';

    $pdf->load_html($html);
    $pdf->set_paper("a4", "portrait");
    $pdf->render();
    $pdfData = $pdf->output();

    $storage = new StorageClient([
        'keyFilePath' => 'adept-portal-397013-1d8a23b30297.json', // Ruta a tu archivo de credenciales
        'projectId' => 'adept-portal-397013',
    ]);
    $bucketName = 'dermacan-storage';

    //nombre del pdf
    

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