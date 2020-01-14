<?php

class Usuario {

	private $id;
	private $nombre;
	private $email;
	private $password;
	private $fechaRegistro;
	private $activo;
	private $registrado;

	function __construct($id, $nombre, $email, $password, $fechaRegistro, $activo, $registrado) {

		$this -> id = $id;
		$this -> nombre = $nombre;
		$this -> email = $email;
		$this -> password = $password;
		$this -> fechaRegistro = $fechaRegistro;
		$this -> activo = $activo;
		$this -> registrado = $registrado;

	}

	public function getId() {

		return $this -> id;

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

	public function getFechaRegistro() {

		return $this -> fechaRegistro;

	}

	public function getActivo() {

		return $this -> activo;

	}

	public function getRegistrado() {

		return $this -> registrado;

	}

	public function setNombre($nombre) {

		$this -> nombre = $nombre;

	}

	public function setEmail($email) {

		$this -> email = $email;

	}

	public function setPassword($password) {

		$this -> password = $password;

	}

	public function setActivo($activo) {

		$this -> activo = $activo;

	}

	public function setRegistrado($registrado) {

		$this -> registrado = $registrado;

	}


}