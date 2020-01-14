<?php

	error_reporting(E_ERROR | E_PARSE);

	/*****************FUNCIONES LOCALES*****************/

	function comprobarImagenes($contenido, $seccion, $url) {

		$contenidoFinal = "";

		$texto = explode("&inicio&", $contenido);
		$texto = array_filter($texto);
		$texto = array_slice($texto, 0);

		for ($i = 0; $i < count($texto); $i++) {

			$imagen = explode("&final&", $texto[$i]);

			if (count($imagen) == 2) {

				$source = $imagen[0];
				$resto = $imagen[1];

				$source_tmp = explode("/", $source)[count(explode("/", $source)) - 1];
				$source_tmp = str_replace(" ", "_", $source_tmp);
				$source_tmp = str_replace("-", "_", $source_tmp);

				$imagen = $url . '-' . strtolower($source_tmp);

				copy($source, 'secciones/' . strtolower($seccion) . '/imagenes/' .  $imagen);

				$contenidoFinal .= '<img src="' . SERVIDOR . "secciones/" . strtolower($seccion) . "/imagenes/" . $imagen . '">';

				$contenidoFinal .= $resto;

			} else {

				$contenidoFinal .= $imagen[0];

			}

		}

		return $contenidoFinal;

	}

	/*****************VARIABLES LOCALES*****************/

	$seccionEntradaNueva = '';
	$activaEntradaNueva = '';
	$tituloEntradaNueva = '';
	$urlEntradaNueva = '';
	$imagenEntradaNueva = '';
	$rutaImagen = "";
	$textoEntradaNueva = '';

	$errorEntradaNueva = '';

	/*****************METODOS*****************/

	if (ControlSesion :: sesionIniciada()) {

		Conexion :: abrirConexion();
		
		$usuario = RepositorioUsuario :: getUsuarioPorNombre(Conexion :: getConexion(), $_SESSION['nombreUsuario']);
		
		Conexion :: cerrarConexion();

		$autorId = $usuario -> getId();

		if (!($_SESSION['idUsuario'] === '1')) {

			Redireccion :: redirigir('error');

		}

	} else {

		Redireccion :: redirigir('error');

	}

	if (isset($_POST['botonNuevaEntrada'])) {

		$seccionEntradaNueva = $_POST['seccion'];
		$tituloEntradaNueva = $_POST['inputTitulo'];
		$textoEntradaNueva = $_POST['texto'];
		$rutaImagen = $_POST['inputImagen'];

		if ($_POST['inputUrl'] == '') {

			$urlEntradaNueva = strtolower(str_replace(' ', '-', $tituloEntradaNueva));

		} else {

			$urlEntradaNueva = strtolower(str_replace(' ', '-', $_POST['inputUrl']));

		}

		$charset='ISO-8859-1';
		$urlEntradaNueva = preg_replace('/\s+/', '-', $urlEntradaNueva);
		$urlEntradaNueva = iconv($charset, 'ASCII//TRANSLIT', $urlEntradaNueva);

		$imagen = strtolower($urlEntradaNueva . "-" . str_replace('-', '_', str_replace(' ', '_', explode("/", $rutaImagen)[count(explode("/", $rutaImagen)) - 1])));
		$imagenEntradaNueva = SERVIDOR . 'secciones/' . strtolower($seccionEntradaNueva) . '/imagenes/' . $imagen;

		if (isset($_POST['inputActiva'])) {

			$activaEntradaNueva = 1;

		} else {

			$activaEntradaNueva = 0;

		}

		Conexion :: abrirConexion();

		$validacion = new ValidacionNuevaEntrada(Conexion :: getConexion(), $imagen, $tituloEntradaNueva, $urlEntradaNueva, $textoEntradaNueva);
	
		Conexion :: cerrarConexion();

		$errorEntradaNueva = $validacion -> getError();

		if ($errorEntradaNueva == '') {

			$errorEntradaNueva = copy($rutaImagen, 'secciones/' . strtolower($seccionEntradaNueva) . '/imagenes/' .  $imagen);
	
			if ($errorEntradaNueva) {

				$textoEntradaNueva = comprobarImagenes($textoEntradaNueva, $seccionEntradaNueva, $urlEntradaNueva); // Subimos las imagenes del texto

				Conexion :: abrirConexion();

				$entrada = new Entrada('', $autorId, $urlEntradaNueva, $seccionEntradaNueva, $imagenEntradaNueva, $tituloEntradaNueva, $textoEntradaNueva, '', $activaEntradaNueva);
			
				RepositorioEntrada :: nuevaEntrada(Conexion :: getConexion(), $entrada);

				Conexion :: cerrarConexion();

				$seccionEntradaNueva = '';
				$activaEntradaNueva = '';
				$tituloEntradaNueva = '';
				$urlEntradaNueva = '';
				$imagenEntradaNueva = '';
				$rutaImagen = "";
				$textoEntradaNueva = '';

				$errorEntradaNueva = '';

				Redireccion :: redirigir($rutaGestorPHP);

			} else {

				$errorEntradaNueva = "Error al copiar la imagen";

			}

		}	

	}

	if (isset($_POST['botonEditarEntrada'])) {

		$activa = 0;

		Conexion :: abrirConexion();

		$entradaPulsada = RepositorioEntrada :: getEntradaPorId(Conexion :: getConexion(), $_POST['idEntrada'], $activa);

		Conexion :: cerrarConexion();

		$rutaEditarEntrada = 'editar-entrada/' . $entradaPulsada -> getUrl();

		Redireccion :: redirigir($rutaEditarEntrada);

	}

	
	if (isset($_POST['botonBorrarEntrada'])) {

		$activa = 0;

		Conexion :: abrirConexion();

		RepositorioEntrada :: borrarEntrada(Conexion :: getConexion(), $_POST['idEntrada']);

		Conexion :: cerrarConexion();

		Redireccion :: redirigir($rutaGestorPHP);

	}

	if (isset($_GET['botonExtras'])) {

		$advertencia = $_GET['inputAdvertencia'];
		$consejo = $_GET['inputConsejo'];
		$sabiasQue = $_GET['inputSabiasQue'];

		$extra = new Extra('', $advertencia, $consejo, $sabiasQue, '');

		Conexion :: abrirConexion();

		RepositorioExtra :: nuevoExtra(Conexion :: getConexion(), $extra);

		Conexion :: cerrarConexion();

	}

?>

		<div id="menu">

			<ul>

				<li id="entradas">Entradas</li>
				<li id="nuevaEntrada">Nueva Entrada</li>
				<li id="extras">Extras</li>

			</ul>

		</div>

		<div id="gestor">

			<h2 id="tituloFormulario">Nueva Entrada</h2>

			<div id="listaEntradas">

				<?php

					$activa = 0;

					Conexion :: abrirConexion();
					
					$entradas = RepositorioEntrada :: getEntradasPorAutorId(Conexion :: getConexion(), $autorId, $activa);

					Conexion :: cerrarConexion();

					if (count($entradas)) {

						foreach ($entradas as $entrada) {

							$entradaActiva = $entrada -> getActiva();
							$urlEntrada = SERVIDOR . 'entrada/' . $entrada -> getUrl();
							$tituloEntrada = $entrada -> getTitulo();
							$fechaEntrada = $entrada -> getFecha();
							$seccionEntrada = $entrada -> getSeccion();

				?>
							
							<article>
								
								<div class="contenido">

									<h3>

				<?php 

										if ($entradaActiva) {

											echo '<a href="' . $urlEntrada . '">'; 

										}

				?>

									<span class="titulo"><?php echo $tituloEntrada; ?></span> el 
									<span class="otros"><?php echo $fechaEntrada; ?></span>. 

				<?php 

										if ($entradaActiva) {

											echo '<span class="otros">Pública</span>';
											echo '</a>'; 

										} else {

											echo '<span class="otros">Privada</span>';

										}

									?>

									</h3>

								</div>

								<div class="opciones">

									<form method="post">

										<input type="hidden" name="idEntrada" value="<?php echo $entrada -> getId(); ?>">

										<button type="submit" name="botonEditarEntrada">Editar</button>

										<input type="hidden" name="idEntrada" value="<?php echo $entrada -> getId(); ?>">
									
										<button type="submit" name="botonBorrarEntrada">Borrar</button>
									
									</form>
							
								</div>
							
							</article>	

				<?php
							
						}

					}

				?>

			</div>

			<form id="formNuevaEntrada" method="post" enctype="multipart/form-data" action="<?php $rutaGestorPHP; ?>">

				<?php

					if ($errorEntradaNueva != '') {

						echo '<div id="error">';
						echo "<p> $errorEntradaNueva </p>";
						echo '</div>';

					}

				?>
				
				<div>
					
					<select id="seccion" name="seccion">
					
						<option>Hacking</option>
						<option>Proyectos</option>
						<option>General</option>

					</select>

					<label><input id="inputActiva" type="checkbox" name="inputActiva" checked>Pública</label>

				</div>

				<input id="inputTitulo" type="text" name="inputTitulo" placeholder="Titulo" value="<?php echo $tituloEntradaNueva; ?>">
				<input id="inputUrl" type="text" name="inputUrl" placeholder="Url" value="<?php echo $urlEntradaNueva; ?>">
				<input id="inputImagen" type="text" name="inputImagen" placeholder="Imagen" value="<?php echo $rutaImagen; ?>">
				<input type="text" value="<?php echo '&inicio&/home/user/images/Imagen.png&final&' ?>" />
				
				<textarea id="inputTexto" name="texto" contenteditable="true"><?php echo $textoEntradaNueva; ?></textarea>
				
				<button type="submit" name="botonNuevaEntrada">CONFIRMAR</button>
			
			</form>
				
			<form id="formExtras" method="get" action="<?php $rutaGestorPHP; ?>">

				<input id="inputAdvertencia" type="text" name="inputAdvertencia" placeholder="ADVERTENCIA" required>
				<input id="inputConsejo" type="text" name="inputConsejo" placeholder="CONSEJO" required>
				<input id="inputSabiasQue" type="text" name="inputSabiasQue" placeholder="SABÍAS QUE" required>
				
				<button type="submit" name="botonExtras">CONFIRMAR</button>

			</form>

		</div>

		<script type="text/javascript" src="<?php echo $rutaGestorJS; ?>"></script>