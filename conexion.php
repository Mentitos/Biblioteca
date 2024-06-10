<body background="img/biblio2.jpg"> 
<?php
session_start(); //inicia sesión o reanuda una ya existente.

//son valores asignados
$usuario=$_POST['usuario'];
$contra=$_POST['contra'];

//Introducción a SQL SELECT par obtener los atos de la tabla
$consulta="SELECT * FROM usuarios WHERE usuario='$usuario' AND contraseña='$contra'"; 

//conecta con la basde de datos
$conexion=mysqli_connect('localhost','root','','biblioteca');

$result=mysqli_query($conexion, $consulta);//ejecuta $consulta en la conexion de base de datos establecida y asigna el resultado a la variable $result
$cant_filas=mysqli_num_rows($result); //devulve el número de filas en el conjunto de datos de $resultado
if($cant_filas>0){ //verifica si el número de consultas en el conjunto de datos $result es mayor que 0
$_SESSION['biblioteca']=$usuario;
$_SESSION['mostrar_alerta'] = true; 
header('location:principal.php');}//redirige a 'principal.php' si el resultado es mayor que 0
	
	//si el resultado es mayor que 0, se muestra un mensaje de error
	else{
	
	echo '<script language="javascript">alert("NOMBRE DE USUARIO O CONTRASEÑA INCORRECTO")</script>';
	header("refresh:1;url=index.php");
}
?>
</body>