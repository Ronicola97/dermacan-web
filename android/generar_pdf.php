<?php
include("../db/conexion.php");
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

use Google\Cloud\Storage\StorageClient;

$options = new Options();
$options->set('isRemoteEnabled', true);

$pdf = new Dompdf($options);

date_default_timezone_set("America/Guayaquil");


$id_pet = $_POST['id_pet'];
$id_fcder = $_POST['id_fcder'];



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

        $id_diag = $row['id_diag'];

        $ruta_imagen = $row['ruta_imagen'];

        //calculo edad
        $fechaNacimiento = $row['fnaci_pet'];
        $fechaActual = date('Y-m-d');

        // Convertir las fechas a objetos DateTime para un cálculo más preciso
        $fechaNacimiento = new DateTime($fechaNacimiento);
        $fechaActual = new DateTime($fechaActual);

        // Calcular la diferencia entre las fechas
        $diferencia = $fechaNacimiento->diff($fechaActual);

        // Obtener la edad en años y meses restantes
        $edadAnios = $diferencia->y; // años
        $edadMesesRestantes = $diferencia->m;

        $edad = $edadAnios." años ".$edadMesesRestantes." meses";

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

    $seccion_sintomas = '';

    $seccion_sintomas .= '<div class="section sintomas-presentes">
    <h2 class="section-title">Síntomas Presentes</h2>
    <table class="table">
        <thead>
            <th colspan="2">Alopecia</th>
            <th> Pústulas</th>
        </thead>
        <tbody>
        <tr>
            <td>
                <ul class="custom-list">
                    <li>';
                    
    if($alo_cabe == 1){
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .= '<a href="#">Cabeza</a>
                    </li>
                    <li>';
    if($alo_ore == 1){
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .= '<a href="#">Orejas</a>
                    </li>
                    <li>';
    if($alo_cue == 1){
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .= '<a href="#">Cuello</a>
                    </li>
                </ul>
            </td>
            <td>
                <ul class="custom-list">
                    <li>';
    if($alo_lom == 1){
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .= '<a href="#">Lomo</a>
                    </li>
                    <li>';
    if($alo_ext == 1){
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .= '<a href="#">Extremiadades</a>
                    </li>
                    <li>';
    if($alo_abdo == 1){
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .= '<a href="#">Abdomen</a>
                    </li>
                </ul>
            </td>
            <td>
                
                <ul class="custom-list">
                    <li>';
    if($pust_peq == 1){
        $seccion_sintomas .= '<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Pequeñas</a>
                    </li>
                    <li>';
    if($pust_gran == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Grandes</a>
                    </li>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <th> Picazón </th>
            <th> Costras </th>
            <th> Piel Gruesa </th>
        </thead>
    <tbody>
        <tr>
            <td>
              
              <ul class="custom-list">
                  <li>';
    if($pica_lev == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a>Leve</a>
                  </li>
                  <li>';
    if($pica_mod == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Moderada</a>
                  </li>
                  <li>';
    if($pica_int == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Intensa</a>
                  </li>
              </ul>
            </td>
            <td>
              
              <ul class="custom-list">
    <li>';
    if($cost_peq == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Pequeñas</a>
                  </li>
                  <li>';
    if($cost_med == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Medianas</a>
                  </li>
                  <li>';
    if($cost_gran == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Grandes</a>
                  </li>
              </ul>
            </td>

            <td>
              
              <ul class="custom-list">
                  <li>';
    if($pg_lv == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Leve</a>
                  </li>
                  <li>';
    if($pg_pron == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Pronunciada</a>
                  </li>
                  <li>';
    if($pg_gra == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='<a href="#">Grave</a>
                  </li>
              
            </td>
        </tr>
      </tbody>
    </table>

    <table>
        <thead>
            <th> Piel Enrojecida ';
    if($enrojecimiento == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='</th>
            <th> Mal Olor ';
    if($mal_olor == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='</th>
            <th> Eritema ';
    if($eritema == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='</th>
            <th> Sacudida de Cabeza ';
    if($sacu_cabe == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='</th>
            <th> Cerumen en el oido ';
    if($cerum_oid == 1){
        $seccion_sintomas .='<input type="checkbox" id="checkbox1" checked>';
    }else{
        $seccion_sintomas .='<input type="checkbox" id="checkbox1">';
    }
    $seccion_sintomas .='</th>
        </thead>
        

    </table>
</div>';

    $html = '
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Dermatológica - Síntomas</title>
    <style>
        /* Estilos generales y de secciones */

        
        body {
            font-family: Arial, sans-serif;
            padding: 5px;
            background-color: #f8f9fa;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #4B81BB;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .section {
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        /* Estilos para Datos de la Mascota */
        .datos-mascota {
            background-color: #DCEFFF; /* Color de fondo más claro */
            border: 1px solid #b3e0d7; /* Borde en tono azul claro */
        }

        /* Estilos para Síntomas Presentes */
        .sintomas-presentes {
            background-color: #DCEFFF; /* Color de fondo más oscuro */
        }

        .sintomas-presentes .section-title {
            border-bottom: 2px solid #004080; /* Cambia el color del título a un azul oscuro */
        }

        /* Estilos para Diagnóstico */
        .diagnostico {
            background-color: #DCEFFF; /* Color de fondo neutro */
        }

        .diagnostico .section-title {
            border-bottom: 2px solid #0B3E6A; /* Cambia el color del título a un verde oscuro */
        }

        /* Estilos generales para tablas y listas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #EEF5DB;
        }

        .datos-mascota th {
            background-color: #13293d;
        }
        .sintomas-presentes th {
            background-color: #13293d;
        }

        .diagnostico th {
            background-color: #13293d;
        }



        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .custom-list {
            list-style-type: none;
            padding-left: 0;
            margin-top: 5px;
        }

        .custom-list li {
            display: flex;
            align-items: center;
            padding: 5px;
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
            margin-right: 5px;
        }

        .custom-list li a {
            text-decoration: none;
            color: #424C55;
        }

        .custom-list li a:hover {
            text-decoration: underline;
        }

        /* Estilos generales para la sección de datos de la mascota */
        .td100 {
            width: 105px;
        }

        
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Ficha Dermatológica - Diagnóstico</h1>
    </div>
    <div class="datos-mascota-container">
        <table>
            <tbody>
                <tr>
                    <td class="td100">
                    <img class="imagen-mascota" src="'.$ruta_imagen.'" alt="Foto Mascota" width="100" height="100">
                    </td>
                    <td>
                    <div class="section datos-mascota">
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
                                <td>'.$edad.'</td>
                                <td>'.$raz_pet.'</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    </td>
                </td>
            </tbody>
        </table>
        
        
    </div>
    '.$seccion_sintomas.'
    <div class="section diagnostico">
        <h2 class="section-title">Diagnóstico</h2>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Enfermedad</th>
                <th scope="col">Probabilidad</th>
                <th scope="col">Fecha análisis</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>'.$enf_diag.'</td>
                <td>'.$porcentaje_diag.'%</td>
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