<?php
	
	/*****************METODOS*****************/

	if (isset($_POST['botonPaginaAnterior'])) {

		$pagina -= 1;

		Redireccion :: redirigir(SERVIDOR . 'hacking/' . $pagina);

	}

	if (isset($_POST['botonPaginaSiguiente'])) {

		$pagina += 1;

		Redireccion :: redirigir(SERVIDOR . 'hacking/' . $pagina);

	}

?>

		<section id="main">

			<div id="seccion">

				<h2>HACKING</h2>

			</div>

			<section id="articulos">

				<?php

					if (count($entradas)) {

						foreach ($entradas as $entrada) {

							$autorId = $entrada -> getAutorId();

							Conexion :: abrirConexion();

							$autor = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), $autorId);

							Conexion :: cerrarConexion();

							$nombreAutor = $autor -> getNombre();

							$rutaImagen = $entrada -> getImagen();
							$urlEntrada = SERVIDOR . 'entrada/' . $entrada -> getUrl();
							$tituloEntrada = $entrada -> getTitulo();
							$fechaEntrada = $entrada -> getFecha();

							Conexion :: abrirConexion();

							$textoEntrada =  nl2br(RepositorioEntrada :: resumirTexto($entrada -> getTexto()));

							Conexion :: cerrarConexion();

				?>
							
							<article>

								<div class="imagenArticulo">

									<a href=<?php echo $urlEntrada; ?>><img src=<?php echo $rutaImagen; ?>></a>

								</div>

								<div class="contenido">

									<h3><a href=<?php echo $urlEntrada; ?>>
									<span class="titulo"><?php echo $tituloEntrada; ?></span> por 
									<span class="otros"><?php echo $nombreAutor; ?></span></a></h3>

									<p><?php echo $textoEntrada; ?></p>

								</div>

							</article>	

				<?php

						}

					}

				?>				

			</section>

			<form id="paginador" method="post" action="<?php echo SERVIDOR . 'hacking/' . $pagina; ?>">

				<?php

					if ($pagina > 1) {

				?>

						<button type="sumbit" name="botonPaginaAnterior">Página Anterior</button>

				<?php

					}

					if (($pagina * 10) < count($todasEntradas)) {

				?>
				
				<button type="sumbit" name="botonPaginaSiguiente">Página Siguiente</button>

				<?php

					}

				?>

			</form>

		</section>