<?php

	/*****************VARIABLES LOCALES*****************/

	$nombreRegistro = '';
	$emailRegistro = '';

	$errorNombreRegistro = '';
	$errorEmailRegistro = '';
	$errorPassword1 = '';
	$errorPassword2 = '';

	$nombreOEmailLogin = '';

	$errorLogin = '';

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

	if (ControlSesion :: sesionIniciada()) {

		$rutaPerfil = SERVIDOR . 'perfil/';

		Redireccion :: redirigir($rutaPerfil);

	}

	if (isset($_POST['botonR'])) {

		Conexion :: abrirConexion();

		$validacion = new ValidacionRegistro($_POST['nombreR'], $_POST['emailR'], $_POST['password1R'], $_POST['password2R'], Conexion :: getConexion());

		Conexion :: cerrarConexion();

		$nombreRegistro = $validacion -> getNombre();
		$emailRegistro = $validacion -> getEmail();

		$errorNombreRegistro = $validacion -> getErrorNombre();
		$errorEmailRegistro = $validacion -> getErrorEmail();
		$errorPassword1 = $validacion -> getErrorPassword1();
		$errorPassword2 = $validacion -> getErrorPassword2();

		if ($validacion -> registroValido()) {

			$hashPasswordUsuario = password_hash($validacion -> getPassword(), PASSWORD_DEFAULT);

			$usuario = new Usuario('', $nombreRegistro, $emailRegistro, $hashPasswordUsuario, '', '', '');

			Conexion :: abrirConexion();

			$usuarioInsertado = RepositorioUsuario :: nuevoUsuario(Conexion :: getConexion(), $usuario);

			Conexion :: cerrarConexion();

			if ($usuarioInsertado) {

				$stringAleatorio = stringAleatorio(20);

				$urlSecreta = hash('sha256', $stringAleatorio . $usuario -> getNombre());

				Conexion :: abrirConexion();

				$usuario = RepositorioUsuario :: getUsuarioPorNombre(Conexion :: getConexion(), $usuario -> getNombre());

				RepositorioRecuperacion :: nuevaActivacion(Conexion :: getConexion(), $usuario -> getId(), $urlSecreta);

				Conexion :: cerrarConexion();

				$correo = $usuario -> getEmail();

				$url = SERVIDOR . 'activacion/' . $urlSecreta;

				mail($correo, 'Activacion de Cuenta en GeekZepovop', $url);

				ControlSesion :: iniciarSesion($usuario -> getId(), $usuario -> getNombre());

				$rutaRegistroCorrecto = SERVIDOR . 'registro-correcto/';

				Redireccion :: redirigir($rutaRegistroCorrecto);

			}

		}

	}

	if (isset($_POST['botonI'])) {

		Conexion :: abrirConexion();

		$validacion = new ValidacionLogin($_POST['nombreI'], $_POST['passwordI'], Conexion :: getConexion());

		Conexion :: cerrarConexion();

		$nombreOEmailLogin = $validacion -> getNombreOEmail();

		$errorLogin = $validacion -> getError();

		if($validacion -> getError() === '' && !is_null($validacion -> getUsuario())) {

			$idUsuario = $validacion -> getUsuario() -> getId();
			$rutaPerfil = SERVIDOR . 'perfil/';

			ControlSesion :: iniciarSesion($idUsuario, $validacion -> getUsuario() -> getNombre());

			Redireccion :: redirigir($rutaPerfil);

		}

	}

?>

		<div id="formulario">

			<div id="registrarse">

				<h2>Registrarse</h2>

				<form method="post" action="<?php echo $rutaRegistro; ?>">

					<div>
				
						<label for="nombreR">Nombre de Usuario:</label>
						<input type="text" name="nombreR" placeholder="Entre 4 - 25 caracteres" value="<?php echo str_replace("\"", "", $nombreRegistro); ?>" required autofocus>
						
						<?php 

							if ($errorNombreRegistro != '') {

								echo '<div class="errorRegistro">';
							 	echo "<p> $errorNombreRegistro </p>";
							 	echo '</div>';

							}
						
						?>

					</div>
				
					<div>
				
						<label for="emailR">Email:</label>
						<input type="email" name="emailR" placeholder="Máximo 255 caracteres" value="<?php echo $emailRegistro; ?>" required>
				
						<?php 

							if ($errorEmailRegistro != '') {

								echo '<div class="errorRegistro">';
							 	echo "<p> $errorEmailRegistro </p>";
							 	echo '</div>';

							}
						
						?>

					</div>
				
					<div>
				
						<label for="password1R">Contraseña:</label>
						<input type="password" name="password1R" placeholder="Máximo 255 caracteres" required>
				
						<?php 

							if ($errorPassword1 != '') {

								echo '<div class="errorRegistro">';
							 	echo "<p> $errorPassword1 </p>";
							 	echo '</div>';

							}
						
						?>

					</div>
				
					<div>
				
						<label for="password2R">Repetir Contraseña:</label>
						<input type="password" name="password2R" required>
				
						<?php 

							if ($errorPassword2 != '') {

								echo '<div class="errorRegistro">';
							 	echo "<p> $errorPassword2 </p>";
							 	echo '</div>';

							}
						
						?>

					</div>	
				
					<button type="submit" name="botonR">Confirmar</button>

				</form>

			</div>

			<div id="iniciarSesion">

				<h2>Iniciar Sesión</h2>

				<form method="POST" action="<?php echo $rutaRegistro; ?>">

					<div>
			
						<label for="nombreI">Nombre de Usuario:</label>
						<input type="text" name="nombreI" placeholder="Nombre / Mail" value="<?php echo str_replace("\"", "", $nombreOEmailLogin); ?>" required>
			
					</div>
			
					<div>
			
						<label for="passwordI">Contraseña:</label>
						<input type="password" name="passwordI" required>

						<?php 

							if ($errorLogin != '') {

								echo '<div class="errorRegistro">';
							 	echo "<p> $errorLogin </p>";
							 	echo '</div>';

							}
						
						?>
			
					</div>
			
					<button type="submit" name="botonI">Confirmar</button>

				</form>

				<p><a href="<?php echo $rutaRecuperarPassword; ?>">¿Has olvidado tu contraseña?</a></p>

			</div>
			
		</div>
