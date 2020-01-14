<?php	

	/*****************VARIABLES LOCALES*****************/

	$imagenInicio = SERVIDOR . 'imagenes/imagenInicio.jpg';

	$advertencia = '';
	$consejo = '';
	$sabiasQue = '';

	Conexion :: abrirConexion();

	$extra = RepositorioExtra :: getExtraNuevo(Conexion :: getConexion());

	Conexion :: cerrarConexion();

	if ($extra != null) {

		$advertencia = ' - ' . $extra -> getAdvertencia();
		$consejo = ' - ' . $extra -> getConsejo();
		$sabiasQue = ' ' . $extra -> getSabiasQue();

	}

	$tituloContacto = '';
	$textoContacto = '';

	$errorContacto = '';

	/*****************METODOS*****************/

	if (isset($_GET['enviar'])) {

		if (ControlSesion :: sesionIniciada()) {

			$tituloContacto = $_GET['titulo'];
			$textoContacto = $_GET['mensaje'];

			Conexion :: abrirConexion();

			$validacion = new ValidacionContacto(Conexion :: getConexion(), $tituloContacto, $textoContacto);

			Conexion :: cerrarConexion();

			$errorContacto = $validacion -> getError();

			if ($errorContacto == '') {

				Conexion :: abrirConexion();

				$admin = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), 1);

				$correo = $admin -> getEmail();

				$usuario = RepositorioUsuario :: getUsuarioPorNombre(Conexion :: getConexion(), $nombreUsuario);

				Conexion :: cerrarConexion();

				$textoContacto = 'Nombre: ' . $nombreUsuario . '<br>Id Usuario: ' . $idUsuario . '<br>Correo: ' . $usuario -> getEmail() . '<br>Contenido: ' . $textoContacto;

				if(mail($correo, $tituloContacto, $textoContacto)) {

					$errorContacto = 'Email Enviado Correctamente';

				} else {

					$errorContacto = 'Error al Enviar el Mail';

				}

				$textoContacto = $_GET['mensaje'];

			}

		} else {

			$errorContacto = "Debes registrarte o iniciar sesión.";

		}

	}

?>

		<section id="articulos">
			
			<article id="imagenInicio">

				<a href="<?php echo $rutaComoFuncionaGeek; ?>"><img src="<?php echo $imagenInicio; ?>"></a>

			</article>
			
			<article id="informacion">

				<h2>INFORMACIÓN</h2>

				<p>Buenas, mi nombre es Daniel Jiménez, tengo 17 años y actualmente soy estudiante de segundo de bachillerato. Me encanta el mundo de las tecnologías y he
					montado esta pequeña web para ir aprendiendo yo y todos los que queráis uniros. Esperos que disfrutéis mucho. ¡Un saludo!.</p>

			</article>

			<article id="ultimosArticulos">

				<?php 

					$activa = 1;

					Conexion :: abrirConexion();

					$entradas = RepositorioEntrada :: getTresEntradasPorFechaDescendiente(Conexion :: getConexion(), $activa);

					Conexion :: cerrarConexion();

					if (count($entradas)) {

						$contador = 1;

						foreach ($entradas as $entrada) {
						
							$rutaEntrada = SERVIDOR . 'entrada/' . $entrada -> getUrl();
							$rutaImagenEntrada = $entrada -> getImagen();
							$tituloEntrada = $entrada -> getTitulo();

				?>

							<div class="articuloNuevo" id="<?php echo 'articuloNuevo' . $contador; ?>">

				<?php 		

							if (($contador/2) == 1) {

				?>

								<div>

									<h2 id="tituloEntrada"><a href="<?php echo $rutaEntrada; ?>"><?php echo $tituloEntrada; ?></a></h2>
								
								</div>	

								<a href="<?php echo $rutaEntrada; ?>"><img src="<?php echo $rutaImagenEntrada; ?>"></a>

				<?php

							} else {

				?>

								<a href="<?php echo $rutaEntrada; ?>"><img src="<?php echo $rutaImagenEntrada; ?>"></a>

								<div>

									<h2 id="tituloEntrada"><a href="<?php echo $rutaEntrada; ?>"><?php echo $tituloEntrada; ?></a></h2>
								
								</div>	

				<?php									

							}

				?>

							</div>

				<?php

							$contador++;

						}

					}

				?>

			</article>

			<article id="extra">

				<div id="advertencia">

					<h2><?php echo 'ADVERTENCIA' . $advertencia; ?></h2>

				</div>

				<div id="consejo">

					<h2><?php echo 'CONSEJO' . $consejo; ?></h2>

				</div>

				<div id="sabiasQue">

					<h2><?php echo 'SABÍAS QUE...' . $sabiasQue; ?></h2>

				</div>

			</article>

			<article id="contacto">

				<h2>CONTACTO</h2>

				<form id="formContacto" method="get" action="<?php echo $rutaInicio; ?>">

					<?php

						if ($errorContacto != '') {

							echo '<div id="error">';
							echo "<p> $errorContacto </p>";
							echo '</div>';

						}

					?>
				
					<input type="text" placeholder="Título" name="titulo" id="titulo" value="<?php echo $tituloContacto; ?>" required>
					<textarea placeholder="Mensaje" name="mensaje" id="mensaje" required><?php echo $textoContacto; ?></textarea>

					<input type="submit" value="Enviar" id="enviar" name="enviar">

				</form>

			</article>

		</section>
