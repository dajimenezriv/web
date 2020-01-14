<?php

	/*****************VARIABLES LOCALES*****************/

	$entradaId = $entrada -> getId();
	$entradaAutorId = $entrada -> getAutorId();
	$tituloEntrada = $entrada -> getTitulo();

	Conexion :: abrirConexion();

	$entradaUsuario = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), $entradaAutorId);

	Conexion :: cerrarConexion(); 

	$errorComentario = '';
	$holderComentario = 'Escribe un Comentario para ayudar a que crezca la Web';
	$referenciaId = 0;
	$autofocus = '';

	$rutaEntrada = SERVIDOR . 'entrada/' . $entrada -> getUrl();

	$rutaImagenFavorito = SERVIDOR . 'imagenes/favorito.png';

	/*****************METODOS*****************/

	if (isset($_POST['botonNuevoComentario'])) {

		if (ControlSesion :: sesionIniciada()) {

			Conexion :: abrirConexion();

			$usuario = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), $_SESSION["idUsuario"]);

			Conexion :: cerrarConexion();

			if ($usuario -> getRegistrado()) {

				if ($usuario -> getActivo()) {

					$tituloComentario = $_POST['inputTitulo'];
					$texto = $_POST['texto'];
					$autorId = $_SESSION['idUsuario'];

					$tituloComentario = str_replace("<", "&lt;", $tituloComentario);
					$tituloComentario = str_replace(">", "&gt;", $tituloComentario);

					$texto = str_replace("<", "&lt;", $texto);
					$texto = str_replace(">", "&gt;", $texto);

					Conexion :: abrirConexion();
			
					$validacion = new ValidacionComentario(Conexion :: getConexion(), $tituloComentario, $texto);
		
					Conexion :: cerrarConexion();

					$errorComentario = $validacion -> getError();

					if ($errorComentario == '') {

						Conexion :: abrirConexion();

						if (isset($_POST['referenciaId'])) {
					
							$comentario = new Comentario('', $autorId, $entradaId, $tituloComentario, $texto, $_POST['referenciaId'], '');

						} else {

							$comentario = new Comentario('', $autorId, $entradaId, $tituloComentario, $texto, $referenciaId, '');

						}

						RepositorioComentario :: nuevoComentario(Conexion :: getConexion(), $comentario);
					
						Conexion :: cerrarConexion();

						//Redireccion :: redirigir($rutaEntrada);

					}

				} else {

					$autofocus = 'autofocus';

					$errorComentario = 'Tu cuenta esta baneada temporalmente';

				}

			} else {

				$autofocus = 'autofocus';

				$errorComentario = 'Debes Activar tu Cuenta mediante la confirmación por correo';

			}

		} else {

			//Redireccion :: redirigir($rutaRegistro);

		}

	}

	if (isset($_POST['botonResponderComentario'])) {

		$autofocus = 'autofocus';

		if (ControlSesion :: sesionIniciada()) {

			Conexion :: abrirConexion();

			$usuario = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), $_SESSION["idUsuario"]);

			Conexion :: cerrarConexion();

			if ($usuario -> getRegistrado()) {

				if ($usuario -> getActivo()) {

					$referenciaId = $_POST['idComentario'];

					Conexion :: abrirConexion();

					$tmpComentario = RepositorioComentario :: getComentarioPorId(Conexion :: getConexion(), $referenciaId);

					Conexion :: cerrarConexion();

					$holderComentario = 'Respuesta al Comentario: ' . $tmpComentario -> getTitulo();

				} else {

					$errorComentario = 'Tu cuenta esta baneada temporalmente';

				}

			} else {

				$errorComentario = 'Debes Activar tu Cuenta mediante la confirmación por correo';

			}

		} else {

			Redireccion :: redirigir($rutaRegistro);

		}

	}

	if (isset($_POST['botonEliminarComentario'])) {

		Conexion :: abrirConexion();

		RepositorioComentario :: eliminarComentariosPorReferenciaId(Conexion :: getConexion(), $_POST['idComentario']);

		RepositorioComentario :: eliminarComentarioPorId(Conexion :: getConexion(), $_POST['idComentario']);

		Conexion :: cerrarConexion();

		Redireccion :: redirigir($rutaEntrada);

	}

	if (isset($_POST['botonFavorito'])) {

		if (ControlSesion :: sesionIniciada()) {

			Conexion :: abrirConexion();

			if (!(RepositorioEntradaFavorita :: encontrarEntradaFavoritaPorUsuarioId(Conexion :: getConexion(), $_SESSION['idUsuario'], $_POST['idEntrada']))) {

				RepositorioEntradaFavorita :: nuevaEntradaFavorita(Conexion :: getConexion(), $_SESSION['idUsuario'], $_POST['idEntrada']);

			} else {

				RepositorioEntradaFavorita :: eliminarEntradaFavorita(Conexion :: getConexion(), $_SESSION['idUsuario'], $_POST['idEntrada']);

			}

			Conexion :: cerrarConexion();

			Redireccion :: redirigir($rutaEntrada);

		} else {

			Redireccion :: redirigir($rutaRegistro);

		}

	}

?>

		<section id="main">

			<div id="titulo">

				<h2><?php echo $entrada -> getTitulo(); ?></h2>

				<p>Escrito por 
				<span><?php echo $entradaUsuario -> getNombre(); ?></span> el 
				<span><?php echo $entrada -> getFecha(); ?></span></p>

				<?php

					if (ControlSesion :: sesionIniciada()) {

				?>
					
						<form method="post" action="<?php echo $rutaEntrada; ?>">

							<input type="hidden" name="idEntrada" value="<?php echo $entrada -> getId(); ?>">

							<?php

								Conexion :: abrirConexion();

								$entradaFavoritaEncontrada = RepositorioEntradaFavorita :: encontrarEntradaFavoritaPorUsuarioId(Conexion :: getConexion(), $_SESSION['idUsuario'], $entradaId);

								Conexion :: cerrarConexion();

								if ($entradaFavoritaEncontrada) {

									echo '<button type="submit" name="botonFavorito" id="botonFavorito"><img id="imagenFavorito" src="' . $rutaImagenFavorito  . '">  Eliminar Entrada de Favoritos</button>';

								} else {

									echo '<button type="submit" name="botonFavorito" id="botonFavorito"><img id="imagenFavorito" src="' . $rutaImagenFavorito  . '">    Añadir Entrada a Favoritos</button>';

								}

							?>

						</form>

				<?php

					}

				?>

			</div>

			<div id="contenido">

				<p><?php echo nl2br($entrada -> getTexto()); ?></p>

			</div>

			<div id="azar">

				<h2>Otras Entradas</h2>
				
				<?php
				
					$activa = 1;

					Conexion :: abrirConexion();
					
					$entradas = RepositorioEntrada :: getTresEntradasAleatorias(Conexion :: getConexion(), $activa);

					Conexion :: cerrarConexion();

					if (count($entradas)) {
					
						foreach ($entradas as $entradaAzar) {

							if ($entradaAzar -> getTitulo() != $tituloEntrada && $entradaAzar -> getUrl() != 'como-funciona-geek') {

								$rutaEntradaAzar = SERVIDOR . 'entrada/' . $entradaAzar -> getUrl();
								$tituloEntradaAzar = $entradaAzar -> getTitulo();
								$imagenEntradaAzar = $entradaAzar -> getImagen();

				?>

								<div class="entrada">

									<h3><a href=<?php echo $rutaEntradaAzar; ?>>
									<?php echo $tituloEntradaAzar; ?></a></h3>

									<img src=<?php echo $imagenEntradaAzar; ?>>

								</div>

				<?php

							}

						}

					}
				
				?>

			</div>

			<div id="comentarios">

				<?php

					if ($errorComentario != '') {

				?>

						<div id="error">

							<p><?php echo $errorComentario; ?></p>

						</div>

				<?php

					}
				
					Conexion :: abrirConexion();

					$comentarios = RepositorioComentario :: getComentariosPorEntradaId(Conexion :: getConexion(), $entradaId);

					Conexion :: cerrarConexion();

					if (count($comentarios)) {

						$numeroComentarios = count($comentarios);

				?>

						<h2 id="tituloComentarios">Comentarios (<?php echo $numeroComentarios; ?>)</h2>

						<form id="escribirComentario" method="post" action="<?php echo $rutaEntrada; ?>">

							<input type="text" name="inputTitulo" placeholder="TITULO" required <?php echo $autofocus; ?>>
							<input type="hidden" name="referenciaId" value="<?php echo $referenciaId; ?>">
							<textarea type="text" name="texto" placeholder="<?php echo $holderComentario; ?>" required></textarea>

							<button type="submit" name="botonNuevoComentario">CONFIRMAR</button>

						</form>

						<div id="comentariosEscritos">

				<?php

						foreach ($comentarios as $comentario) {

							/* COMENTARIO PRINCIPAL */

							$comentarioAutorId = $comentario -> getAutorId();
					
							Conexion :: abrirConexion();

							$comentarioUsuario = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), $comentarioAutorId);

							Conexion :: cerrarConexion();

				?>

							<div class="comentario">

								<h3 class="tituloComentario">
								<span class="titulo"><?php echo $comentario -> getTitulo(); ?></span> por 
								<span class="otros"><?php echo $comentarioUsuario -> getNombre(); ?></span> el 
								<span class="otros"><?php echo $comentario-> getFecha(); ?></span></h3>

								<p><?php echo nl2br($comentario -> getTexto()); ?></p>	

								<form method="post" action="<?php echo $rutaEntrada; ?>">

									<input type="hidden" name="idComentario" value="<?php echo $comentario -> getId(); ?>">

									<button type="submit" name="botonResponderComentario">Responder</button>

								<?php 

									if (ControlSesion :: sesionIniciada()) {

										if ($_SESSION['idUsuario'] == $comentarioAutorId) {

								?>

										<button type="submit" name="botonEliminarComentario">Eliminar</button>		

								<?php

										}

									}

								?>

								</form>

							</div>

							<?php

							/* RESPUESTAS */

							Conexion :: abrirConexion();

							$respuestas = RepositorioComentario :: getComentariosPorReferenciaId(Conexion :: getConexion(), $entradaId, $comentario -> getId());

							Conexion :: cerrarConexion();

							foreach ($respuestas as $respuesta) {

								$respuestaAutorId = $respuesta -> getAutorId();
				
								Conexion :: abrirConexion();

								$respuestaUsuario = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), $respuestaAutorId);

								Conexion :: cerrarConexion();

							?>

								<div class="respuesta">

									<h3 class="tituloComentario">
									<span class="titulo"><?php echo $respuesta -> getTitulo(); ?></span> por 
									<span class="otros"><?php echo $respuestaUsuario -> getNombre(); ?></span> el 
									<span class="otros"><?php echo $respuesta -> getFecha(); ?></span></h3>

									<p><?php echo nl2br($respuesta -> getTexto()); ?></p>	

									<form method="post" action="<?php echo $rutaEntrada; ?>">

										<input type="hidden" name="idComentario" value="<?php echo $respuesta -> getId(); ?>">

									<?php 

										if (ControlSesion :: sesionIniciada()) {

											if ($_SESSION['idUsuario'] == $respuestaAutorId) {

									?>

											<button type="submit" name="botonEliminarComentario">Eliminar</button>		

									<?php

											}

										}

									?>

									</form>

								</div>

							<?php

							}

						}

				?>

						</div>

				<?php

					} else {

				?>
						
						<h2>No hay Comentarios</h2>	

						<form id="escribirComentario" method="post" action="<?php echo $rutaEntrada; ?>">

							<input type="text" name="inputTitulo" placeholder="TITULO" required <?php echo $autofocus; ?>>
							<textarea type="text" name="texto" placeholder="Escribe un Comentario para ayudar a que crezca la Web. Se el primero en comentar" required></textarea>

							<button type="submit" name="botonNuevoComentario">CONFIRMAR</button>

						</form>

				<?php

					}

				?>

				<script type="text/javascript" src="<?php echo $rutaComentariosJS; ?>"></script>

			</div>

		</section>