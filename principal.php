<?php
// ... tu código existente ...

// Conectar a la base de datos (reemplaza los valores con los tuyos)
$conexion = mysqli_connect('localhost', 'root', '', 'biblioteca');

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Consulta SQL para obtener préstamos vencidos
$fechaActual = date("Y-m-d");
$queryPrestamosVencidos = "SELECT COUNT(*) AS num_prestamos_vencidos FROM prestamos WHERE FechaDevolucion < '$fechaActual'";

$resultadoPrestamosVencidos = mysqli_query($conexion, $queryPrestamosVencidos);

if ($resultadoPrestamosVencidos) {
    $fila = mysqli_fetch_assoc($resultadoPrestamosVencidos);
    $numPrestamosVencidos = $fila['num_prestamos_vencidos'];

    // Verificar si hay préstamos vencidos
    if ($numPrestamosVencidos > 0) {
        echo '<script type="text/javascript">';
        echo 'alert("¡Tienes préstamos vencidos! Por favor, devuelve los libros a tiempo.")';
        echo '</script>';
    }
}

// Resto de tu código principal.php
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"> <!-- determina la representación de la información -->
<title>BIBLIOTECA </title>
<link rel="stylesheet" href="css/principal.css">
<link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
</head>
<body> <!-- imagen de fondo -->
<?php
$bdnom="biblioteca";
$tabla="libros";
?>
<div class="buttons">
<a href="grilla.php?bd=<?=$bdnom?>&tabla=<?=$tabla?>" class="btn">catalogación</a><br>
<a href="stock.php" class="btn">stock</a><br>
<a href="prestamos.php" class="btn">préstamos</a>
</div>

</body>
</html>
