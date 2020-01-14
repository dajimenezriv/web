<?php

class Recuperacion {

	private $id;
	private $tipo;
	private $usuarioId;
	private $urlSecreta;
	private $fecha;

	function __construct($id, $tipo, $usuarioId, $urlSecreta, $fecha) {

		$this -> id = $id;
		$this -> tipo = $tipo;
		$this -> usuarioId = $usuarioId;
		$this -> urlSecreta = $urlSecreta;
		$this -> fecha = $fecha;

	}

	public function getId() {

		return $this -> id;

	}

	public function getTipo() {

		return $this -> tipo;

	}

	public function getUrlSecreta() {

		return $this -> urlSecreta;

	}

	public function getUsuarioId() {

		return $this -> usuarioId;

	}

	public function getFecha() {

		return $this -> fecha;

	}

}