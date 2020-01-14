<?php

class RepositorioComentario {

	/*****************GENERALES*****************/

	public static function nuevoComentario($conexion, $comentario) {

		$comentarioInsertado = false;

		if (isset($conexion)) {

			try {

				$sql = "INSERT INTO comentarios(autor_id, entrada_id, titulo, texto, referencia, fecha) VALUES(:autor_id, :entrada_id, :titulo, :texto, :referencia, NOW())";
				
				$sentencia = $conexion -> prepare($sql);

				$tempAutorId = $comentario -> getAutorId();
				$tempEntradaId = $comentario -> getEntradaId();
				$tempTitulo = $comentario -> getTitulo();
				$tempTexto = $comentario -> getTexto();
				$tempReferencia = $comentario -> getReferencia();

				$sentencia -> bindParam(':autor_id', $tempAutorId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':entrada_id', $tempEntradaId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':titulo', $tempTitulo, PDO :: PARAM_STR);
				$sentencia -> bindParam(':texto', $tempTexto, PDO :: PARAM_STR);
				$sentencia -> bindParam(':referencia', $tempReferencia, PDO :: PARAM_STR);
				
				$comentarioInsertado = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR' . $e -> getMessage();

		
			}
		}
		
		return $comentarioInsertado;
	
	}

	public static function eliminarComentarioPorId($conexion, $idComentario) {

		$comentarioEliminado = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM comentarios WHERE id = :id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':id', $idComentario, PDO :: PARAM_STR);

				$comentarioEliminado = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $comentarioEliminado;

	}

	public static function eliminarComentariosPorAutorId($conexion, $autorId) {

		$comentariosEliminados = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM comentarios WHERE autor_id = :autor_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':autor_id', $autorId, PDO :: PARAM_STR);

				$resultados = $sentencia -> execute();

				$comentariosEliminados = true;

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $comentariosEliminados;

	}

	public static function eliminarComentariosPorEntradaId($conexion, $entradaId) {

		$comentariosEliminados = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM comentarios WHERE entrada_id = :entrada_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);

				$resultados = $sentencia -> execute();	

				$comentariosEliminados = true;

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $comentariosEliminados;

	}

	public static function eliminarComentariosPorReferenciaId($conexion, $referencia) {

		$comentariosEliminados = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM comentarios WHERE referencia = :referencia";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':referencia', $referencia, PDO :: PARAM_STR);

				$resultados = $sentencia -> execute();	

				$comentariosEliminados = true;

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $comentariosEliminados;

	}

	public static function getNumeroComentariosPorAutorId($conexion, $autorId) {
	
		$numeroComentarios = null;
	
		if (isset($conexion)) {
	
			try {
	
				$sql = "SELECT COUNT(*) as total FROM comentarios WHERE autor_id = :autor_id ORDER BY fecha DESC";
				$sentencia = $conexion -> prepare($sql);
	
				$sentencia -> bindParam(':autor_id', $autorId, PDO :: PARAM_STR);
	
				$sentencia -> execute();	
				$resultado = $sentencia -> fetch();
				$numeroComentarios = $resultado['total'];
	
			} catch (PDOException $e) {
	
				print 'ERROR ' . $e -> getMessage();
	
			}
	
		}
	
		return $numeroComentarios;
	
	}

	public static function getNumeroComentariosPorEntradaId($conexion, $entradaId) {
	
		$numeroComentarios = null;
	
		if (isset($conexion)) {
	
			try {
	
				$sql = "SELECT COUNT(*) as total FROM comentarios WHERE entrada_id = :entrada_id ORDER BY fecha DESC";
				$sentencia = $conexion -> prepare($sql);
	
				$sentencia -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);
	
				$sentencia -> execute();	
				$resultado = $sentencia -> fetch();
				$numeroComentarios = $resultado['total'];
	
			} catch (PDOException $e) {
	
				print 'ERROR ' . $e -> getMessage();
	
			}
	
		}
	
		return $numeroComentarios;
	
	}

	public static function getComentarioPorId($conexion, $id) {
	
		$comentario = null;
	
		if (isset($conexion)) {
	
			try {
	
				$sql = "SELECT * FROM comentarios WHERE id = :id ORDER BY fecha DESC";
				$sentencia = $conexion -> prepare($sql);
	
				$sentencia -> bindParam(':id', $id, PDO :: PARAM_STR);
	
				$sentencia -> execute();	
				$resultados = $sentencia -> fetchAll();
	
				if (count($resultados)) {
	
					foreach ($resultados as $resultado) {

						$comentario = new Comentario($resultado['id'], $resultado['autor_id'], $resultado['entrada_id'],
							$resultado['titulo'], $resultado['texto'], $resultado['referencia'], $resultado['fecha']);	

						break;

					}
				
				}
	
			} catch (PDOException $e) {
	
				print 'ERROR ' . $e -> getMessage();
	
			}
	
		}
	
		return $comentario;
	
	}

	public static function getComentariosPorAutorId($conexion, $autorId) {
	
		$comentarios = [];
	
		if (isset($conexion)) {
	
			try {
	
				$sql = "SELECT * FROM comentarios WHERE autor_id = :autor_id ORDER BY fecha DESC";
				$sentencia = $conexion -> prepare($sql);
	
				$sentencia -> bindParam(':autor_id', $autorId, PDO :: PARAM_STR);
	
				$sentencia -> execute();	
				$resultados = $sentencia -> fetchAll();
	
				if (count($resultados)) {

					foreach ($resultados as $resultado) {
	
						$comentarios[] = new Comentario($resultado['id'], $resultado['autor_id'], $resultado['entrada_id'],
							$resultado['titulo'], $resultado['texto'], $resultado['referencia'], $resultado['fecha']);

					}
				
				}
	
			} catch (PDOException $e) {
	
				print 'ERROR ' . $e -> getMessage();
	
			}
	
		}
	
		return $comentarios;
	
	}

	public static function getComentariosPorEntradaId($conexion, $entradaId) {
	
		$comentarios = [];
	
		if (isset($conexion)) {
	
			try {
	
				$sql = "SELECT * FROM comentarios WHERE entrada_id = :entrada_id AND referencia = 0 ORDER BY fecha DESC";
				$sentencia = $conexion -> prepare($sql);
	
				$sentencia -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);
	
				$sentencia -> execute();	
				$resultados = $sentencia -> fetchAll();
	
				if (count($resultados)) {

					foreach ($resultados as $resultado) {
	
						$comentarios[] = new Comentario($resultado['id'], $resultado['autor_id'], $resultado['entrada_id'],
							$resultado['titulo'], $resultado['texto'], $resultado['referencia'], $resultado['fecha']);

					}
				
				}
	
			} catch (PDOException $e) {
	
				print 'ERROR ' . $e -> getMessage();
	
			}
	
		}
	
		return $comentarios;
	
	}

	public static function getComentariosPorReferenciaId($conexion, $entradaId, $referencia) {
	
		$comentarios = [];
	
		if (isset($conexion)) {
	
			try {
	
				$sql = "SELECT * FROM comentarios WHERE entrada_id = :entrada_id AND referencia = :referencia ORDER BY fecha DESC";
				$sentencia = $conexion -> prepare($sql);
	
				$sentencia -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':referencia', $referencia, PDO :: PARAM_STR);
	
				$sentencia -> execute();	
				$resultados = $sentencia -> fetchAll();
	
				if (count($resultados)) {

					foreach ($resultados as $resultado) {
	
						$comentarios[] = new Comentario($resultado['id'], $resultado['autor_id'], $resultado['entrada_id'],
							$resultado['titulo'], $resultado['texto'], $resultado['referencia'], $resultado['fecha']);

					}
				
				}
	
			} catch (PDOException $e) {
	
				print 'ERROR ' . $e -> getMessage();
	
			}
	
		}
	
		return $comentarios;
	
	}

}