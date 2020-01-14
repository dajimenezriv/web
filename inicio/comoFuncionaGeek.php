<?php

	/*****************VARIABLES LOCALES*****************/

	$activa = 1;

	Conexion :: abrirConexion();

	$entrada = RepositorioEntrada :: getEntradaPorUrl(Conexion :: getConexion(), 'como-funciona-geek', $activa);

	Conexion :: cerrarConexion();

?>

			<section id="main">

				<div id="titulo">

					<h2><?php echo nl2br($entrada -> getTitulo()); ?></h2>

				</div>

				<div id="contenido">

					<p><?php echo nl2br($entrada -> getTexto()); ?></p>

				</div>

			</section>