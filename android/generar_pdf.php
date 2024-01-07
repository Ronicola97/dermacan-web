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

    // Convertir los datos a un formato PDF
    $pdf = new DOMPDF();
    $html = '
    <html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Dermatológica - Síntomas</title>
    <!-- Incluir Bootstrap CSS para mejorar el diseño -->
    
    <link rel="stylesheet" href="http://'.$_ENV["HOST_URL"].'/assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 1.5em;
            
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        .symptom {
            margin-top: 15px;
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

        <div class="row">
            <div class="col-md-4 section-title">
                <strong>Alopecia</strong>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Cabeza:</th>
                    <th scope="col">Orejas:</th>
                    <th scope="col">Cuello:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>'.$alo_cabe.'</td>
                    <td>'.$alo_ore.'</td>
                    <td>'.$alo_cue.'</td>

                </tr>
                </tbody>
            </table>

        </div>

    </div>

    <div class="section">
        <h2 class="section-title">Diagnóstico </h2>
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