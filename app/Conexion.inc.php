<?php

class Conexion {

	private static $conexion;

	public static function abrirConexion() {

		if (!isset(self :: $conexion)) { // isset: esta inicializada

			try {

				self :: $conexion = new PDO('mysql:host=' . NOMBRE_SERVIDOR . '; dbname=' . NOMBRE_BBDD . '', NOMBRE_USUARIO, PASSWORD);
				self :: $conexion -> setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
				self :: $conexion -> exec('SET CHARACTER SET utf8');

			} catch (PDOException $e) {

				print 'ERROR: ' . $e -> getMessage();
 				die();

			}

		}



	}

	public static function cerrarConexion() {

		if (isset(self :: $conexion)) {

			self :: $conexion = null;		

		}

	}

	public static function getConexion() {

		return self :: $conexion;

	}

}
