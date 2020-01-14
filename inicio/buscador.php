<?php

	$activa = 1;

	Conexion :: abrirConexion();

	$entradas = RepositorioEntrada :: getEntradasPorBusqueda(Conexion :: getConexion(), $textoBuscado, $activa);

	$numeroResultados = count($entradas);

	if ($numeroResultados) {

		foreach ($entradas as $entrada) {

			if ($entrada -> getUrl() == 'como-funciona-geek') {

				$numeroResultados -= 1;

			}

		}

?>

			<h2 id="titulo">Se han encontrado <?php echo $numeroResultados; ?> resultados</h2>

<?php

			foreach ($entradas as $entrada) {

				if ($entrada -> getUrl() != 'como-funciona-geek') {

					if (isset($entrada)) {

						$tituloEntrada = $entrada -> getTitulo();
						$fechaEntrada = $entrada-> getFecha();
						$entradaUrl = $entrada -> getUrl();

						$rutaEntrada = SERVIDOR . 'entrada/' . $entradaUrl;

?>

						<div class="entrada">

							<h3><a href="<?php echo $rutaEntrada; ?>">
							<span class="titulo2"><?php echo $tituloEntrada; ?></span> el 
							<span class="otros"><?php echo $fechaEntrada; ?></span></a></h3>

						</div>

<?php

					}

				}

			}

		} else {

?>

			<h2 id="titulo">No se han encontrado resultados</h2>

<?php

		}

	Conexion :: cerrarConexion();

?>