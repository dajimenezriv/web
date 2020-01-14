<?php

class ValidacionLogin {

	private $usuario;
	private $error;

	private $nombre_email;

	public function __construct($nombre_email, $password, $conexion) {

		$this -> error = '';

		if (!$this -> variableIniciada($nombre_email) || !$this -> variableIniciada($password)) {

			$this -> usuario = null;
			$this -> error = 'Debes introducir tu Nombre/Email y Contraseña';

		} else {

			$this -> nombre_email = $nombre_email;
			$this -> usuario = RepositorioUsuario :: getUsuarioPorNombre($conexion, $nombre_email);

			if (is_null($this -> usuario) || !password_verify($password, $this -> usuario -> getPassword())) {

				$this -> usuario = RepositorioUsuario :: getUsuarioPorEmail($conexion, $nombre_email);

				if (is_null($this -> usuario) || !password_verify($password, $this -> usuario -> getPassword())) {

					$this -> error = 'Datos Incorrectos'; 

				}

			}

		}

	}

	private function variableIniciada($variable) {

		if (isset($variable) && !empty($variable)) {

			return true;

		} else {

			return false;

		}

	}

	public function getUsuario() {

		return $this -> usuario;

	}

	public function getNombreOEmail() {

		return $this -> nombre_email;

	}

	public function getError() {

		return $this -> error;

	}

}