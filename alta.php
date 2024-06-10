
    <head>
        <link rel="stylesheet" href="css/modificarb.css">
    </head>
    <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Variables importantes
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
    // Obtener los valores ingresados en el formulario
    $valores = array();
    foreach ($_POST as $columna => $valor) {
        $valores[$columna] = $valor;
    }

    // Construir la consulta de inserción
    $sql = "INSERT INTO $tabla (";
    $sql .= implode(", ", array_keys($valores));
    $sql .= ") VALUES ('";
    $sql .= implode("', '", $valores);
    $sql .= "')";

    if ($conn->query($sql) === TRUE) {
        // Redireccionar al usuario a la grilla
        header("Location: grilla.php?bd=$bdnom&tabla=$tabla");
        exit();
    } else {
        // Mostrar mensaje de error en caso de fallo en la inserción
        echo "Error al guardar los datos: " . $conn->error;
    }
}

// Consulta para obtener los nombres de las columnas
$sql_columnas = "SELECT column_name FROM information_schema.columns WHERE table_schema = '$bdnom' AND table_name = '$tabla'";
$resultado_columnas = $conn->query($sql_columnas);

if ($resultado_columnas->num_rows > 0) {
    // Crear formulario de alta
    echo "<center>";
    echo "<h2>Formulario de Alta</h2>";
    echo "<div class='contenedor'>";
    echo "<form action='alta.php?bd=$bdnom&tabla=$tabla' method='POST'>";
    echo "<div class='caja'>";
    while ($fila_columnas = $resultado_columnas->fetch_assoc()) {
        $columna = $fila_columnas["column_name"];
        echo "<div class='columna'>";
        echo "<label>$columna:</label>";
        echo "<input type='text' name='$columna' id='$columna'>";
        echo "</div>";
    }
    echo "</div>";
    echo "<input type='submit' value='Guardar'>";
    echo "</form>";
    echo "</div>";
    echo "</center>";
} else {
    echo "No se encontraron columnas en la tabla $tabla.";
}

// Cerrar la conexión
$conn->close();
?>