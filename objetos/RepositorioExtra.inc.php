<?php

class RepositorioExtra {

	public static function nuevoExtra($conexion, $extra) {

		$extraInsertado = false;

		if (isset($conexion)) {

			try {

				$sql = "INSERT INTO extras(advertencia, consejo, sabias_que, fecha) VALUES(:advertencia, :consejo, :sabias_que, NOW())";

				$sentencia = $conexion -> prepare($sql);

				$tempAdvertencia = $extra -> getAdvertencia();
				$tempConsejo = $extra -> getConsejo();
				$tempSabiasQue = $extra -> getSabiasQue();

				$sentencia -> bindParam(':advertencia', $tempAdvertencia, PDO :: PARAM_STR);
				$sentencia -> bindParam(':consejo', $tempConsejo, PDO :: PARAM_STR);
				$sentencia -> bindParam(':sabias_que', $tempSabiasQue, PDO :: PARAM_STR);

				$extraInsertado = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $extraInsertado;

	}

	public static function getExtraNuevo($conexion) {

		$extra = null;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM extras ORDER BY fecha DESC LIMIT 1";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> execute();

				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {
						
						$extra = new Extra($resultado['id'], $resultado['advertencia'], $resultado['consejo'], $resultado['sabias_que'], $resultado['fecha']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $extra;

	}

}