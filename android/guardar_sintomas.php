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
    "edad": $edadMeses,
    "alo_cabe": $alo_cabe,
    "alo_ore": $alo_ore,
    "alo_cue": $alo_cue,
    "alo_lom": $alo_lom,
    "alo_ext": $alo_ext,
    "alo_abdo": $alo_abdo,
    "pica_lev": $pica_lev,
    "pica_mod": $pica_mod,
    "pica_int": $pica_int,
    "enrojecimiento": $enrojecimiento,
    "cost_peq": $cost_peq,
    "cost_med": $cost_med,
    "cost_gran": $cost_gran,
    "pg_lv": $pg_lv,
    "pg_pron": $pg_pron,
    "pg_gra": $pg_gra,
    "pust_peq": $pust_peq,
    "pust_gran": $pust_gran,
    "mal_olor": $mal_olor,
    "eritema": $eritema,
    "sacu_cabe": $sacu_cabe,
    "cerum_oid": $cerum_oid
  }
  DATA;

  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

  $resp = curl_exec($curl);
  curl_close($curl);

  //echo $resp;

  //decodificar el json de la api de machine learning
  $respuesta_array = json_decode($resp, true);

  if ($respuesta_array !== null) {
    // Acceder a los datos decodificados
    $prediccion = $respuesta_array['prediccion'];
    $probabilidad = $respuesta_array['probabilidad'];
    
  }else {
      // La decodificación falló
      echo "error";
  }

  



  $consulta_idf = "SELECT id_fcder,id_mas FROM ficha_dermatologica where (id_mas ='$id_mas') order by id_fcder desc limit 1;";
// Ejecutar la consulta
  $resultado_idf = $conn->query($consulta_idf);

  $no_filas = $resultado_idf->num_rows;
  if($no_filas>0){
    while ($filas = $resultado_idf->fetch_assoc()) {
      $id_fcder = $filas['id_fcder'];
    }
  }

  $datos = json_decode($resp);
  $enfermedad = $datos->prediccion;
  $probabilidad = $datos->probabilidad;

  $verificado = 0;


  $sentencia = "INSERT INTO diagnostico(porcentaje_diag, enf_diag, id_fcder, estado_diag, verificado_diag) 
                VALUES ('$probabilidad','$enfermedad', '$id_fcder', '$estado', '$verificado')";
        $respuesta = $conn->query($sentencia);

  if ($respuesta == true) {
      $verificado = 0;
  } else {
      echo 'Se ha producido un error';
  }

  $array_enviar = array(
    "prediccion" => $prediccion,
    "probabilidad" => $probabilidad,
    "id_fcder" => $id_fcder,
    "id_pet" => $id_mas,
  );

  // Codificar el array asociativo a formato JSON
  $json_enviar = json_encode($array_enviar);

  // Verificar si la codificación fue exitosa
  if ($json_enviar !== false) {
      // Mostrar el JSON codificado
      echo $json_enviar;

      // Puedes enviar este JSON como respuesta, guardar en una base de datos, etc.
  } else {
      // La codificación falló
      echo "error";
  }



}else{
	echo 'error';
}
$conn->close();

?>