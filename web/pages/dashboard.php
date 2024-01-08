<?php
include("../../db/conexion.php");
if(isset($_SESSION['cedula_usu'])==false){
  header("location:../index.php");
} 

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Administrador</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  

  <!-- Template Main CSS File -->
  <link href="../../assets/css/style.css" rel="stylesheet">

  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js" integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


  
</head>


<body>

  <!-- ======= Mobile nav toggle button ======= -->
  <i class="bi bi-list mobile-nav-toggle d-xl-none"></i>

  <?php include("../menu.php"); ?>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Dashboard</h2>      
        </div>       
        <div class="row">      
            <h3 class="text-center">Estadísticas de Enfermedades y Mascotas</h3>            
            <hr>       
        </div>     
        <section id="gallery">
          <div class="container">
            <p></p>
            <form method="post" action="">
              <div class="row">
                <div class="col-sm-6">
                  <div class="card">
                    <div class="card-header">
                      <div class="card-title">Casos Positivos</div>
                    </div>
                    <div class="card-body">
                      <canvas id="pastel"></canvas>
                    </div>

                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="card">
                    <div class="card-header">
                      <div class="card-title">Diagnóstico Realizado por Usuario</div>
                    </div>
                    <div class="card-body">
                      <table id="mesa" class="table table-bordered table-striped table-hover ">
                        <thead class="bg-secondary ">
                          <th>Usuario</th>
                          <th>#</th>
                        </thead>
                        <tbody class="text-center">
                          <?php
                          $sql = "select CONCAT(u.nombre_usu,' ',u.apellido_usu) as usuario, count(u.nombre_usu) as cuantos_analisis
                          from ficha_dermatologica fd, diagnostico d, mascota m, usuario u
                          where (fd.id_fcder = d.id_fcder and m.id_pet = fd.id_mas and u.cedula_usu = m.cedula_usu and fd.estado_fcder = 1)
                          group by u.cedula_usu ";
                          $result = mysqli_query($conn, $sql);
                          while ($mostrar = mysqli_fetch_array($result)){ ?>
                          <tr>
                            <td><?php echo $mostrar['usuario']; ?></td>
                            <td><?php echo $mostrar['cuantos_analisis']; ?></td>
                          </tr>                    
                          <?php }?>                      
                          </tr>

                        </tbody>
                        <tfoot class="bg-info ">
                            
                        </tfoot>
                      </table>
                    </div>

                  </div>
                </div>
              </div>
              <p></p>
              <div class="row">
                <div class="col-sm-12">
                  <div class="card">
                    <div class="card-header">
                      <div class="card-title">Diagnostico por Raza</div>
                    </div>
                    <div class="card-body">
                      <canvas id="barra"></canvas>
                    </div>

                  </div>
                </div>
              </div>                
            </form>
          </div>  
                
        </section>
        


      </div>
    </section><!-- End About Section -->

    <!-- ======= Facts Section ======= -->
    







  </main><!-- End #main -->



  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../../assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../../assets/vendor/aos/aos.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../../assets/vendor/typed.js/typed.umd.js"></script>
  <script src="../../assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="../../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../../assets/js/main.js"></script>
   


  <script>
    $(document).ready(function () {
      $('#mesa').DataTable({
        "language": {
          "processing": "Procesando...",
          "lengthMenu": "Mostrar _MENU_ registros",
          "zeroRecords": "No se encontraron resultados",
          "emptyTable": "Ningún dato disponible en esta tabla",
          "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
          "infoFiltered": "(filtrado de un total de _MAX_ registros)",
          "search": "Buscar:",
          "infoThousands": ",",
          "loadingRecords": "Cargando...",
          "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
          },
        }
      });
    });



  </script>

/*
  <script>
        // Obtener los datos desde la consulta SQL (ejemplo en PHP)
        <?php
        // Suponiendo que tienes una conexión a la base de datos previamente establecida
        $query = "select d.enf_diag as enfermedad, count(d.enf_diag) as cuantos_casos 
        from ficha_dermatologica fd, diagnostico d
        where (fd.id_fcder = d.id_fcder and fd.estado_fcder = 1)
        group by d.enf_diag";
        $result = mysqli_query($conn, $query);

        // Procesar los datos y guardarlos en un array para usarlos en JavaScript
        $labels = [];
        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $labels[] = $row['enfermedad'];
            $data[] = $row['cuantos_casos'];
        }
        ?>

        // Datos de la gráfica con los datos obtenidos de la consulta
        var datos = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                data: <?php echo json_encode($data); ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',                    
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                ],
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 2
            }]
        };

        // Opciones de la gráfica
        var opciones = {
            responsive: true,
            maintainAspectRatio: false
        };

        // Crear la gráfica de pastel
        var ctx = document.getElementById('pastel').getContext('2d');
        var pastel = new Chart(ctx, {
            type: 'pie',
            data: datos,
            options: opciones
        });
  </script>

  <script>
        // Función para obtener los datos desde la base de datos (ejemplo en PHP)
        <?php

        // Realiza la consulta SQL
        $query2 = "select m.raz_pet as razas, count(m.raz_pet) as cuantas_razas 
        from ficha_dermatologica fd, diagnostico d, mascota m
        where (fd.id_fcder = d.id_fcder and m.id_pet = fd.id_mas and fd.estado_fcder = 1  and m.raz_pet != '-')
        group by m.raz_pet";
        $resultado = mysqli_query($conn, $query2);

        // Procesa los datos y guárdalos en arrays para utilizarlos en JavaScript
        $labels2 = [];
        $data2 = [];

        while ($fila2 = mysqli_fetch_assoc($resultado)) {
            $labels2[] = $fila2['razas'];
            $data2[] = $fila2['cuantas_razas'];
        }
        ?>

        // Datos de la gráfica de barras con los datos obtenidos de la consulta
        const datos2 = {
            labels: <?php echo json_encode($labels2); ?>,
            datasets: [{
                label: 'Casos por Raza',
                data: <?php echo json_encode($data2); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // Opciones de la gráfica
        const opciones2 = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true // Iniciar el eje Y desde cero
                }
            }
        };

        // Crear la gráfica de barras
        const bar = document.getElementById('barra').getContext('2d');
        const barra = new Chart(bar, {
            type: 'bar',
            data: datos2,
            options: opciones2
        });
  </script>


</body>
</html>

