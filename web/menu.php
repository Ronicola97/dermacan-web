<?php



?>

<!-- ======= Header ======= -->



  <header id="header">

    <div class="d-flex flex-column">

      <div class="profile">
        <p></p>
        <h2 class="text-light"><a href=""><?php echo $_SESSION['nombre'] ?> / Administrador </a></h2>
        
      </div>

      <nav id="navbar" class="nav-menu navbar">
        <ul class="active">
          <li><a href="admin.php" class="nav-link scrollto"><i class="bx bx-home"></i> <span>Inicio</span></a></li>
          <!-- <li><a href="pages/subircsv.php" class="nav-link scrollto"><i class="bi bi-bar-chart-line"></i> <span>Subir CSV</span></a></li> -->
          <li><a href="" class="nav-link scrollto"><i class="bi bi-clipboard2-data"></i> <span>Reporte</span></a></li>
          <li><a href="" class="nav-link scrollto"><i class="bi bi-people"></i><span>Enfermedades</span></a></li>
          <li><a href="php/cerrar.php" class="nav-link scrollto"><i class="bi bi-arrow-bar-right"></i> <span>Cerrar sesión</span></a></li>
        </ul>
      </nav><!-- .nav-menu -->
    </div>
  </header><!-- End Header -->