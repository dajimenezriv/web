<?php

class Extra {

	private $id;
	private $advertencia;
	private $consejo;
	private $sabiasQue;
	private $fecha;

	function __construct($id, $advertencia, $consejo, $sabiasQue, $fecha) {

		$this -> id = $id;
		$this -> advertencia = $advertencia;
		$this -> consejo = $consejo;
		$this -> sabiasQue = $sabiasQue;
		$this -> fecha = $fecha;

	}

	public function getId() {

		return $this -> id;

	}

	public function getAdvertencia() {

		return $this -> advertencia;

	}

	public function getConsejo() {

		return $this -> consejo;

	}

	public function getSabiasQue() {

		return $this -> sabiasQue;

	}

	public function getFecha() {

		return $this -> fecha;

	}

}