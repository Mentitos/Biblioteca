<head>
    <link rel="stylesheet" href="css/modificarb.css">
</head>
<body>
    <html>

<?php
// Verificar si se recibió el nombre de la tabla y de la base de datos por GET
if (isset($_GET['tabla']) && isset($_GET['bd'])) {
    $tabla = $_GET['tabla']; // Nombre de la tabla
    $bdnom = $_GET['bd']; // Nombre de la base de datos

    $conn = new mysqli("localhost", "root", "", $bdnom);
    // Verificar si hay algún error en la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    // Incluir el código de formatos.php
    include 'formatos.php'; 
    mostrar_header();
    // Verificar si se recibieron los datos del formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Variables importantes
    $tabla = $_GET['tabla']; // Nombre de la tabla
    $bdnom = $_GET['bd']; // Nombre de la base de datos
    $id = $_GET['id']; // ID del registro a modificar
    $pk = $_GET['modificar']; // Nombre de la columna que es clave primaria

    // Obtener los valores ingresados en el formulario
    $valores = array();
    foreach ($_POST as $columna => $valor) {
        $valores[$columna] = $valor;
    }
    // Construir la consulta de modificación
    $sql = "UPDATE $tabla SET ";
    foreach ($valores as $columna => $valor) {
        $sql .= "$columna = '$valor', ";
    }
    // Eliminar la coma y el espacio extra al final
    $sql = rtrim($sql, ', ');
    $sql .= " WHERE $pk = '$id'";

    if ($conn->query($sql) === TRUE) {
        // Mostrar mensaje de éxito
        echo "Registro modificado con éxito.";

        // Redireccionar al usuario a la grilla después de 2 segundos
        header("refresh:1; url=grilla.php?bd=$bdnom&tabla=$tabla");
        exit();
    } else {
        echo "Error al modificar el registro: " . $conn->error;
    }
    // Verificar si se recibió el parámetro "modificar" y el ID del registro a modificar
    }else if (isset($_GET['modificar']) && isset($_GET['id'])) {
    
        $id = $_GET['id'];
        $pk = $_GET['modificar'];
        // Obtener los datos del registro a modificar

        $sql = "SELECT Autores, Titulo, Edicion, Publicacion, Descripcion, Notas, Contenido, Nivel, ISBN, CDU, Materia, Tema, Acceso FROM $tabla WHERE $pk = $id"; // Reemplaza 'id' por el nombre de la columna que contiene el identificador único
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            $registro = $resultado->fetch_assoc();

            // Aquí puedes mostrar el formulario de modificación con los campos prellenados con los valores del registro
            echo"<center>";
            echo "<div class='contenedor'>";
            echo "<h2>MODIFICAR</h2>";
            
            
            echo "<form action='modificar.php?bd=$bdnom&tabla=$tabla&modificar=$pk&id=$id' method='POST'>";
            echo "<div class='caja'>";
            foreach ($registro as $columna => $valor) {
                echo "<div class='columna'>";
                echo "<label>$columna:</label>";
                echo "<input type='text' name='$columna' value='$valor'>";
                echo "</div>";
            }
            echo "</div>";
            echo "<input type='submit' value='Guardar'>";
            echo "</form>";
            echo "</div>";
            echo "</center>";

            
        } else {
            echo "No se encontró el registro con ID $id.";
        }
    } else {
        echo "Falta el parámetro 'modificar' o el parámetro 'id'.";
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Faltan los parámetros 'tabla' y/o 'bd'.";
}

?>
    
    </body>
    </html>