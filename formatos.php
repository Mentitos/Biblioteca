<!DOCTYPE html>
<html>
    <?php 
function grilla_header(){
  ?>
<head>
<meta charset="UTF-8"> 
<link rel="stylesheet" href="css/bootstrap.min.css"> 
<link rel="stylesheet" href="css/tablas.css">
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.8.10/jquery-ui.min.js"></script>  
</head>
    <nav class="navbar navbar-expand-lg fondo-header">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto divorse">
                <li class="nav-item active">
                    <a href="index.php" class="btn btn-outline-light p-2">Iniciar sesion </a>
                </li>
                <li class="nav-item active">
                    <a href="principal.php" class="btn btn-outline-light p-2">Principal </a>
                </li>
            </ul>
            <img src="img/logo.png" width="70" height="60">
        </div>
    </nav>
<?php  }  


function mostrar_header(){
    ?>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="css/bootstrap.min.css"> 
<link rel="stylesheet" href="css/tablas.css">
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.8.10/jquery-ui.min.js"></script>  
</head>
    <nav class="navbar navbar-expand-lg fondo-header">
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto divorse">
                <li class="nav-item active">
                    <a href="index.php" class="btn btn-outline-light p-2">Iniciar sesion </a>
                </li>
                <li class="nav-item active">
                    <a href="principal.php" class="btn btn-outline-light p-2">Principal </a>
                </li>
                <li class="nav-item active">
                    <a href="grilla.php?bd=biblioteca&tabla=libros" class="btn btn-outline-light p-2"> Grilla</a>
                </li>
            </ul>
            <img src="img/logo.png" width="70" height="60">
        </div>
    </nav>
<?php  }

function prestamos_header(){
    ?>
<head>
<meta charset="UTF-8"> 
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/tablas.css">
</head>
    <nav class="navbar navbar-expand-lg fondo-header">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto divorse">
                <li class="nav-item active">
                    <a href="index.php" class="btn btn-outline-light p-2">Iniciar sesion </a>
                </li>
                <li class="nav-item active">
                    <a href="principal.php" class="btn btn-outline-light p-2">Principal </a>
                </li>
                <li class="nav-item active">
                    <a href="prestamos.php" class="btn btn-outline-light p-2"> Volver</a>
                </li>
            </ul>
            <img src="img/logo.png" width="70" height="60">
        </div>
    </nav>
<?php  } 

function catalogacion($bdnom,$tabla){

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
$sql = "SELECT * FROM $tabla LIMIT $registrosPorPagina OFFSET $offset";
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
        header("Location: grilla.php?bd=$bdnom&tabla=$tabla");
        exit();
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }
}

if ($resultado->num_rows > 0) {
    echo "<table>";
    echo "<tr>";
    echo "<th>Libros</th>";
    echo"<th colspan=3>Acciones</th>";
    echo "</tr>"; // Termina la fila de las columnas

    $resultado->data_seek(0);// Vaciar la data
    // Mostrar los registros
    while ($fila = $resultado->fetch_assoc()) { 
        echo "<tr>";
        echo "<td>".$fila['Titulo']."</td>";
        $id = $fila[$columnaId];
        echo "<td><a href='detalle.php?bd=$bdnom&tabla=$tabla&id=$id'><img src='img/lupa.png'></a></td>";
        echo "</tr>";
    }
    echo "</table>";
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
<?php 
    // Agregar enlace a la página de alta con los valores de $tabla y $dbname en la URL
        echo "<a class='button1' href='alta.php?bd=$bdnom&tabla=$tabla'>Nuevo</a>";
// Cerrar la conexión
$conn->close(); } 




?>