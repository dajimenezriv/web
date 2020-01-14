<?php	

	/*****************VARIABLES LOCALES*****************/

	$errorFormulario = '';

	$rutaCambioPassword = SERVIDOR . 'password/' . $urlSecreta;

	/*****************METODOS*****************/

	if (isset($_POST['boton'])) {

		$usuarioId = $recuperacionPassword -> getUsuarioId();

		$hashPasswordUsuario = password_hash($_POST['password'], PASSWORD_DEFAULT);

		$tipo = 'password';

		Conexion :: abrirConexion();

		RepositorioUsuario :: cambiarPasswordPorUsuarioId(Conexion :: getConexion(), $usuarioId, $hashPasswordUsuario);

		RepositorioRecuperacion :: eliminarRecuperacionesPasswordPorUsuarioId(Conexion :: getConexion(), $usuarioId);

		Conexion :: cerrarConexion();

		Redireccion :: redirigir($rutaRegistro);

	}

?>


		<div id="recuperarPassword">

			<h2>Recuperar Contraseña</h2>

			<form method="post" action="<?php echo $rutaCambioPassword; ?>">

				<div>

					<?php 

						if ($errorFormulario != '') {

							echo '<div id="errorFormulario">';
						 	echo "<p> $errorFormulario </p>";
						 	echo '</div>';

						}
					
					?>
		
					<label for="password">Nueva Contraseña:</label>
					<input type="password" name="password" placeholder="Nueva Contraseña" required>
		
				</div>
		
				<button type="submit" name="boton">Confirmar</button>

			</form>

		</div>