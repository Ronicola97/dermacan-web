<?php
include("../../db/conexion.php");
// Obtenemos el archivo CSV
$archivo = $_FILES['csv'];

// Comprobamos que el archivo sea válido
if ($archivo['error'] != UPLOAD_ERR_OK) {
  die("Error al cargar el archivo");
}

// Leemos el contenido del archivo
$contenido = file_get_contents($archivo['tmp_name']);

// Convertimos el contenido a un array
$datos = array_map('str_getcsv', explode("\n", $contenido));

// Eliminamos la primera fila del array, que contiene los encabezados
array_shift($datos);

foreach ($datos as $fila):
    $dni = "0".$fila[0];
    $edad = $fila[1];

    $alo_cabe = $fila[2];
    $alo_ore = $fila[3];
    $alo_cue = $fila[4];
    $alo_lom = $fila[5];
    $alo_ext = $fila[6];
    $alo_abdo = $fila[7];
    $pica_lev = $fila[8];
    $pica_mod = $fila[9];
    $pica_int = $fila[10];
    $enrojecimiento = $fila[11];
    $cost_peq = $fila[12];
    $cost_med = $fila[13];
    $cost_gran = $fila[14];
    $pg_lv = $fila[15];
    $pg_pron = $fila[16];
    $pg_gra = $fila[17];
    $pust_peq = $fila[18];
    $pust_gran = $fila[19];
    $mal_olor = $fila[20];
    $eritema = $fila[21];
    $sacu_cabe = $fila[22];
    $cerum_oid = $fila[23];

    $enfermedad = $fila[24];

    $id_mas = $fila[25];
    $name_masc = "-";
    $sector = "-";
    $fnaci = date('Y-m-d');
    $raz_pet = "-";

    $fechaActual = date('Y-m-d');
    $estado = 1;
    $id_ficha = $fila[26];

    $ya_existe = mysqli_query($conn, "select * FROM mascota WHERE (id_pet = '$id_mas')");
    $numerodefilas = mysqli_num_rows($ya_existe);

    if ($numerodefilas > 0) {
            $existe = "existe";
            echo $existe;
        }else{
            $sentencia = "INSERT INTO mascota(id_pet,nombre_pet, direccion_pet, fnaci_pet, raz_pet, cedula_usu,estado_pet) 
            VALUES ('$id_mas','$name_masc','$sector','$fnaci','$raz_pet','$dni','$estado')";
            $respuesta = $conn->query($sentencia);
            if($respuesta==true){
                echo 'grabado';
            }else{
                echo 'Se ha producido un error';
            }
        }
    

    if(true){
        
        $sql = "INSERT INTO ficha_dermatologica
            ('id_fcder',
            `fecha_fcder`,
            `id_mas`,
            `estado_fcder`,
            `alo_cabe`,
            `alo_ore`,
            `alo_cue`,
            `alo_lom`,
            `alo_ext`,
            `alo_abdo`,
            `pica_lev`,
            `pica_mod`,
            `pica_int`,
            `enrojecimiento`,
            `cost_peq`,
            `cost_med`,
            `cost_gran`,
            `pg_lv`,
            `pg_pron`,
            `pg_gra`,
            `pust_peq`,
            `pust_gran`,
            `mal_olor`,
            `eritema`,
            `sacu_cabe`,
            `cerum_oid`)
            VALUES
            ('$id_ficha',
            '$fechaActual',
            '$id_mas',
            '$estado',
            `$alo_cabe`,
            `$alo_ore`,
            `$alo_cue`,
            `$alo_lom`,
            `$alo_ext`,
            `$alo_abdo`,
            `$pica_lev`,
            `$pica_mod`,
            `$pica_int`,
            `$enrojecimiento`,
            `$cost_peq`,
            `$cost_med`,
            `$cost_gran`,
            `$pg_lv`,
            `$pg_pron`,
            `$pg_gra`,
            `$pust_peq`,
            `$pust_gran`,
            `$mal_olor`,
            `$eritema`,
            `$sacu_cabe`,
            `$cerum_oid`);";

            $respuesta2 = $conn->query($sql);
        if($respuesta2==true){
            echo 'grabado2';
            $porcentaje = 100;
            $verificado_diag = "SI";

            $sql_diagnostico = "INSERT INTO diagnostico
            (
            `porcentaje_diag`,
            `enf_diag`,
            `id_fcder`,
            `estado_diag`,
            `verificado_diag`)
            VALUES
            ('$porcentaje',
            '$enfermedad',
            '$id_ficha',
            '$estado',
            '$verificado_diag');
            ";

            $respuesta3 = $conn->query($sql_diagnostico);

            if($respuesta3==true){
                echo 'grabado';
            }else{
                echo 'Se ha producido un error';
            }


        }else{
            echo 'Se ha producido un error2';
        }

    }else{
        echo 'Se ha producido un error';
    }

endforeach;

$conn->close();
?>


