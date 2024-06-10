<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Establecer la conexión a la base de datos
$conexion = mysqli_connect('localhost', 'root', '', 'biblioteca');

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

if (isset($_POST["libros_devueltos"]) && is_array($_POST["libros_devueltos"])) {
    foreach ($_POST["libros_devueltos"] as $libroId => $cantidadDevuelta) {
        if ($cantidadDevuelta > 0) {
            $libroId = mysqli_real_escape_string($conexion, $libroId);

            // Eliminar el registro de préstamo correspondiente en la tabla prestamos
            $query_eliminar = "DELETE FROM prestamos WHERE LibroId = '$libroId'";
            mysqli_query($conexion, $query_eliminar);

            // Aumentar la cantidad en el stock para el libro devuelto
            $query_aumentar_stock = "UPDATE stock SET Cantidad = cantidad_real WHERE Detalle = $libroId";
            mysqli_query($conexion, $query_aumentar_stock);
        }
    }

    // Redirigir a la página de devolución con un mensaje de éxito
    header("Location: devolucion.php?exito=1");
    exit;
}

// Cerrar la conexión a la base de datos al final de la página
mysqli_close($conexion);
?>