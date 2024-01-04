<?php
include("../db/conexion.php");

date_default_timezone_set("America/Guayaquil");

$dni = $_POST['cedula_dueno'];
$fechaActual = date('Y-m-d');

$id_mas = $_POST['id_mascota'];

// Consulta SQL
$consulta = "select * from mascota where (cedula_usu='$dni' and id_pet = '$id_mas')";
// Ejecutar la consulta
$resultado = $conn->query($consulta);
// Iterar sobre los resultados
foreach ($resultado as $registro) {
  // Obtener la fecha de nacimiento de la mascota
  $fechaNacimiento = $registro['fnaci_pet'];
  // Obtener la fecha actual
  $fechaActual = date('Y-m-d');
  // Calcular la diferencia entre las dos fechas
  $diferencia = strtotime($fechaActual) - strtotime($fechaNacimiento);
  // Divide la diferencia por 30.5 días
  $edadMeses = $diferencia / (30.5 * 86400);
}




$estado = 1;

$alo_cabe = $_POST['alo_cabeza'];
$alo_ore = $_POST['alo_orejas'];
$alo_cue = $_POST['alo_cuello'];
$alo_lom = $_POST['alo_lomo'];
$alo_ext = $_POST['alo_extremidades'];
$alo_abdo = $_POST['alo_abdomen'];

$pica_lev = $_POST['pica_leve'];
$pica_mod = $_POST['pica_mode'];
$pica_int = $_POST['pica_inte'];

$enrojecimiento = $_POST['enrojecimiento'];

$cost_peq = $_POST['costra_peque'];
$cost_med = $_POST['costra_media'];
$cost_gran = $_POST['costra_grand'];

$pg_lv = $_POST['piel_gru_leve'];
$pg_pron = $_POST['piel_gru_pron'];
$pg_gra = $_POST['piel_gru_grav'];

$pust_peq = $_POST['pustula_peque'];
$pust_gran = $_POST['pustula_grand'];

$mal_olor = $_POST['mal_olor'];
$eritema = $_POST['eritema'];
$sacu_cabe = $_POST['sacudida_cabeza'];
$cerum_oid = $_POST['cerumen_oido'];



$sql = "INSERT INTO ficha_dermatologica
            (fecha_fcder, id_mas, estado_fcder,
            alo_cabe,
            alo_ore,
            alo_cue,
            alo_lom,
            alo_ext,
            alo_abdo,
            pica_lev,
            pica_mod,
            pica_int,
            enrojecimiento,
            cost_peq,
            cost_med,
            cost_gran,
            pg_lv,
            pg_pron,
            pg_gra,
            pust_peq,
            pust_gran,
            mal_olor,
            eritema,
            sacu_cabe,
            cerum_oid,
            edad_meses)
            VALUES
            ('$fechaActual', '$id_mas', '$estado',
            '$alo_cabe',
            '$alo_ore',
            '$alo_cue',
            '$alo_lom',
            '$alo_ext',
            '$alo_abdo',
            '$pica_lev',
            '$pica_mod',
            '$pica_int',
            '$enrojecimiento',
            '$cost_peq',
            '$cost_med',
            '$cost_gran',
            '$pg_lv',
            '$pg_pron',
            '$pg_gra',
            '$pust_peq',
            '$pust_gran',
            '$mal_olor',
            '$eritema',
            '$sacu_cabe',
            '$cerum_oid',
            '$edadMeses');";

            $respuesta = $conn->query($sql);


if($respuesta==true){
	//echo 'grabado';
  $url = "https://dermacan-ml.up.railway.app/run-python-script/";

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $headers = array(
    "Accept: application/json",
    "Content-Type: application/json",
  );
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  $data = <<<DATA
  {
    "edad": '$edadMeses',
    "alo_cabe": '$alo_cabe',
    "alo_ore": '$alo_ore',
    "alo_cue": '$alo_cue',
    "alo_lom": '$alo_lom',
    "alo_ext": '$alo_ext',
    "alo_abdo": '$alo_abdo',
    "pica_lev": '$pica_lev',
    "pica_mod": '$pica_mod',
    "pica_int": '$pica_int',
    "enrojecimiento": '$enrojecimiento',
    "cost_peq": '$cost_peq',
    "cost_med": '$cost_med',
    "cost_gran": '$cost_gran',
    "pg_lv": '$pg_lv',
    "pg_pron": '$pg_pron',
    "pg_gra": '$pg_gra',
    "pust_peq": '$pust_peq',
    "pust_gran": '$pust_gran',
    "mal_olor": '$mal_olor',
    "eritema": '$eritema',
    "sacu_cabe": '$sacu_cabe',
    "cerum_oid": '$cerum_oid'
  }
  DATA;

  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

  $resp = curl_exec($curl);
  curl_close($curl);

  echo $resp;


}else{
	echo 'error';
}
$conn->close();

?>