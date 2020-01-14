<?php

class Entrada {

	private $id;
	private $autorId;
	private $url;
	private $titulo;
	private $imagen;
	private $seccion;
	private $texto;
	private $fecha;
	private $activa;

	function __construct($id, $autorId, $url, $seccion, $imagen, $titulo, $texto, $fecha, $activa) {

		$this -> id = $id;
		$this -> autorId = $autorId;
		$this -> url = $url;
		$this -> seccion = $seccion;
		$this -> imagen = $imagen;
		$this -> titulo = $titulo;
		$this -> texto = $texto;
		$this -> fecha = $fecha;
		$this -> activa = $activa;

	}

	public function getId() {

	
		return $this -> id;
	
	}

	public function getAutorId() {
	
		return $this -> autorId;
	
	}

	public function getUrl() {
	
		return $this -> url;
	
	}

	public function getSeccion() {
	
		return $this -> seccion;
	
	}

	public function getImagen() {

		return $this -> imagen;

	}	

	public function getTitulo() {
	
		return $this -> titulo;
	
	}

	public function getTexto() {
	
		return $this -> texto;
	
	}

	public function getFecha() {
	
		return $this -> fecha;
	
	}

	public function getActiva() {

		return $this -> activa;

	}

	public function setUrl($url) {
	
		$this -> url = $url;
	
	}

	public function setSeccion($seccion) {
	
		$this -> seccion = $seccion;
	
	}

	public function setImagen($imagen) {
	
		$this -> imagen = $imagen;
	
	}	

	public function setTitulo($titulo) {
	
		$this -> titulo = $titulo;
	
	}

	public function setTexto($texto) {
	
		$this -> texto = $texto;
	
	}

	public function setActiva($activa) {
	
		$this -> activa = $activa;
	
	}

}