<?php

class RepositorioRecuperacion {

	/*****************GENERALES*****************/

	public static function nuevaRecuperacionPassword($conexion, $usuarioId, $urlSecreta) {

		$recuperacionClaveGenerada = false;

		if (isset($conexion)) {

			try {

				$sql = "INSERT INTO recuperacion(tipo, usuario_id, url_secreta, fecha) VALUES (:tipo, :usuario_id, :url_secreta, NOW())";

				$sentencia = $conexion -> prepare($sql);

				$tipo = 'password';

				$sentencia -> bindParam(':tipo', $tipo, PDO :: PARAM_STR);
				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':url_secreta', $urlSecreta, PDO :: PARAM_STR);

				$recuperacionClaveGenerada = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $recuperacionClaveGenerada;

	} 

	public static function nuevaActivacion($conexion, $usuarioId, $urlSecreta) {

		$recuperacionActivacion = false;

		if (isset($conexion)) {

			try {

				$sql = "INSERT INTO recuperacion(tipo, usuario_id, url_secreta, fecha) VALUES (:tipo, :usuario_id, :url_secreta, NOW())";

				$sentencia = $conexion -> prepare($sql);

				$tipo = 'activacion';

				$sentencia -> bindParam(':tipo', $tipo, PDO :: PARAM_STR);
				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':url_secreta', $urlSecreta, PDO :: PARAM_STR);

				$recuperacionActivacion = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $recuperacionActivacion;

	} 

	public static function eliminarRecuperacionesPasswordPorUsuarioId($conexion, $usuarioId) {

		$recuperacionClaveEliminada = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM recuperacion WHERE tipo = 'password' AND usuario_id = :usuario_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);

				$recuperacionClaveEliminada = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $recuperacionClaveEliminada;

	}

	public static function eliminarActivacionesPorUsuarioId($conexion, $usuarioId) {

		$recuperacionClaveEliminada = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM recuperacion WHERE tipo = 'activacion' AND usuario_id = :usuario_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);

				$recuperacionClaveEliminada = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $recuperacionClaveEliminada;

	}

	public static function getRecuperacionPasswordPorUrlSecreta($conexion, $urlSecreta) {

		$recuperacionPassword = null;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM recuperacion WHERE tipo = 'password' AND url_secreta = :url_secreta";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':url_secreta', $urlSecreta, PDO :: PARAM_STR);

				$sentencia -> execute();

				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {
						
						$recuperacionPassword = new Recuperacion($resultado['id'], $resultado['tipo'], $resultado['usuario_id'], $resultado['url_secreta'], $resultado['fecha']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $recuperacionPassword;

	}

	public static function getActivacionPorUrlSecreta($conexion, $urlSecreta) {

		$recuperacionPassword = null;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM recuperacion WHERE tipo = 'activacion' AND url_secreta = :url_secreta";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':url_secreta', $urlSecreta, PDO :: PARAM_STR);

				$sentencia -> execute();

				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {
						
						$recuperacionPassword = new Recuperacion($resultado['id'], $resultado['tipo'], $resultado['usuario_id'], $resultado['url_secreta'], $resultado['fecha']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $recuperacionPassword;

	}

}