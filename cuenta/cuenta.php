<?php

	/*****************VARIABLES LOCALES*****************/

	Conexion :: abrirConexion();

	$usuario = RepositorioUsuario :: getUsuarioPorNombre(Conexion :: getConexion(), $nombreUsuario);

	Conexion :: cerrarConexion();

	$emailUsuario = $usuario -> getEmail();
	$usuarioFechaRegistro = $usuario -> getFechaRegistro();
	$usuarioActivo = $usuario -> getActivo();

	if ($usuario -> getRegistrado()) {

		if (!$usuario -> getActivo()) {

			$mensajeError = 'Tu cuenta esta baneada temporalmente. No podrás comentar en entradas.';

		} else {

			$mensajeError = '';

		}

	} else {

		$mensajeError = 'Activa la Cuenta desde tu Mail';

	}

	/*****************METODOS*****************/

	function stringAleatorio($longitud) {

		$caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numeroCaracteres = strlen($caracteres);
		$stringAleatorio = '';

		for ($i = 0; $i < $longitud; $i++) {

			$stringAleatorio .= $caracteres[rand(0, $numeroCaracteres - 1)]; 

		}

		return $stringAleatorio;

	}

	if (isset($_POST['botonActivacion'])) {

		$stringAleatorio = stringAleatorio(20);

		$urlSecreta = hash('sha256', $stringAleatorio . $nombreUsuario);

		Conexion :: abrirConexion();

		$usuario = RepositorioUsuario :: getUsuarioPorNombre(Conexion :: getConexion(), $nombreUsuario);

		RepositorioRecuperacion :: eliminarActivacionesPorUsuarioId(Conexion :: getConexion(), $idUsuario);

		RepositorioRecuperacion :: nuevaActivacion(Conexion :: getConexion(), $idUsuario, $urlSecreta);

		Conexion :: cerrarConexion();

		$correo = $usuario -> getEmail();

		$url = SERVIDOR . 'activacion/' . $urlSecreta;

		mail($correo, 'Activacion de Cuenta en GeekZepovop', $url);

	}

?>

		<h2 id="tituloPerfil">Perfil de <?php echo $_SESSION["nombreUsuario"]; ?></h2>

		<div id="fondo">

			<div id="informacionPerfil">

				<img src="<?php echo $rutaImagenWeb; ?>">

				<div id="informacion">

					<p>Nombre: <span><?php echo $nombreUsuario; ?></span></p>
					<p>Email: <span><?php echo $emailUsuario; ?></span></p>
					<p>Fecha Registro: <span><?php echo $usuarioFechaRegistro; ?></span></p>
					<p><span>

						<?php

							if ($mensajeError != '') {

								echo $mensajeError;

							} else {

								echo 'Cuenta Activada';

							}

						?>
							
					</span></p>

				</div>

			</div>

				<form class="botonOpcion" method="post" action="<?php echo SERVIDOR . 'eliminar-cuenta'; ?>">

					<button type="submit" name="botonActivacion">Eliminar Cuenta</button>

				</form>

			<?php

				if (!$usuario -> getRegistrado()) {

			?>

					<form class="botonOpcion" id="formActivacion" method="post" action="">

						<button type="submit" name="botonActivacion">Volver a Enviar Mail de Activación</button>

					</form>

			<?php

				}

			?>

		</div>

		<?php 

			Conexion :: abrirConexion();

			$comentarios = RepositorioComentario :: getComentariosPorAutorId(Conexion :: getConexion(), $idUsuario);
			$entradasFavoritas = RepositorioEntradaFavorita :: getEntradasFavoritasPorUsuarioId(Conexion :: getConexion(), $idUsuario);

			Conexion :: cerrarConexion();	

			$numeroComentarios = count($comentarios);
			$numeroEntradasFavoritas = count($entradasFavoritas);

		?>

		<div class="apartado">	

			<?php

				if ($numeroComentarios == 0) {

			?>

					<h2 id="tituloComentarios">No hay Comentarios</h2>

			<?php

				} else {

			?>

					<h2 id="tituloComentarios">Comentarios Escritos (<?php echo $numeroComentarios; ?>)</h2>

			<?php

				}

			?>

		</div>

		<div class="apartado entradas">

			<?php

				if ($numeroEntradasFavoritas == 0) {

			?>

					<h2 id="tituloEntradasFavoritas">No hay Entradas Favoritas</h2>

			<?php

				} else {

			?>

					<h2 id="tituloEntradasFavoritas">Entradas Favoritas (<?php echo $numeroEntradasFavoritas; ?>)</h2>

			<?php

				}

			?>
			
		</div>

		<div class="apartado cerrarSesion">
	
			<h2><a href="<?php echo $rutaCerrarSesion; ?>">Cerrar Sesión</a></h2>
	
		</div>

			<?php
		
				if (count($comentarios)) {

			?>

					<div class="contenidos" id="comentariosEscritos">

			<?php


					foreach ($comentarios as $comentario) {

						$autorId = $comentario -> getAutorId();
				
						$usuario = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), $autorId);
						
						$tituloComentario = $comentario -> getTitulo();
						$fechaComentario = $comentario-> getFecha();
						$idEntrada = $comentario -> getEntradaId();

						$activa = 1;

						Conexion :: abrirConexion();

						$entrada = RepositorioEntrada :: getEntradaPorId(Conexion :: getConexion(), $idEntrada, $activa);

						Conexion :: cerrarConexion();

						$entradaTitulo = $entrada -> getTitulo();
						$entradaUrl = $entrada -> getUrl();

						$rutaEntrada = SERVIDOR . 'entrada/' . $entradaUrl;

			?>

						<div class="contenido">
	
							<h3 class="titulo"><a href="<?php echo $rutaEntrada; ?>">
							<span class="titulo2"><?php echo $tituloComentario; ?></span> en la entrada 
							<span class="otros"><?php echo $entradaTitulo; ?></span> el 
							<span class="otros"><?php echo $fechaComentario; ?></span></a></h3>	
	
						</div>

			<?php
					}

			?>

					</div>

			<?php

				}
		
				if (count($entradasFavoritas)) {

			?>

					<div class="contenidos" id="entradasFavoritas">

			<?php


					foreach ($entradasFavoritas as $entradaFavorita) {

						$activa = 1;

						$fechaEntradaFavorita = $entradaFavorita -> getFecha();

						Conexion :: abrirConexion();

						$entrada = RepositorioEntrada :: getEntradaPorId(Conexion :: getConexion(), $entradaFavorita -> getEntradaId(), $activa);

						Conexion :: cerrarConexion();

						$entradaTitulo = $entrada -> getTitulo();
						$entradaUrl = $entrada -> getUrl();

						$rutaEntrada = SERVIDOR . 'entrada/' . $entradaUrl;

			?>

						<div class="contenido">
	
							<h3 class="titulo"><a href="<?php echo $rutaEntrada; ?>">
							<span class="titulo3"><?php echo $entradaTitulo; ?></span> el
							<span class="otros"><?php echo $fechaEntradaFavorita; ?></span></a></h3>
		
						</div>

			<?php
					}

			?>

					</div>

			<?php

				}
		
		?>

		<script type="text/javascript" src="<?php echo $rutaComentariosJS; ?>"></script>