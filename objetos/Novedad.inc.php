<?php

class Novedad {

	private $id;
	private $texto;
	private $enlace;
	private $fecha;

	function __construct($id, $texto, $enlace, $fecha) {
		
		$this -> id = $id;
		$this -> texto = $texto;
		$this -> enlace = $enlace;
		$this -> fecha = $fecha;
	
	}

	public function getId() {

		return $this -> id;

	}

	public function getTexto() {

		return $this -> texto;

	}

	public function getEnlace() {

		return $this -> enlace;

	}

	public function getFecha() {

		return $this -> fecha;

	}

}