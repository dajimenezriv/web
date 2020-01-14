<?php

include_once 'validaciones/ValidacionEntrada.inc.php';

class ValidacionNuevaEntrada extends ValidacionEntrada {

	function __construct($conexion, $imagen, $titulo, $url, $texto) {

		$this -> error = '';

		$this -> titulo = $titulo;
		$this -> url = $url;
		$this -> texto = $texto;

		if (!$this -> variableIniciada($imagen) || !$this -> variableIniciada($titulo) || !$this -> variableIniciada($url) || !$this -> variableIniciada($texto)) {

			$this -> error = 'Debes introducir una Imagen, un Título y un Texto';

		} else {

			if (is_null($url) || RepositorioEntrada :: compararEntradaPorUrl($conexion, $url)) {

				$this -> error = 'La Url ya Existe'; 

			} else {

				if (is_null($titulo) || RepositorioEntrada :: compararEntradaPorTitulo($conexion, $titulo)) {

					$this -> error = 'El Título ya Existe'; 


				}

			}

		}

	}

}