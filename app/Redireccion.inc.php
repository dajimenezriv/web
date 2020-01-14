<?php

class Redireccion {
	
	public static function redirigir($url) {

		header('Location: ' . $url, true, 301);

		// false/true: reescribir direccion
		// 301: codigo de redireccion
		// 404: codigo de error

		exit();
		
	}

}
