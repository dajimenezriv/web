<?php

/*****************INCLUDES*****************/

include_once 'app/Conexion.inc.php';
include_once 'app/ControlSesion.inc.php';
include_once 'app/Redireccion.inc.php';
include_once 'app/config.inc.php';

include_once 'objetos/Usuario.inc.php';
include_once 'objetos/Entrada.inc.php';
include_once 'objetos/Comentario.inc.php';
include_once 'objetos/Recuperacion.inc.php';
include_once 'objetos/EntradaFavorita.inc.php';
include_once 'objetos/Extra.inc.php';
include_once 'objetos/Novedad.inc.php';

include_once 'objetos/RepositorioUsuario.inc.php';
include_once 'objetos/RepositorioEntrada.inc.php';
include_once 'objetos/RepositorioComentario.inc.php';
include_once 'objetos/RepositorioRecuperacion.inc.php';
include_once 'objetos/RepositorioEntradaFavorita.inc.php';
include_once 'objetos/RepositorioExtra.inc.php';

include_once 'validaciones/ValidacionRegistro.inc.php';
include_once 'validaciones/ValidacionLogin.inc.php';
include_once 'validaciones/ValidacionEntrada.inc.php';
include_once 'validaciones/ValidacionNuevaEntrada.inc.php';
include_once 'validaciones/ValidacionEditarEntrada.inc.php';
include_once 'validaciones/ValidacionComentario.inc.php';
include_once 'validaciones/ValidacionContacto.inc.php';

/*****************RUTA*****************/

$componentesUrl = parse_url($_SERVER["REQUEST_URI"]);

$ruta = $componentesUrl['path'];

if (SERVIDOR == "http://localhost/geek/" || SERVIDOR == "http://192.168.1.103/geek/") {

	$parte0 = 1;

} else {

	$parte0 = 0;

}

$parte1 = $parte0 + 1;
$parte2 = $parte1 + 1;
$parte3 = $parte2 + 1;

$partesRuta = explode("/", $ruta);
$partesRuta = array_filter($partesRuta);
$partesRuta = array_slice($partesRuta, 0);

$titulo = "Error";
$rutaElegida = 'inicio/404.php';
$estilos = "error.css";
$textoBuscado = "";

/*****************ELEGIR RUTA*****************/

switch (count($partesRuta)) {

	case $parte0:

		$titulo = 'Geek';
		$estilos = 'inicio.css';
		$rutaElegida = 'inicio/inicio.php';

		break;

	case $parte1:

		switch ($partesRuta[$parte0]) {

			case 'registro':

				$titulo = 'Registro';
				$estilos = 'registro.css';
				$rutaElegida = 'cuenta/registro.php'; 
				break;

			case 'gestor': 

				if (ControlSesion :: sesionIniciada()) {

					if ($_SESSION['nombreUsuario'] === 'Zepovop') {

						$titulo = 'Gestor';
						$estilos = 'gestor.css';
						$rutaElegida = 'opciones/gestor.php'; 

					}

				}

				break;

			case 'recuperar-password': 

				$titulo = 'Recuperar Contraseña';
				$estilos = 'recuperarPassword.css';
				$rutaElegida = 'cuenta/recuperarPassword.php'; 
				break;

			case 'como-funciona-geek': 

				$titulo = '¿Cómo Funciona Geek?';
				$estilos = 'entrada.css';
				$rutaElegida = 'inicio/comoFuncionaGeek.php'; 
				break;

			case 'perfil':

				if (ControlSesion :: sesionIniciada()) {

					$estilos = "cuenta.css";
					$titulo = $_SESSION['nombreUsuario'];
					$rutaElegida = "cuenta/cuenta.php";

				}

				break;

			case 'registro-correcto':

				if (ControlSesion :: sesionIniciada()) {

					$titulo = "Registro Correcto";
					$estilos = "registroCorrecto.css";
					$nombreRegistrado = $_SESSION['nombreUsuario'];
					$rutaElegida = "cuenta/registroCorrecto.php";

				}

				break;

			case 'eliminar-cuenta':

				if (ControlSesion :: sesionIniciada()) {

					$titulo = 'Eliminar Cuenta';
					$estilos = 'eliminarCuenta.css';
					$rutaElegida = 'cuenta/eliminarCuenta.php';

				}

				break;

		}

		break;

	case $parte2:

		switch ($partesRuta[$parte0]) {

			case 'perfil':

				if ($partesRuta[$parte1] == 'cerrar-sesion') {

					if (ControlSesion :: sesionIniciada()) {

						$nombreUsuario = $_SESSION['nombreUsuario'];

						ControlSesion :: cerrarSesion();

						$titulo = 'Cerrar Sesión';
						$estilos = 'cerrarSesion.css';
						$rutaElegida = 'cuenta/cerrarSesion.php';

					}

				}

				break;

			case 'entrada':

				$urlEntrada = $partesRuta[$parte1];
				$activa = $parte0;

				if ($urlEntrada != 'como-funciona-geek') {

					Conexion :: abrirConexion();

					$entrada = RepositorioEntrada :: getEntradaPorUrl(Conexion :: getConexion(), $urlEntrada, $activa);

					Conexion :: cerrarConexion();

					if ($entrada != null) {

						$titulo = $entrada -> getTitulo();
						$estilos = 'entrada.css';
						$rutaElegida = 'inicio/entrada.php';

					}

				} else {

					$titulo = "¿Cómo funciona Geek?";
					$estilos = "entrada.css";
					$rutaElegida = "inicio/comoFuncionaGeek.php";

				}

				break;

			case 'busqueda':

				$titulo = 'Búsqueda';
				$estilos = 'buscador.css';
				$textoBuscado = urldecode($partesRuta[$parte1]);
				$rutaElegida = 'inicio/buscador.php';
				break;

			case 'editar-entrada': 

				if (ControlSesion :: sesionIniciada()) {

					if ($_SESSION['nombreUsuario'] === 'Zepovop') {

						$urlEntrada = $partesRuta[$parte1];
					 	$activa = 0;

						Conexion :: abrirConexion();

						$entrada = RepositorioEntrada :: getEntradaPorUrl(Conexion :: getConexion(), $urlEntrada, $activa);

						Conexion :: cerrarConexion();

						if ($entrada != null) {

							$titulo = 'Editar "' . $entrada -> getTitulo() . '"';
							$estilos = 'editarEntrada.css';
							$rutaElegida = 'opciones/editarEntrada.php'; 

						}

					}

				}

				break;

			case 'password':

				$urlSecreta = $partesRuta[$parte1];

				Conexion :: abrirConexion();

				$recuperacionPassword = RepositorioRecuperacion :: getRecuperacionPasswordPorUrlSecreta(Conexion :: getConexion(), $urlSecreta);

				Conexion :: cerrarConexion();

				if ($recuperacionPassword != null) {

					$titulo = 'Recuperar Contraseña';
					$estilos = 'recuperarPassword.css';
					$rutaElegida = 'cuenta/cambioPassword.php';

				}

				break;

			case 'activacion':

				$urlSecreta = $partesRuta[$parte1];

				Conexion :: abrirConexion();

				$activacion = RepositorioRecuperacion :: getActivacionPorUrlSecreta(Conexion :: getConexion(), $urlSecreta);

				Conexion :: cerrarConexion();

				if ($activacion != null) {

					Conexion :: abrirConexion();

					RepositorioUsuario :: activarUsuario(Conexion :: getConexion(), $activacion -> getUsuarioId());

					RepositorioRecuperacion :: eliminarActivacionesPorUsuarioId(Conexion :: getConexion(), $activacion -> getUsuarioId());

					Conexion :: cerrarConexion();

					if (ControlSesion :: sesionIniciada()) {

						$estilos = "cuenta.css";
						$titulo = $_SESSION['nombreUsuario'];
						$rutaElegida = 'cuenta/cuenta.php';

					} else {

						$titulo = 'Registro';
						$estilos = 'registro.css';
						$rutaElegida = 'cuenta/registro.php';

					}

				}

				break;

			case 'hacking':

				$todasEntradas = null;
				$entradas = [];
				$pagina = null;

				try {

					$pagina = (int)$partesRuta[$parte1];

				} catch (Exception $e) {

					$e -> getMessage();

				}

				if ($pagina != 0 && $pagina != null && gettype($pagina) == 'integer') {

					$seccion = 'Hacking';

					$activa = $parte0;

					Conexion :: abrirConexion();

					$todasEntradas = RepositorioEntrada :: getEntradasPorSeccion(Conexion :: getConexion(), $seccion, $activa);

					Conexion :: cerrarConexion();

					if (isset($todasEntradas[$pagina * 10])) {

						for ($i = ($pagina * 10 - 10); $i < ($pagina * 10); $i++) {

							$entradas[] = $todasEntradas[$i];

						}

					} else {

						for ($i = ($pagina * 10 - 10); $i < (count($todasEntradas)); $i++) {

							$entradas[] = $todasEntradas[$i];

						}

					}

					$titulo = 'Hacking';
					$estilos = 'general.css';
					$rutaElegida = 'secciones/hacking/hacking.php';

				}

				break;

			case 'proyectos':

				$todasEntradas = null;
				$entradas = [];
				$pagina = (int)$partesRuta[$parte1];

				if ($pagina == 0) {

					Conexion :: abrirConexion();

					$proyecto = RepositorioEntrada :: getEntradaPorUrl(Conexion :: getConexion(), $partesRuta[$parte1], 1);

					Conexion :: cerrarConexion();

					if ($proyecto != null) {

						$titulo = $proyecto -> getTitulo();
						$estilos = '../secciones/proyectos/' . str_replace("-", "", $partesRuta[$parte1]) . "/" . str_replace("-", "", $partesRuta[$parte1]) . '.css';
						$rutaElegida = 'secciones/proyectos/' . str_replace("-", "", $partesRuta[$parte1]) . "/" . str_replace("-", "", $partesRuta[$parte1]) . ".php";

					}

				} else {

					$seccion = 'Proyectos';

					Conexion :: abrirConexion();

					$todasEntradas = RepositorioEntrada :: getEntradasPorSeccion(Conexion :: getConexion(), $seccion, 1);

					Conexion :: cerrarConexion();

					if (isset($todasEntradas[$pagina * 10])) {

						for ($i = ($pagina * 10 - 10); $i < ($pagina * 10); $i++) {

							$entradas[] = $todasEntradas[$i];

						}

					} else {

						for ($i = ($pagina * 10 - 10); $i < (count($todasEntradas)); $i++) {

							$entradas[] = $todasEntradas[$i];

						}

					}

					$titulo = 'Proyectos';
					$estilos = 'general.css';
					$rutaElegida = 'secciones/proyectos/proyectos.php';

				}

				break;

		}

		break;

	case $parte3:

		switch ($partesRuta[$parte0]) {

			case "proyectos":

				if ($partesRuta[$parte2] == "download") {

					Conexion :: abrirConexion();

					$proyecto = RepositorioEntrada :: getEntradaPorUrl(Conexion :: getConexion(), $partesRuta[$parte1], 1);

					Conexion :: cerrarConexion();

					if ($proyecto != null) {

						$titulo = $proyecto -> getTitulo();
						$estilos = "";
						$rutaElegida = 'secciones/proyectos/' . str_replace("-", "", $partesRuta[$parte1]) . "/download.php";

					}
				}

				break;
		}

}

/*****************VARIABLES GENERALES*****************/

$rutaFavicon = SERVIDOR . 'imagenes/favicon.ico';
$rutaImagenWeb = SERVIDOR . 'imagenes/icono.png';

$rutaDefectoCSS = SERVIDOR . 'css/defecto.css';
$rutaEstilosCSS = SERVIDOR . 'css/' . $estilos;

$rutaBuscadorJS = SERVIDOR . 'js/buscador.js';
$rutaComentariosJS = SERVIDOR . 'js/perfil.js';
$rutaGestorJS = SERVIDOR . 'js/gestor.js';

$rutaInicio = SERVIDOR;
$rutaRegistro = SERVIDOR . 'registro';
$rutaHacking = SERVIDOR . 'hacking/1';
$rutaProyectos = SERVIDOR . 'proyectos/1';
$rutaGestorPHP = SERVIDOR . 'gestor';
$rutaCerrarSesion = SERVIDOR . 'perfil/cerrar-sesion';
$rutaRecuperarPassword = SERVIDOR . 'recuperar-password';
$rutaEliminarCuenta = SERVIDOR . 'eliminar-cuenta';
$rutaComoFuncionaGeek = SERVIDOR . 'como-funciona-geek';

Conexion :: abrirConexion();

$numUsuarios = RepositorioUsuario :: getNumeroUsuarios(Conexion :: getConexion());

Conexion :: cerrarConexion();	

if (ControlSesion :: sesionIniciada()) {

	$imagen = 'cuenta.png';
	$nombreUsuario = $_SESSION['nombreUsuario'];
	$idUsuario = $_SESSION['idUsuario'];

} else {

	$imagen = 'iniciarSesion.png';

}

$rutaImagenRegistro = SERVIDOR . 'imagenes/' . $imagen;

/*****************WEB*****************/

include_once 'plantillas/inicioDocumento.inc.php';
include_once $rutaElegida;
include_once 'plantillas/cierreDocumento.inc.php';
