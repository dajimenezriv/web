<?php

class EntradaFavorita {

	private $id;
	private $usuarioId;
	private $entradaId;
	private $fecha;

	function __construct($id, $usuarioId, $entradaId, $fecha) {

		$this -> id = $id;
		$this -> usuarioId = $usuarioId;
		$this -> entradaId = $entradaId;
		$this -> fecha = $fecha;

	}

	public function getId() {

		return $this -> id;

	}

	public function getUsuarioId() {

		return $this -> usuarioId;

	}

	public function getEntradaId() {

		return $this -> entradaId;

	}

	public function getFecha() {

		return $this -> fecha;

	}

}