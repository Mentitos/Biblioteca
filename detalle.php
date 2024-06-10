<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/tablas.css">
    <title>Detalles del Libro</title>
</head>
<body > <!-- Aplica la clase para el fondo otoñal -->
    <?php
    ini_set('display_errors', 1);
error_reporting(E_ALL);
    // Variables importantes
    $tabla = $_GET['tabla']; // Nombre de la tabla
    $bdnom = $_GET['bd']; // Nombre de la base de datos

    // Incluir el código de formatos.php
    include 'formatos.php'; 

    // Llamar a la función en header.php con el el header
    mostrar_header();
    
    // Realizar la conexión 
    $conn = new mysqli("localhost", "root", "", $bdnom);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    // Obtener el ID del libro desde la URL
    $id = $_GET['id'];
    
    // Consulta para obtener los detalles del libro
    $sql = "SELECT * FROM libros WHERE ID = '$id'";
    $resultado = $conn->query($sql);
    
    if ($resultado->num_rows > 0) {
        $libro = $resultado->fetch_assoc();
        echo "<div class='book-details'>";
        echo "<h1 class='title'>Detalles del Libro</h1>";
        echo "<p><strong class='text'>Autores:</strong> " . $libro['Autores'] . "</p>";
        echo "<p><strong class='text'>Título:</strong> " . $libro['Titulo'] . "</p>";
        echo "<p><strong class='text'>Edición:</strong> " . $libro['Edicion'] . "</p>";
        echo "<p><strong class='text'>Publicación:</strong> " . $libro['Publicacion'] . "</p>";
        echo "<p><strong class='text'>Descripción:</strong> " . $libro['Descripcion'] . "</p>";
        echo "<p><strong class='text'>Notas:</strong> " . $libro['Notas'] . "</p>";
        echo "<p><strong class='text'>Contenido:</strong> " . $libro['Contenido'] . "</p>";
        echo "<p><strong class='text'>Nivel:</strong> " . $libro['Nivel'] . "</p>";
        echo "<p><strong class='text'>ISBN:</strong> " . $libro['ISBN'] . "</p>";
        echo "<p><strong class='text'>CDU:</strong> " . $libro['CDU'] . "</p>";
        echo "<p><strong class='text'>Materia:</strong> " . $libro['Materia'] . "</p>";
        echo "<p><strong class='text'>Tema:</strong> " . $libro['Tema'] . "</p>";
        echo "<p><strong class='text'>Acceso:</strong> " . $libro['Acceso'] . "</p>";
        $columnaId="ID"; 
        echo "<div class='icon'>";
        echo "<a href='modificar.php?bd=$bdnom&tabla=$tabla&modificar=$columnaId&id=$id'><img src='img/edit.png' title='modificar' width=35px height=35px></a>";
         // Enlace para eliminar con confirmación
        echo "<a href='javascript:void(0);' class='delete-link' onclick='confirmDelete($id)'><img src='img/borrar.png' title='borrar' width=27px height=27px></a>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='book-details'>";
        echo "<p class='text'>No se encontraron detalles para este libro.</p>";
        echo "</div>";
    }
    
    // Cerrar la conexión
    $conn->close();
    ?>
    <script>
function confirmDelete(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este libro?")) {
        window.location.href = 'grilla.php?bd=<?=$bdnom?>&tabla=<?=$tabla?>&eliminar=' + id;
    }
}
</script>

</body>
</html>

