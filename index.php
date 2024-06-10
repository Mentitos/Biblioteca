<?php // Incluir el código de formatos.php
include 'formatos.php';?>
<head>
		 <title>BIBLIOTECA Inicio </title><!-- Nombre de la página-->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie-edge">
    <link rel="stylesheet" href="css/login.css"><!-- Para entrelazar el estilo de la página -->
    <link rel="stylesheet" href="css/login2.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">

</head>

<body><!-- Cuerpo de la página -->
<br><br>

<div><!--División de la página -->
<form action="conexion.php"  method="POST" class="login">	<!--Define la ubicación donde se envían los datos que el formulario fue recopilando-->
<center><img src="img/logo.png" class="logo"></center><!-- Encuetra y centra la imagen -->
	<h2> iniciar sesion</h2><!-- Subtítulo-->
<input type="text" name="usuario" id="usuario" placeholder="&#128101; Usuario" class="usuario"/>
<br><br>
<input type="password" name="contra" id="contra" placeholder="&#128272; Contrase&ntilde;a" class="pass"/>
<!--Entrada de texto -->
<br><br>
<input type="submit" name="boton" value="ingresar"  class="submit" /><!--Botón -->
<br>
</form>
</div>
</body>
</html>
