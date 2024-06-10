<?php include("formatos.php");
grilla_header(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/tablas.css">
	
</head>
<body>
    <div class="container">
    	<?php 
$bdnom="biblioteca";
$tabla="stock";
    // Realizar la conexción 
$conn = new mysqli("localhost", "root", "", $bdnom);

// Verificar si hay algún error en la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Paginación 
$registrosPorPagina = 25; // Cantidad de registros a mostrar por página
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Número de página actual, si no se proporciona, es la página 1
$sql_count = "SELECT COUNT(*) as total FROM $tabla";


// Consulta para obtener los registros de la tabla
$offset = ($paginaActual - 1) * $registrosPorPagina; // Calcula el desplazamiento
$sql = "SELECT Detalle AS Nombre, Estanteria, Cantidad FROM $tabla LIMIT $registrosPorPagina OFFSET $offset";
$resultado = $conn->query($sql);

$resultado_count = $conn->query($sql_count);
$fila_count = $resultado_count->fetch_assoc();
$totalRegistros = $fila_count['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina); // Calcula el número total de páginas

// Obtener el nombre de la columna que es clave primaria
$sql_primary_key = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = '$bdnom' AND TABLE_NAME = '$tabla' AND CONSTRAINT_NAME = 'PRIMARY'";
    $resultado_primary_key = $conn->query($sql_primary_key);
    $fila_primary_key = $resultado_primary_key->fetch_assoc();
    $columnaId = $fila_primary_key['COLUMN_NAME'];


// Verificar si se recibió el parámetro "eliminar"
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    // Consulta para eliminar el registro de la tabla
    $sql_eliminar = "DELETE FROM $tabla WHERE $columnaId = '$id'";
    if ($conn->query($sql_eliminar) === TRUE) {
        // Redireccionar al usuario a la grilla sin el parámetro "eliminar"
        header("Location: stock.php?bd=$bdnom&tabla=$tabla");
        exit();
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }
}
if ($resultado->num_rows > 0) {
    echo"<center>";
    echo "<table>";
    echo "<tr>";
    // Obtener los nombres de las columnas
    $columnas = $resultado->fetch_assoc();
    foreach ($columnas as $columna => $valor) {
         echo "<th>$columna</th>";
    }echo "<th colspan=2>Acciones</th>";
    echo "</tr>"; // Termina la fila de las columnas

    $resultado->data_seek(0); // Vaciar la data

// Mostrar los registros
while ($fila = $resultado->fetch_assoc()) {
    echo "<tr>";
    $detalle = $fila['Nombre'];

    
    // Ejecutar la subconsulta para obtener el nombre
    $cons = "SELECT Titulo FROM libros WHERE Id = '$detalle'";
    $resultadoSubconsulta = $conn->query($cons);
    
    if ($resultadoSubconsulta) {
        // Obtener el resultado de la subconsulta como un array numérico
        $filaSubconsulta = $resultadoSubconsulta->fetch_row();
        $nombre = $filaSubconsulta[0]; // El índice 0 es el primer campo de la consulta
        
echo "<td style='text-align:center;'>$nombre</td>";
    } else {
        // Manejo de error si la subconsulta falla
        echo "<td>Error en la subconsulta</td>";
    }
    echo "<td>" . $fila['Estanteria'] . "</td>";
    echo "<td>" . $fila['Cantidad'] . "</td>";
    echo "<td><a href='detalle.php?bd=$bdnom&tabla=libros&id=$detalle'><img src='img/lupa.png'></a></td>";
    echo "</tr>";
}
    echo "</table>";
    echo"</center>";
} else {
    echo "No se encontraron registros en la tabla $tabla.";
}?>

<div class="d-flex justify-content-center">
  <nav aria-label="Page navigation example">
    <ul class="pagination">
      <?php
      for ($i = 1; $i <= $totalPaginas; $i++) {
          if ($i == $paginaActual) {
              echo "<li class='page-item active'><span class='button1'>$i</span></li>"; // Resalta la página actual
          } else {
              echo "<li class='page-item'><a href='grilla.php?bd=$bdnom&tabla=$tabla&pagina=$i' class='button1'>$i</a></li>";
          }
      }
      
      ?>
    </ul>
  </nav>

</div>
<a class='button1' href='alta.php?bd=biblioteca&tabla=stock'>Nuevo</a>
    </div>
</body>
</html>