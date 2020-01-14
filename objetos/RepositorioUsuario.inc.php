<?php

class RepositorioUsuario {

	/*****************GENERALES*****************/

	public static function nuevoUsuario($conexion, $usuario) {

		$usuarioInsertado = false;

		if (isset($conexion)) {

			try {

				$sql = "INSERT INTO usuarios(nombre, email, password, fecha_registro, activo, registrado) VALUES(:nombre, :email, :password, NOW(), 0, 0)";

				$sentencia = $conexion -> prepare($sql);

				$tempNombre = $usuario -> getNombre();
				$tempEmail = $usuario -> getEmail();
				$tempPassword = $usuario -> getPassword();

				$sentencia -> bindParam(':nombre', $tempNombre, PDO :: PARAM_STR);
				$sentencia -> bindParam(':email', $tempEmail, PDO :: PARAM_STR);
				$sentencia -> bindParam(':password', $tempPassword, PDO :: PARAM_STR);

				$usuarioInsertado = $sentencia -> execute();

			} catch (PDOException $e) {

				print 'ERROR' . $e -> getMessage();

			}

		}

		return $usuarioInsertado;

	}

	public static function eliminarUsuarioPorId($conexion, $id) {

		$usuarioEliminado = false;

		if (isset($conexion)) {

			try {

				$sql = "DELETE FROM usuarios WHERE id = :id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':id', $id, PDO :: PARAM_STR);

				$usuarioInsertado = $sentencia -> execute();

				$usuarioEliminado = true;

			} catch (PDOException $e) {

				print 'ERROR' . $e -> getMessage();

			}

		}

		return $usuarioEliminado;

	}


	public static function activarUsuario($conexion, $usuarioId) {

		$usuarioActivado = false;

		if (isset($conexion)) {

			try {

				$sql = "UPDATE usuarios SET activo = 1, registrado = 1 WHERE id = :id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':id', $usuarioId, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultado = $sentencia -> rowCount();

				if ($resultado != 0) {

					$usuarioActivado = true;

				}

			} catch (PDOException $e) {

				print 'ERROR' . $e -> getMessage();

			}

		}

		return $usuarioActivado;

	}

	public static function cambiarPasswordPorUsuarioId($conexion, $usuarioId, $password) {

		$usuarioEditado = false;

		if (isset($conexion)) {

			try {

				$sql = "UPDATE usuarios SET password = :password WHERE id = :id";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':password', $password, PDO :: PARAM_STR);
				$sentencia -> bindParam(':id', $usuarioId, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultado = $sentencia -> rowCount();

				if ($resultado != 0) {

					$usuarioEditado = true;

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $usuarioEditado;

	}

	public static function getTodosUsuarios($conexion) {

		$usuarios = array();

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM usuarios";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll(); // Devuelve todas las Entradas

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$usuarios[] = new Usuario($resultado['id'], $resultado['nombre'], $resultado['email'], $resultado['password'], $resultado['fecha_registro'], $resultado['activo'], $resultado['registrado']);

					}

				} else {

					print "NO HAY USUARIOS";

				}

			} catch (PDOException $e) {

				print "EROR: " . $e -> getMessage();

			}

		}

		return $usuarios;

	}

	public static function getNumeroUsuarios($conexion) {

		$totalUsers = null;

		if (isset($conexion)) {

			try {

				// Cuenta el número de entradas y los guarda en "total"
				$sql = "SELECT COUNT(*) as total FROM usuarios";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> execute();
				$resultado = $sentencia -> fetch(); // Devuelve una Entrada
				$totalUsers = $resultado['total'];

			} catch (PDOException $e) {

				print "ERROR: " . $e -> getMessage(); 

			}

		}

		return $totalUsers;

	}

	public static function getUsuarioPorId($conexion, $id) {

		$usuario = null;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM usuarios WHERE id = :id";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':id', $id, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$usuario = new Usuario($resultado['id'], $resultado['nombre'], $resultado['email'], $resultado['password'], $resultado['fecha_registro'], $resultado['activo'], $resultado['registrado']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $usuario;

	}

	public static function getUsuarioPorNombre($conexion, $nombre) {

		$usuario = null;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM usuarios WHERE nombre = :nombre";

				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':nombre', $nombre, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$usuario = new Usuario($resultado['id'], $resultado['nombre'], $resultado['email'], $resultado['password'], $resultado['fecha_registro'], $resultado['activo'], $resultado['registrado']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $usuario;

	}

	public static function getUsuarioPorEmail($conexion, $email) {

		$usuario = null;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM usuarios WHERE email = :email";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':email', $email, PDO :: PARAM_STR);

				$sentencia -> execute();
				$resultados = $sentencia -> fetchAll();

				if (count($resultados)) {

					foreach ($resultados as $resultado) {

						$usuario = new Usuario($resultado['id'], $resultado['nombre'], $resultado['email'], $resultado['password'], $resultado['fecha_registro'], $resultado['activo'], $resultado['registrado']);

						break;

					}

				}

			} catch (PDOException $e) {

				print 'ERROR ' . $e -> getMessage();

			}

		}

		return $usuario;

	}

	public static function compararUsuariosPorNombre($conexion, $nombre) {

		$nombreEncontrado = false;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM usuarios WHERE nombre = :nombre";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':nombre', $nombre, PDO::PARAM_STR);

				$sentencia -> execute();
				$resultado = $sentencia -> fetchAll();

				if (count($resultado)) {

					$nombreEncontrado = true;

				}

			} catch (PDOException $e) {

				print 'ERROR' . $e -> getMessage();

			}

		}

		return $nombreEncontrado;

	}

	public static function compararUsuariosPorEmail($conexion, $email) {

		$emailEncontrado = false;

		if (isset($conexion)) {

			try {

				$sql = "SELECT * FROM usuarios WHERE email = :email";
				$sentencia = $conexion -> prepare($sql);

				$sentencia -> bindParam(':email', $email, PDO::PARAM_STR);

				$sentencia -> execute();
				$resultado = $sentencia -> fetchAll();

				if (count($resultado)) {

					$emailEncontrado = true;

				}

			} catch (PDOException $e) {

				print 'ERROR' . $e -> getMessage();

			}

		}

		return $emailEncontrado;

	}

}
