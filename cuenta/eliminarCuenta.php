<?php

	error_reporting(E_ERROR | E_PARSE);

	/*****************VARIABLES LOCALES*****************/

	$errorFormulario = '';

	/*****************METODOS*****************/

	if (isset($_POST['boton'])) {

		if (!($_SESSION['idUsuario'] === '1')) {

			$password = $_POST['password'];

			Conexion :: abrirConexion();

			$usuario = RepositorioUsuario :: getUsuarioPorId(Conexion :: getConexion(), $_SESSION['idUsuario']);

			Conexion :: cerrarConexion();

			if (password_verify($password, $usuario -> getPassword())) {

				Conexion :: abrirConexion();

				RepositorioRecuperacion :: eliminarRecuperacionesPasswordPorUsuarioId(Conexion :: getConexion(), $usuario -> getId());

				RepositorioRecuperacion :: eliminarActivacionesPorUsuarioId(Conexion :: getConexion(), $usuario -> getId());

				$entradas = RepositorioEntrada :: getEntradasPorAutorId(Conexion :: getConexion(), $usuario -> getId(), 1);

				for ($i = 0; $i < count($entradas); $i++) {
					
					RepositorioComentario :: eliminarComentariosPorEntradaId(Conexion :: getConexion(), $entradas[$i] -> getId());

					RepositorioEntradaFavorita :: eliminarEntradasFavoritasPorEntradaId(Conexion :: getConexion(), $entradas[$i] -> getId());

				}

				$comentarios = RepositorioComentario :: getComentariosPorAutorId(Conexion :: getConexion(), $usuario -> getId());

				for ($i = 0; $i < count($comentarios); $i++) {

					RepositorioComentario :: eliminarComentariosPorReferenciaId(Conexion :: getConexion(), $comentarios[$i] -> getId());

				}

				RepositorioComentario :: eliminarComentariosPorAutorId(Conexion :: getConexion(), $usuario -> getId());

				RepositorioEntradaFavorita :: eliminarEntradasFavoritasPorUsuarioId(Conexion :: getConexion(), $usuario -> getId());

				RepositorioEntrada :: eliminarEntradasPorAutorId(Conexion :: getConexion(), $usuario -> getId());

				$errorFormulario = RepositorioUsuario :: eliminarUsuarioPorId(Conexion :: getConexion(), $usuario -> getId());

				Conexion :: cerrarConexion();

				if (!$errorFormulario) {

					$errorFormulario = "Error al eliminar la cuenta";

				} else {

					ControlSesion :: cerrarSesion();

					Redireccion :: redirigir(SERVIDOR);

				}

			} else {

				$errorFormulario = "La contraseña no es correcta";

			}

		} else {

			$errorFormulario = "No se puede borrar la cuenta del administrador";

		}

	}

?>

		<div id="eliminarCuenta">

			<h2>Eliminar Cuenta</h2>

			<form method="post" action="<?php echo $rutaEliminarCuenta; ?>">

				<div>

					<?php 

						if ($errorFormulario != '') {

							echo '<div id="errorFormulario">';
						 	echo "<p> $errorFormulario </p>";
						 	echo '</div>';

						}
					
					?>
		
					<label for="password">Password:</label>
					<input type="password" name="password" placeholder="Password" required>
		
				</div>
		
				<button type="submit" name="boton">Confirmar</button>

			</form>

		</div>
