<?php

class ValidacionComentario {

	private $error;

	function __construct($conexion, $titulo, $texto) {

		$this -> error = '';

		if (!($this -> variableIniciada($titulo)) || !($this -> variableIniciada($texto))) {

			$this -> error = 'Debes introducir un Título y un Texto';

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