<?php
// Establece la conexión a la base de datos (reemplaza los valores con los tuyos)
$conexion = mysqli_connect('localhost', 'root', '', 'biblioteca');

// Verifica la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Verificar si se hizo clic en el botón "Borrar Historial"
if (isset($_POST['borrar_historial'])) {
    // Código para borrar el historial
    $borrarHistorial = "DELETE FROM historial_prestamos";
    if (mysqli_query($conexion, $borrarHistorial)) {
        echo "Historial borrado con éxito.";
    } else {
        echo "Error al borrar el historial: " . mysqli_error($conexion);
    }
}

// Consulta para obtener el historial de préstamos
$consultaHistorial = "SELECT libros.Titulo, hp.Prestatario, hp.FechaPrestamo
                     FROM libros
                     JOIN historial_prestamos hp ON libros.Id = hp.LibroId";
// Ejecutar la consulta
$resultadoHistorial = mysqli_query($conexion, $consultaHistorial);

// Verificar si se hizo clic en el botón "Borrar Historial"
if (isset($_POST['borrar_historial'])) {
    // Recargar la página después de borrar el historial
    header("refresh:1;url=historial_prestamos.php");
}

// Cierra la conexión a la base de datos
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial de Préstamos</title>
    <link rel="stylesheet" href="css/tablas.css">
</head>
<body>
<?php 
    include("formatos.php");
    prestamos_header(); ?>
    <h1 class="dev">Historial de Préstamos</h1>
    <!-- Mostrar el historial de préstamos en una tabla -->
    <center><table border="1">
        <tr>
            <th>Título del Libro</th>
            <th>Prestatario</th>
            <th>Fecha de Préstamo</th>
        </tr>
        <?php while ($fila = mysqli_fetch_assoc($resultadoHistorial)) { ?>
            <tr>
                <td><?php echo $fila['Titulo']; ?></td>
                <td><?php echo $fila['Prestatario']; ?></td>
                <td><?php echo $fila['FechaPrestamo']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <!-- Botón para borrar el historial -->
    <form method="POST">
        <input type="submit" class="button2" name="borrar_historial" value="Borrar Historial">
    </form>
</center>
</body>
</html>