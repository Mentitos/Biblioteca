<!DOCTYPE html>
<html>
<head>
    <title>Grilla</title>
</head>
<body>
<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Variables importantes
$tabla = $_GET['tabla']; // Nombre de la tabla
$bdnom = $_GET['bd']; // Nombre de la base de datos

// Incluir el código de formatos.php
include 'formatos.php'; 

// Llamar a la función en header.php con el el header
grilla_header();
?>
<div class="d-flex justify-content-center align-items-center">
<div class="d-flex justify-content-center align-items-center flex-column" style="min-height: 80vh; max-width: 60%;">
  <?php
  catalogacion($bdnom, $tabla);
  ?>
</div>
</div>
</body>
</html>