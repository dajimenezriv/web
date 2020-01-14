<?php

abstract class ValidacionEntrada {

	protected $titulo;
	protected $url;
	protected $texto;

	protected $error;

	public function variableIniciada($variable) {

		if (isset($variable) && !empty($variable)) {

			return true;

		} else {

			return false;

		}

	}

	public function getError() {

		return $this -> error;

	}

	public function getTitulo() {

		return $this -> titulo;

	}

	public function getUrl() {

		return $this -> url;

	}

	public function getTexto() {

		return $this -> texto;

	}

}