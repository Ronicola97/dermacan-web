<?php

include("../db/conexion.php");
date_default_timezone_set("America/Guayaquil");
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
          <li><a href="dashboard.php" class="nav-link scrollto"><i class="bi bi-bar-chart-line"></i> <span>Dashboard</span></a></li>
          <li><a href="admin_report.php" class="nav-link scrollto"><i class="bi bi-clipboard2-data"></i> <span>Reportes</span></a></li>
          <li><a href="admin_per.php" class="nav-link scrollto"><i class="bi bi-people"></i><span>Personal</span></a></li>
          <li><a href="almacenar/cerrar.php" class="nav-link scrollto"><i class="bi bi-arrow-bar-right"></i> <span>Cerrar sesión</span></a></li>
        </ul>
      </nav><!-- .nav-menu -->
    </div>
  </header><!-- End Header -->