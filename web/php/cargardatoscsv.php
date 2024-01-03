<?php

// Obtenemos el archivo CSV
$archivo = $_FILES['archivo'];

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

// Mostramos los datos en HTML
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <title>Datos del archivo CSV</title>
</head>
<body>

  <table>
    <thead>
      <tr>
        <?php foreach ($datos[0] as $cabecera): ?>
          <th><?php echo $cabecera; ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($datos as $fila): ?>
        <tr>
          <?php foreach ($fila as $dato): ?>
            <td><?php echo $dato; ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</body>
</html>
