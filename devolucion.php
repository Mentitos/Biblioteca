<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Establecer la conexión a la base de datos (reemplaza los valores con los tuyos)
$conexion = mysqli_connect('localhost', 'root', '', 'biblioteca');

// Verificar la conexión
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Verificar si se ha enviado el formulario de devolución
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los libros marcados para devolución desde el formulario
    //if (isset($_POST["libros_devueltos"]) && is_array($_POST["libros_devueltos"])) {
        //foreach ($_POST["libros_devueltos"] as $libroId) {
            // Ejecutar una consulta SQL para marcar el libro como devuelto en la base de datos
            //$libroId = mysqli_real_escape_string($conexion, $libroId);
            // Eliminar el registro de préstamo correspondiente en la tabla prestamos
           // $query_eliminar = "DELETE FROM prestamos WHERE LibroId = '$libroId'";
           // mysqli_query($conexion, $query_eliminar);
       // }

        // Redirigir a la página de devolución con un mensaje de éxito
       // header("Location: devolucion.php?exito=1");
       // exit;
   // }
// Recuperar los libros marcados para devolución desde el formulario
if (isset($_POST["libros_devueltos"]) && is_array($_POST["libros_devueltos"])) {
    foreach ($_POST["libros_devueltos"] as $libroId) {
        // Ejecutar una consulta SQL para marcar el libro como devuelto en la base de datos
        $libroId = mysqli_real_escape_string($conexion, $libroId);

        // Eliminar el registro de préstamo correspondiente en la tabla prestamos
        $query_eliminar = "DELETE FROM prestamos WHERE LibroId = '$libroId'";
        mysqli_query($conexion, $query_eliminar);

        // Aumentar la cantidad en el stock para el libro devuelto
        $query_aumentar_stock = "UPDATE stock SET Cantidad = Cantidad + 1 WHERE Detalle = '$libroId'";
        mysqli_query($conexion, $query_aumentar_stock);
    }

    // Redirigir a la página de devolución con un mensaje de éxito
    header("Location: devolucion.php?exito=1");
    exit;
}


// Consulta SQL para obtener la lista de libros prestados
$query_libros_prestados = "SELECT libros.Id, libros.Titulo, prestamos.Prestatario, prestamos.FechaPrestamo, prestamos.FechaDevolucion
                           FROM libros
                           JOIN prestamos ON libros.Id = prestamos.LibroId";

$resultado = mysqli_query($conexion, $query_libros_prestados);

// Verificar si hay un mensaje de éxito
$exito = isset($_GET['exito']) ? true : false;

// Cerrar la conexión a la base de datos al final de la página
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devolución de Préstamos</title>
    <link rel="stylesheet" href="css/tablas.css">
    <!-- Agrega aquí tus enlaces a archivos CSS si es necesario -->
</head>
<body>
<?php 
    include("formatos.php");
    prestamos_header(); ?>
    <h1 class="dev">Devolución de Préstamos</h1>

    <?php
    // Mostrar mensaje de éxito si se ha devuelto un libro
    if ($exito) {
        echo '<p style="color: green;">¡El libro se ha devuelto con éxito!</p>';
    }
    ?>

<form action="procesar_devolucion.php" method="post">
    <center>
        <table>
            <thead>
                <tr>
                    <th>Libro</th>
                    <th>Prestatario</th>
                    <th>Fecha de Préstamo</th>
                    <th>Devolver</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar la lista de libros prestados en una tabla
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo '<tr>';
                    echo '<td>' . $fila['Titulo'] . '</td>';
                    echo '<td>' . $fila['Prestatario'] . '</td>';
                    echo '<td>' . $fila['FechaPrestamo'] . '</td>';
                    echo '<td><input type="checkbox" name="libros_devueltos[' . $fila['Id'] . ']" value="1"></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </center>
    <br>

    <input type="submit" class="button1" value="Marcar como Devuelto(s)">
    <input type="button" class="button2" value="Marcar Todos" onclick="marcarTodos()">
</form>

<script>
    function marcarTodos() {
        var checkboxes = document.querySelectorAll('input[name^="libros_devueltos"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = true;
        });
    }
</script>
</body>
</html>