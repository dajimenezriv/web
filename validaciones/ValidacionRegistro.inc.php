<?php

class ValidacionRegistro {

	private $nombre;
	private $email;
	private $password;

	private $errorNombre;
	private $errorEmail;
	private $errorPassword1;
	private $errorPassword2;

	public function __construct($nombre, $email, $password1, $password2, $conexion) {

		$this -> nombre = '';
		$this -> email = '';
		$this -> password = '';

		$this -> errorNombre = $this -> validarNombreUsuario($conexion, $nombre);
		$this -> errorEmail = $this -> validarEmailUsuario($conexion, $email);
		$this -> errorPassword1 = $this -> validarPassword1($password1);
		$this -> errorPassword2 = $this -> validarPassword2($password1, $password2);

		if ($this -> errorPassword1 === "" && $this -> errorPassword2 === "") {

			$this -> password = $password1;

		}

	}

	private function variableIniciada($variable) {

		if (isset($variable) && !empty($variable)) {

			return true;

		} else {

			return false;

		}

	}

	private function validarNombreUsuario($conexion, $nombre) {

		if (!$this -> variableIniciada($nombre)) {

			return 'Debes escribir un nombre de Usuario';

		} else {

			$this -> nombre = $nombre;	

		}

		if (strlen($nombre) < 4) {

			return 'El nombre de Usuario debe contener al menos 4 caracteres';

		}

		if (strlen($nombre) > 25) {

			return 'El nombre de Usuario no puede contener más de 25 caracteres';

		}

		if (strpos($nombre, "/")) {

			return 'El nombre de Usuario no puede contener el caracter \"/\"';

		}

		if (!mb_check_encoding($nombre, 'ASCII')) {

			return 'El nombre de Usuario contiene caracteres no permitidos. Unicamente caracteres ASCII.';

		}

		if (RepositorioUsuario :: compararUsuariosPorNombre($conexion, $nombre)) {

			return 'El nombre de Usuario ya está en uso';

		}

		return "";

	}

	private function validarEmailUsuario($conexion, $email) {

		if (!$this -> variableIniciada($email)) {

			return 'Debes proporcionar un Mail';

		} else {

			$this -> email = $email;

		}

		if (RepositorioUsuario :: compararUsuariosPorEmail($conexion, $email)) {

			return 'Este Mail ya está en uso';

		}

		return "";

	}

	private function validarPassword1($password1) {

		if (!$this -> variableIniciada($password1)) {

			return 'Debes escribir una Contraseña';

		}

		if (!mb_check_encoding($password1, 'ASCII')) {

			return 'La contraseña contiene caracteres no permitidos. Unicamente caracteres ASCII.';

		}

		return "";

	}

	private function validarPassword2($password1, $password2) {

		if (!$this -> variableIniciada($password1)) {

			return 'Debes escribir una Contraseña';

		}

		if (!$this -> variableIniciada($password2)) {

			return 'Debes repetir la Contraseña';

		}

		if ($password1 !== $password2) {

			return 'Las Contraseñas no coinciden';

		}

		return "";

	}

	public function getNombre() {

		return $this -> nombre;

	}

	public function getEmail() {

		return $this -> email;

	}

	public function getPassword() {

		return $this -> password;

	}

	public function getErrorNombre() {

		return $this -> errorNombre;

	}

	public function getErrorEmail() {

		return $this -> errorEmail;

	}

	public function getErrorPassword1() {

		return $this -> errorPassword1;

	}

	public function getErrorPassword2() {

		return $this -> errorPassword2;

	}

	public function registroValido() {

		/*===: para comparar el mismo tipo de dato*/

		if ($this -> errorNombre === '' && 
			$this -> errorEmail === '' &&
			$this -> errorPassword1 === '' &&
			$this -> errorPassword2 === '') {

			return true;

		} else {

			return false;

		}

	}

}