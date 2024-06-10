<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Conectar a la base de datos
$conexion = mysqli_connect('localhost', 'root', '', 'biblioteca');

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Obtener los datos del formulario
$libroId = $_POST['libro'];
$prestatario = $_POST['prestatario'];
$fecha = $_POST['fechalimite'];
$cantidad = $_POST['cantidad'];

// Obtener la cantidad actual de libros disponibles
$queryCantidadActual = "SELECT Cantidad FROM stock WHERE Detalle = '$libroId'";
$resultadoCantidad = mysqli_query($conexion, $queryCantidadActual);

if ($resultadoCantidad) {
    $fila = mysqli_fetch_assoc($resultadoCantidad);
    $cantidadActual = $fila['Cantidad'];

    // Verificar si hay suficientes libros disponibles
    if ($cantidadActual >= $cantidad) {
        // Restar la cantidad prestada de la cantidad actual
        $nuevaCantidad = $cantidadActual - $cantidad;

        // Insertar el préstamo en la base de datos
        $insertarPrestamo = "INSERT INTO prestamos (LibroId, Prestatario, FechaPrestamo, FechaDevolucion) VALUES ('$libroId', '$prestatario', NOW(), '$fecha')";

        if (mysqli_query($conexion, $insertarPrestamo)) {
            // Actualizar la cantidad en la tabla "stock"
            $actualizarLibro = "UPDATE stock SET Cantidad = '$nuevaCantidad' WHERE Detalle = '$libroId'";
            mysqli_query($conexion, $actualizarLibro);

            $insertarHistorial = "INSERT INTO historial_prestamos (LibroId, Prestatario, FechaPrestamo) VALUES ('$libroId', '$prestatario', NOW())";
            mysqli_query($conexion, $insertarHistorial);
            echo "Préstamo registrado con éxito.";
            header("refresh:1;url=prestamos.php");
        } else {
            echo "Error al registrar el préstamo: " . mysqli_error($conexion);
        }
    } else {
        echo "No hay suficientes libros disponibles.";
    }
} else {
    echo "Error al obtener la cantidad actual: " . mysqli_error($conexion);
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
