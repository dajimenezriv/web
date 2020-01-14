<?php

	error_reporting(E_ERROR | E_PARSE);

	/*****************FUNCIONES LOCALES*****************/

	function convertirImagenes($contenido) {

		$contenidoFinal = "";

		$texto = explode("<img ", $contenido);
		$texto = array_filter($texto);
		$texto = array_slice($texto, 0);

		for ($i = 0; $i < count($texto); $i++) {

			$partes = explode(">", $texto[$i]);

			for ($z = 0; $z < count($partes); $z++) {

				if (substr($partes[$z], 0, 4) == "src=") {

					$ruta = str_replace("\"", "", $partes[$z]);
					$ruta = str_replace("'", "", $ruta);
					$ruta = str_replace("src=", "", $ruta);			

					$contenidoFinal .= "&inicio&" . $ruta . "&final&";

				} else if (count($partes) == 1) {

					$contenidoFinal .= $partes[$z];

				} else {

					if (strpbrk($partes[$z], "<")) { // Si la parte tiene el caracter < significa que se ha separado

						$contenidoFinal .= $partes[$z] . ">";

					} else {

						$contenidoFinal .= $partes[$z];

					}

				}

			}

		}

		return $contenidoFinal;

	}

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

				$imagen = explode("/", $source)[count(explode("/", $source)) - 1];
				$imagen = explode("-", $imagen)[count(explode("-", $imagen)) - 1];
				$imagen = $url . '-' . strtolower(str_replace('-', '_', str_replace(" ", "_", $imagen)));

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

	$idEntrada = $entrada -> getId();

	$tituloAnterior = $entrada -> getTitulo();
	$urlAnterior = $entrada -> getUrl();
	$imagenAnterior = $entrada -> getImagen();
	$textoAnterior = $entrada -> getTexto();
	$seccionAnterior = $entrada -> getSeccion();
	$activaAnterior = $entrada -> getActiva();

	$tituloNuevo = $tituloAnterior;
	$urlNueva = $urlAnterior;
	$imagenNueva = $imagenAnterior;
	$textoNuevo = convertirImagenes($textoAnterior);
	$seccionNueva = $seccionAnterior;
	$activaNueva = $activaAnterior;

	$errorEditarEntrada = '';

	$rutaEditarEntrada = SERVIDOR . 'editar-entrada/' . $urlEntrada;

	/*****************METODOS*****************/

	if (ControlSesion :: sesionIniciada()) {

		Conexion :: abrirConexion();
		
		$usuario = RepositorioUsuario :: getUsuarioPorNombre(Conexion :: getConexion(), $nombreUsuario);
		
		Conexion :: cerrarConexion();

		$autorId = $usuario -> getId();

		if (!($nombreUsuario === 'Zepovop')) {

			Redireccion :: redirigir('error');

		}

	} else {

		Redireccion :: redirigir('error');

	}

	if (isset($_POST['botonEditarEntrada'])) {

		$tituloNuevo = $_POST['inputTitulo'];
		$textoNuevo = $_POST['texto'];
		$seccionNueva = $_POST['seccion'];
		$imagenNueva = $_POST['inputImagen'];
		
		if ($_POST['inputUrl'] == '') {

			$urlNueva = strtolower(str_replace(' ', '-', $tituloNuevo));

		} else {

			$urlNueva = strtolower(str_replace(' ', '-', $_POST['inputUrl']));

		}

		$urlNueva = preg_replace('/\s+/', '-', $urlNueva);

		if (isset($_POST['inputActiva'])) {

			$activaNueva = 1;

		} else {

			$activaNueva = 0;

		}

		if ($errorEditarEntrada == '') {

			Conexion :: abrirConexion();

			$validacion = new ValidacionEditarEntrada(Conexion :: getConexion(), $imagenAnterior, $seccionAnterior, $tituloAnterior, $urlAnterior, $textoAnterior, $activaAnterior,
				$imagenNueva, $seccionNueva, $tituloNuevo, $urlNueva, $textoNuevo, $activaNueva);

			Conexion :: cerrarConexion();

			$errorEditarEntrada = $validacion -> getError();

			if ($errorEditarEntrada == '') {

				$tmpImagen = str_replace($urlAnterior, "", $imagenNueva);
				$imagenEntradaNueva = 'secciones/' . strtolower($seccionNueva) . '/imagenes/' . strtolower($urlNueva) . explode("/", $tmpImagen)[count(explode("/", $tmpImagen)) - 1];

				$errorEditarEntrada = copy($imagenNueva, $imagenEntradaNueva);

				if ($errorEditarEntrada) {

					$textoNuevo = comprobarImagenes($textoNuevo, $seccionNueva, $urlNueva); // Subimos las imagenes del texto

					Conexion :: abrirConexion();

					RepositorioEntrada :: editarEntrada(Conexion :: getConexion(), $idEntrada, $seccionNueva, $urlNueva, $tituloNuevo, 
						$imagenEntradaNueva, $textoNuevo, $activaNueva);

					Conexion :: cerrarConexion();

					Redireccion :: redirigir($rutaGestorPHP);

				} else {

					$errorEditarEntrada = "Error al copiar la imagen";

				}

			}

		}

	}

?>

		<div id="gestor">

			<h2 id="tituloFormulario">Editar Entrada</h2>

			<form id="formNuevaEntrada" method="post" enctype="multipart/form-data" action="<?php $rutaEditarEntrada; ?>">

				<?php

					if ($errorEditarEntrada != '') {

						echo '<div id="error">';
						echo "<p> $errorEditarEntrada </p>";
						echo '</div>';

					}

				?>
				
				<div>
					
					<select id="seccion" name="seccion">

						<?php

							if ($seccionAnterior == "Hacking") {

								?>

									<option>Hacking</option>
									<option>Proyectos</option>
									<option>General</option>

								<?php

							} else if ($seccionAnterior == "Proyectos"){

								?>
				
									<option>Proyectos</option>
									<option>Hacking</option>
									<option>General</option>

								<?php

							} else {

								?>

									<option>General</option>
									<option>Proyectos</option>
									<option>Hacking</option>

								<?php

							}

							?>

					</select>

					<label><input id="inputActiva" type="checkbox" name="inputActiva" checked>Pública</label>

				</div>

				<input id="inputTitulo" type="text" name="inputTitulo" placeholder="Titulo" value="<?php echo $tituloNuevo; ?>">
				<input id="inputUrl" type="text" name="inputUrl" placeholder="Url" value="<?php echo $urlNueva; ?>">
				<input id="inputImagen" type="text" name="inputImagen" placeholder="Imagen" value="<?php echo $imagenNueva; ?>">
				<input type="text" value="<?php echo '&inicio&/home/user/images/Imagen.png&final&' ?>" />
				
				<textarea id="inputTexto" name="texto"><?php echo htmlentities($textoNuevo); // &lt; &gt; ?></textarea>
				
				<button type="submit" name="botonEditarEntrada">CONFIRMAR</button>
			
			</form>

		</div>