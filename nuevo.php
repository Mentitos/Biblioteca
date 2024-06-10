<?php 
    include("formatos.php");
    prestamos_header();
?>
<head>
    <title>Registrar Nuevo Préstamo</title>
    <link rel="stylesheet" href="css/nuevo.css">
</head>
<body>
    <h1>Registrar Nuevo Préstamo</h1>
    <form action="procesar_prestamo.php" method="POST" class="book-details">
        <div class="contenido">
            <label for="libro">Libro:</label><br>
            <select name="libro" id="libro"><br>
                <?php
                    // Conectar a la base de datos
                    $conexion = mysqli_connect('localhost', 'root', '', 'biblioteca');

                    // Verificar la conexión
                    if (!$conexion) {
                        die("Error al conectar a la base de datos: " . mysqli_connect_error());
                    }

                    // Consulta SQL para obtener la lista de libros disponibles
                    $consulta = "SELECT s.Detalle, l.Titulo FROM stock s
                                 JOIN libros l ON s.Detalle = l.Id";

                    // Ejecutar la consulta
                    $resultado = mysqli_query($conexion, $consulta);

                    if (!$resultado) {
                        die("Error al ejecutar la consulta: " . mysqli_error($conexion));
                    }

                    // Mostrar la lista de libros disponibles en un menú desplegable
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        echo "<option value='" . $fila['Detalle'] . "'>" . $fila['Titulo'] . "</option>";
                    }

                    // Liberar el resultado
                    mysqli_free_result($resultado);

                    // Cerrar la conexión a la base de datos
                    mysqli_close($conexion);
                ?>
            </select><br>
            <label for="prestatario">Prestatario:</label>
            <input class="caja-de-texto" type="text" name="prestatario" id="prestatario">
            <label for="prestatario">Cantidad:</label>
            <input class="caja-de-texto" type="text" name="cantidad">
            <label for="prestatario">Límite de tiempo para devolverlo:</label>
            <input class="caja-de-texto" type="date" name="fechalimite" placeholder="0000-00-00">
            <input type="submit" class="btn-menu" value="Registrar">
        </div>
    </form>
    <h2>ADVERTENCIA: si los libros no se devuelven durante el límite de tiempo se le enviará un correo al prestatario responsable</h2>
</body>
</html>

