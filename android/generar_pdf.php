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

        $alo_cabe = $row['alo_cabeza'];
        $alo_ore = $row['alo_orejas'];
        $alo_cue = $row['alo_cuello'];
        $alo_lom = $row['alo_lomo'];
        $alo_ext = $row['alo_extremidades'];
        $alo_abdo = $row['alo_abdomen'];

        $pica_lev = $row['pica_leve'];
        $pica_mod = $row['pica_mode'];
        $pica_int = $row['pica_inte'];

        $enrojecimiento = $row['enrojecimiento'];

        $cost_peq = $row['costra_peque'];
        $cost_med = $row['costra_media'];
        $cost_gran = $row['costra_grand'];

        $pg_lv = $row['piel_gru_leve'];
        $pg_pron = $row['piel_gru_pron'];
        $pg_gra = $row['piel_gru_grav'];

        $pust_peq = $row['pustula_peque'];
        $pust_gran = $row['pustula_grand'];

        $mal_olor = $row['mal_olor'];
        $eritema = $row['eritema'];
        $sacu_cabe = $row['sacudida_cabeza'];
        $cerum_oid = $row['cerumen_oido'];

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
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                border-bottom: 1px solid #ccc;
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
            <div class="row">
                <div class="col-md-4">
                    <strong>Nombre:</strong> $nombre_pet
                </div>
                <div class="col-md-4">
                    <strong>Edad:</strong> $edadMeses
                </div>
                <div class="col-md-4">
                    <strong>Raza:</strong> $raz_pet
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Síntomas Presentes</h2>

            <div class="row">
                <div class="col-md-4 section-title">
                    <strong>Alopecia</strong>
                </div>

            </div>

            <div class="row">
                <div class="col-md-4">
                    <strong>Cabeza:</strong> $alo_cabe
                </div>
                <div class="col-md-4">
                    <strong>Orejas:</strong> $alo_ore
                </div>

                <div class="col-md-4">
                    <strong>Cuello:</strong> $alo_cue
                </div>

            </div>

            <!-- Puedes agregar más síntomas según sea necesario -->
        </div>

        <div class="section">
            <h2 class="section-title">Diagnóstico </h2>
            <div class="row">
                <div class="col-md-4">
                    <strong>Enfermedad:</strong> $enf_diag
                </div>
                <div class="col-md-4">
                    <strong>Probabilidad:</strong> $porcentaje_diag
                </div>

                <div class="col-md-4">
                    <strong>Fecha análisis:</strong> $fecha_fcder
                </div>

            </div>
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