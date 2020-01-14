<?php

class RepositorioEntrada {

	/*****************GENERALES*****************/

	public static function nuevaEntrada($conexion, $entrada) {

		$entradaInsertada = false;

		if (isset($conexion)) {

			try {

				$sql = "INSERT INTO entradas(autor_id, url, seccion, imagen, titulo, texto, fecha, activa) VALUES(:autor_id, :url, :seccion, :imagen, :titulo, :texto, NOW(), :activa)";
				$sentencia = $conexion -> prepare($sql);

				$tempAutorId = $entrada -> getAutorId();
				$tempUrl = $entrada -> getUrl();
				$tempSeccion = $entrada -> getSeccion();
				$tempImagen = $entrada -> getImagen();
				$tempTitulo = $entrada -> getTitulo();
				$tempTexto = $entrada -> getTexto();
				$tempActiva = $entrada -> getActiva();

				$sentencia -> bindParam(':autor_id', $tempAutorId, PDO :: PARAM_STR);
				$sentencia -> bindParam(':url', $tempUrl, PDO :: PARAM_STR);
				$sentencia -> bindParam(':seccion', $tempSeccion, PDO :: PARAM_STR);
				$sentencia -> bindParam(':imagen', $tempImagen, PDO :: PARAM_STR);
				$sentencia -> bindParam(':titulo', $tempTitulo, PDO :: PARAM_STR);		
				$sentencia -> bindParam(':texto', $tempTexto, PDO :: PARAM_STR);
				$sentencia -> bindParam(':activa', $tempActiva, PDO :: PARAM_STR);
				

				$entradaInsertada = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $entradaInsertada;

	}

	public static function borrarEntrada($conexion, $entradaId) {

		if (isset($conexion)) {

			try {

				$sql1 = "DELETE FROM comentarios WHERE entrada_id = :entrada_id";
				$sql2 = "DELETE FROM entradas_favoritas WHERE entrada_id = :entrada_id";
				$sql3 = "DELETE FROM entradas WHERE id = :entrada_id";
				
				$sentencia1 = $conexion -> prepare($sql1);
				$sentencia2 = $conexion -> prepare($sql2);
				$sentencia3 = $conexion -> prepare($sql3);

				$sentencia1 -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);
				$sentencia2 -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);
				$sentencia3 -> bindParam(':entrada_id', $entradaId, PDO :: PARAM_STR);

				$conexion -> beginTransaction();

				$sentencia1 -> execute();
				$sentencia2 -> execute();
				$sentencia3 -> execute();

				$conexion -> commit();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

				$conexion -> rollBack();

			} 

		}

	}

	public static function eliminarEntradasPorAutorId($conexion, $autorId) {

		$entradasEliminadas = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM entradas WHERE autor_id = :autor_id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':autor_id', $autorId, PDO :: PARAM_STR);

				$resultados = $sentencia -> execute();

				$entradasEliminadas = true;

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $entradasEliminadas;

	}

	public static function editarEntrada($conexion, $id, $seccion, $url, $titulo, $imagen, $texto, $activa) {

		$entradaEditada = false;

		if (isset($conexion)) {

			try {

				$sql = "UPDATE entradas SET titulo = :titulo, url = :url, texto = :texto, seccion = :seccion, imagen = :imagen, activa = :activa WHERE id = :id";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':url', $url, PDO :: PARAM_STR);
				$sentencia -> bindParam(':seccion', $seccion, PDO :: PARAM_STR);
				$sentencia -> bindParam(':imagen', $imagen, PDO :: PARAM_STR);
				$sentencia -> bindParam(':titulo', $titulo, PDO :: PARAM_STR);		
				$sentencia -> bindParam(':texto', $texto, PDO :: PARAM_STR);
				$sentencia -> bindParam(':activa', $activa, PDO :: PARAM_STR);
				$sentencia -> bindParam(':id', $id, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultado = $sentencia -> rowCount();

				if (count($resultado)) {

					$entradaEditada = true;

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $entradaEditada;

	}

	public static function getTodasEntradasPorFechaDescendiente($conexion, $activa) {

		$entradas = [];

		if (isset($conexion)) {

			try {

				if ($activa) {

					$sql = "SELECT * FROM entradas WHERE activa = 1 ORDER BY fecha";

				} else {

					$sql = "SELECT * FROM entradas ORDER BY fecha DESC";

				}
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$entradas[] = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entradas;

	}

	public static function getEntradaPorId($conexion, $id, $activa) {

		$entrada = null;

		if (isset($conexion)) {

			try {

				if ($activa) {

					$sql = "SELECT * FROM entradas WHERE id = :id AND activa = 1 ORDER BY fecha DESC";

				} else {

					$sql = "SELECT * FROM entradas WHERE id = :id ORDER BY fecha DESC";

				}

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':id', $id, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {	

					foreach ($resultados as $resultado) {

						$entrada = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entrada;

	}

	public static function getEntradasPorAutorId($conexion, $autorId, $activa) {

		$entradas = [];

		if (isset($conexion)) {

			try {

				if ($activa) {

					$sql = "SELECT * FROM entradas WHERE autor_id = :autor_id AND activa = 1 ORDER BY fecha DESC";

				} else {

					$sql = "SELECT * FROM entradas WHERE autor_id = :autor_id ORDER BY fecha DESC";

				}

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':autor_id', $autorId, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$entradas[] = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entradas;

	}

	public static function getEntradasPorSeccion($conexion, $seccion, $activa) {

		$entradas = [];

		if (isset($conexion)) {

			try {

				if ($activa) {

					$sql = "SELECT * FROM entradas WHERE seccion = :seccion AND activa = 1 ORDER BY fecha DESC";

				} else {

					$sql = "SELECT * FROM entradas WHERE seccion = :seccion ORDER BY fecha DESC";

				}

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':seccion', $seccion, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$entradas[] = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entradas;

	}

	public static function getEntradaPorTitulo($conexion, $titulo, $activa) {

		$entrada = null;

		if (isset($conexion)) {

			try {

				if ($activa) {

					$sql = "SELECT * FROM entradas WHERE titulo = :titulo AND activa = 1 ORDER BY fecha DESC";

				} else {

					$sql = "SELECT * FROM entradas WHERE titulo = :titulo ORDER BY fecha DESC";

				}

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':titulo', $titulo, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {	

					foreach ($resultados as $resultado) {

						$entrada = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entrada;

	}

	public static function getEntradaPorUrl($conexion, $url, $activa) {

		$entrada = null;

		if (isset($conexion)) {

			try {

				if ($activa) {

					$sql = "SELECT * FROM entradas WHERE url = :url AND activa = 1 ORDER BY fecha DESC";

				} else {

					$sql = "SELECT * FROM entradas WHERE url = :url ORDER BY fecha DESC";

				}

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':url', $url, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {	

					foreach ($resultados as $resultado) {

						$entrada = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entrada;

	}

	public static function compararEntradaPorTitulo($conexion, $titulo) {

		$encontrada = false;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM entradas WHERE titulo = :titulo";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':titulo', $titulo, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultado = $sentencia -> fetchAll();

				if (count($resultado)) {

					$encontrada = true;

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $encontrada;

	}

	public static function compararEntradaPorUrl($conexion, $url) {

		$encontrada = false;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM entradas WHERE url = :url";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':url', $url, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultado = $sentencia -> fetchAll();
				
				if (count($resultado)) {

					$encontrada = true;

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $encontrada;

	}

	/*****************INICIO*****************/

	public static function getTresEntradasPorFechaDescendiente($conexion, $activa) {

		$entradas = [];

		if (isset($conexion)) {

			try {

				if ($activa) {

					$sql = "SELECT * FROM entradas WHERE activa = 1 ORDER BY fecha DESC LIMIT 3";

				} else {

					$sql = "SELECT * FROM entradas ORDER BY fecha DESC LIMIT 3";

				}

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$entradas[] = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entradas;

	}

	/*****************BUSQUEDA*****************/

	public static function getEntradasPorBusqueda($conexion, $texto, $activa) {

		$entradas = [];

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM entradas WHERE"; 

				if ($activa) {

					$sql .= " activa = 1 AND (titulo LIKE '%$texto%' OR texto LIKE '%$texto%') ORDER BY fecha DESC";

				} else {

					$sql .= " titulo LIKE '%$texto%' OR texto LIKE '%$texto%' ORDER BY fecha DESC";

				}

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$entradas[] = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entradas;

	}

	/*****************ENTRADA*****************/

	public static function getTresEntradasAleatorias($conexion, $activa) {

		$entradas = [];

		if (isset($conexion)) {

			try {

				if ($activa) {

					$sql = "SELECT * FROM entradas WHERE activa = 1 ORDER BY RAND() LIMIT 3";

				} else {

					$sql = "SELECT * FROM entradas ORDER BY RAND() LIMIT 3";

				}

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$entradas[] = new Entrada($resultado['id'], $resultado['autor_id'], $resultado['url'], $resultado['seccion'], $resultado['imagen'], $resultado['titulo'], $resultado['texto'], $resultado['fecha'], $resultado['activa']);

					}

				}

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();

			}

		}

		return $entradas;

	}

	/*****************FUNCIONES EXTRA*****************/

	public static function resumirTexto($texto) {

		$longitudMaxima = 400;

		$resultado = '';

		$textoFinal = '';

		if (strlen($texto) >= $longitudMaxima) {

			$resultado = substr($texto, 0, $longitudMaxima);

		} else {

			$resultado = $texto;

		}

		$resultado = str_replace('<br />', '', $resultado);

		$resultado = str_replace('<img', '&12345', $resultado);

		$resultado = explode('&', $resultado);	

		foreach ($resultado as $e) {
			
			if (substr($e, 0, 5) != '12345') {

				$textoFinal .= $e;

			}

		}	
		
		return nl2br($textoFinal);
		
	}

}