<?php

include_once 'validaciones/ValidacionEntrada.inc.php';

class ValidacionEditarEntrada extends ValidacionEntrada {

	private $cambiosRealizados;

	private $tituloAnterior;
	private $urlAnterior;
	private $textoAnterior;

	function __construct($conexion, $imagenAnterior, $seccionAnterior, $tituloAnterior, $urlAnterior, $textoAnterior, $activaAnterior, $imagenNueva, $seccionNueva, $tituloNuevo, $urlNueva, $textoNuevo, $activaNueva) {

		$this -> error = '';

		$this -> titulo = $tituloNuevo;
		$this -> url = $urlNueva;
		$this -> texto = $textoNuevo;

		$this -> tituloAnterior = $tituloAnterior;
		$this -> urlAnterior = $urlAnterior;
		$this -> textoAnterior = $textoAnterior;

		if (!$this -> variableIniciada($imagenNueva) || !$this -> variableIniciada($tituloNuevo) || !$this -> variableIniciada($urlNueva) || !$this -> variableIniciada($textoNuevo)) {

			$this -> error = 'Debes introducir una Imagen, un Título y un Texto';

		} else {

			if ($this -> titulo == $this -> tituloAnterior && $this -> url == $this -> urlAnterior && $this -> texto == $this -> textoAnterior && $imagenNueva == $imagenAnterior && $seccionNueva == $seccionAnterior && $activaNueva == $activaAnterior) {

				$this -> cambiosRealizados = false;

				$this -> error = 'No hay Cambios Realizados';

			} else {

				$this -> cambiosRealizados = true;

				if (is_null($this -> url) || RepositorioEntrada :: compararEntradaPorUrl($conexion, $this -> url)) {

					if ($this -> url !== $this -> urlAnterior) {

						$this -> error = 'La Url ya Existe'; 

					}

				} else {

					if (is_null($this -> titulo) || RepositorioEntrada :: compararEntradaPorTitulo($conexion, $this -> titulo)) {

						if ($this -> titulo !== $this -> tituloAnterior) {

							$this -> error = 'El Título ya Existe'; 

						}

					}

				}

			}

		}

	}

	public function getCambios() {

		return $this -> cambiosRealizados;

	}

}