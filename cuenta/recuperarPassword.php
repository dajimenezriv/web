<?php

	/*****************VARIABLES LOCALES*****************/

	$errorFormulario = '';

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

	if (isset($_POST['boton'])) {

		$mail = $_POST['mail'];

		Conexion :: abrirConexion();

		$usuario = RepositorioUsuario :: getUsuarioPorEmail(Conexion :: getConexion(), $mail);

		Conexion :: cerrarConexion();

		if ($usuario != null) {

			$nombreUsuario = $usuario -> getNombre();
			$idUsuario = $usuario -> getId();

			Conexion :: abrirConexion();

			RepositorioRecuperacion :: eliminarRecuperacionesPasswordPorUsuarioId(Conexion :: getConexion(), $idUsuario);

			Conexion :: cerrarConexion();

			$stringAleatorio = stringAleatorio(20);

			$urlSecreta = hash('sha256', $stringAleatorio . $nombreUsuario);

			Conexion :: abrirConexion();

			$recuperacionClaveGenerada = RepositorioRecuperacion :: nuevaRecuperacionPassword(Conexion :: getConexion(), $idUsuario, $urlSecreta);

			Conexion :: cerrarConexion();
			
			if ($recuperacionClaveGenerada) {

				$url = SERVIDOR . 'password/' . $urlSecreta;

				if(mail($mail, 'Recuperacion Contraseña en GeekZepovop', $url)) {

					$errorFormulario = '¡Petición Enviada! Entra a tu mail para cambiar la Contraseña';

				} else {

					$errorFormulario = 'Ha habido un error al enviar la Peticion';

				}

			} else {

				$errorFormulario = 'Ha habido un error al enviar la Peticion';

			}

		} else {

			$errorFormulario = 'No existe ningún usuario con ese correo';

		}

	}

?>

		<div id="recuperarPassword">

			<h2>Recuperar Contraseña</h2>

			<form method="post" action="<?php echo $rutaRecuperarPassword; ?>">

				<div>

					<?php 

						if ($errorFormulario != '') {

							echo '<div id="errorFormulario">';
						 	echo "<p> $errorFormulario </p>";
						 	echo '</div>';

						}
					
					?>
		
					<label for="mail">Mail:</label>
					<input type="text" name="mail" placeholder="Mail" required>
		
				</div>
		
				<button type="submit" name="boton">Confirmar</button>

			</form>

		</div>
