<?php

class ValidacionContacto {

	private $error;

	function __construct($conexion, $titulo, $mensaje) {

		$this -> error = '';

		if (!($this -> variableIniciada($titulo)) || !($this -> variableIniciada($mensaje))) {

			$this -> error = 'Debes introducir un Título y un Mensaje';

		}

	}

	private function variableIniciada($variable) {

		if (isset($variable) && !empty($variable)) {

			return true;

		} else {

			return false;

		}

	}

	public function getError() {

		return $this -> error;

	}

}