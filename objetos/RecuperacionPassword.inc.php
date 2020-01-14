<?php

class RepositorioPassword {

	private $id;
	private $usuarioId;
	private $urlSecreta;
	private $fecha;

	function __construct($id, $usuarioId, $urlSecreta, $fecha) {

		$this -> id = $id;
		$this -> usuarioId = $usuarioId;
		$this -> urlSecreta = $urlSecreta;
		$this -> fecha = $fecha;

	}

}