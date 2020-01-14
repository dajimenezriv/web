<?php

class RepositorioEntradaFavorita {

	/*****************GENERALES*****************/

	public static function nuevaEntradaFavorita($conexion, $usuarioId, $entradaId) {

		$entradaFavoritaIncluida = false;

		if (isset($conexion)) {

			try {

				$sql = "INSERT INTO entradas_favoritas(usuario_id, entrada_id, fecha) VALUES (:usuario_id, :entrada_id, NOW())";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);

				$entradaFavoritaIncluida = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $entradaFavoritaIncluida;

	}

	public static function eliminarEntradaFavorita($conexion, $usuarioId, $entradaId) {

		$entradaFavoritaIncluida = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM entradas_favoritas WHERE usuario_id = :usuario_id AND entrada_id = :entrada_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);

				$entradaFavoritaIncluida = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $entradaFavoritaIncluida;

	}

	public static function eliminarEntradasFavoritasPorUsuarioId($conexion, $usuarioId) {

		$entradasFavoritasEliminadas = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM entradas_favoritas WHERE usuario_id = :usuario_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);

				$resultados = $sentencia -> execute();	

				$entradasFavoritasEliminadas = true;

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $entradasFavoritasEliminadas;

	}

	public static function eliminarEntradasFavoritasPorEntradaId($conexion, $entradaId) {

		$entradasFavoritasEliminadas = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM entradas_favoritas WHERE entrada_id = :entrada_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);

				$resultados = $sentencia -> execute();	

				$entradasFavoritasEliminadas = true;

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $entradasFavoritasEliminadas;

	}

	public static function getEntradasFavoritasPorUsuarioId($conexion, $usuarioId) {

		$entradasFavoritas = [];

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM entradas_favoritas WHERE usuario_id = :usuario_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$entradasFavoritas[] = new EntradaFavorita($resultado['id'], $resultado['usuario_id'], $resultado['entrada_id'], $resultado['fecha']);

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entradasFavoritas;

	}

	public static function encontrarEntradaFavoritaPorUsuarioId($conexion, $usuarioId, $entradaId) {

		$entradaFavoritaEncontrada = false;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM entradas_favoritas WHERE usuario_id = :usuario_id AND entrada_id = :entrada_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':usuario_id', $usuarioId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultado = $sentencia -> fetchAll();

				if (count($resultado)) {

					$entradaFavoritaEncontrada = true;

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entradaFavoritaEncontrada;

	}


}