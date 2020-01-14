<!DOCTYPE html>

<html lang="es">

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<meta name="author" content="Daniel Jiménez Rivera">

		<?php

			echo '<title>' . $titulo . '</title>';

		?>

		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $rutaFavicon; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $rutaDefectoCSS; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $rutaEstilosCSS ?>">

	</head>

	<body>

		<header>
		
			<div id="navSuperior">

				<div id="navIzquierdo">

					<div id="tituloWeb">

						<a href="<?php echo $rutaInicio; ?>"><h1>Geek</h1></a>

					</div>

				</div>

				<div id="navDerecho">

					<div id="registro">

						<a href="<?php echo $rutaRegistro; ?>"><img src="<?php echo $rutaImagenRegistro; ?>"></a>
					
						<?php
						
							if (ControlSesion :: sesionIniciada()) {
					
								echo '<h2>' . $nombreUsuario . '</h2>';
						
							} else {

								echo '<h2><a href="' . $rutaRegistro . '">Registro</a></h2>';

							}
						
						?>
					
					</div>
					
					<?php
					
						if (!isset($busqueda)) {

					?>
					
						<form id="busqueda" method="get" action="<?php 
					
							if (isset($_GET['search'])) {

								if ($_GET['search'] != '') {

									$rutaBuscador = SERVIDOR . 'busqueda/' . $_GET['search'];

									Redireccion :: redirigir($rutaBuscador);

								}

							}

						?>">

							<input id="search" name="search" type="search" placeholder="BÚSQUEDA" value="<?php echo $textoBuscado; ?>">

						</form>		
					
					<?php

						}
					
					?>			
				
				</div>

			</div>
			
			<nav id="navegador">

				<ul>

					<li><a href="<?php echo $rutaHacking; ?>">HACKING</a></li>
					<li><a href="<?php echo $rutaProyectos; ?>">PROYECTOS</a>

				</ul>

			</nav>
		
		</header>