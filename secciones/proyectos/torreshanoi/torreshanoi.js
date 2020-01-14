var juego;
var botonConfirmar;
var inputFichas;

var cuadros = new Array();

var numeroFichas;
var anchoFicha = 10;
var alturaFicha = 40;

var fichaSeleccionada;
var origen;
var destino;

var movimientos = 0;

function crearCuadros(numeroFichas) {

	cuadros[0] = new Cuadro(true, numeroFichas);
	cuadros[1] = new Cuadro(false, numeroFichas);
	cuadros[2] = new Cuadro(false, numeroFichas);

	for (var i = 0; i < 3; i++) {

		juego.appendChild(cuadros[i].caja);

	}

	cuadros[0].caja.addEventListener("click", click1, false);
	cuadros[1].caja.addEventListener("click", click2, false);
	cuadros[2].caja.addEventListener("click", click3, false);

}

function click(cuadro) {

	if (cuadro.elegido) {

		seleccionarOrigenDestino(cuadro);

	} else {

		cuadro.caja.style.backgroundColor = "#FFFFFF";

		reiniciarOrigenDestino();

	}

}

function click1() {

	cuadros[0].elegido = !cuadros[0].elegido;

	click(cuadros[0]);

}

function click2() {

	cuadros[1].elegido = !cuadros[1].elegido;

	click(cuadros[1]);

}

function click3() {

	cuadros[2].elegido = !cuadros[2].elegido;

	click(cuadros[2]);

}

function Cuadro(cajaInicial, numeroFichas) {

	var alturaCuadro = (alturaFicha * (numeroFichas + 1) + 1 * numeroFichas * 2) + "px";

	this.caja = document.createElement("div");
	this.caja.style.width = "30%";
	this.caja.style.height = alturaCuadro;
	this.caja.style.margin = "1%";
	this.caja.style.border = "1px solid #000";
	this.caja.style.display = "inline-block";
	this.caja.style.borderRadius = "10px";
	this.caja.style.backgroundColor = "#fff";

	this.elegido = false;

	if (cajaInicial) {

		this.contenido = rellenarFichas(numeroFichas);

	} else {

		this.contenido = rellenarContenido(numeroFichas);

	}

	for (var i = 0; i < this.contenido.length; i++) {

		this.caja.appendChild(this.contenido[i].caja);

	}

	this.tieneFichas = function() {

		var rellenos = 0;
		
		for (var i = 0; i < this.contenido.length; i++) {

			if (this.contenido[i] instanceof /*Si contenido es del tipo Relleno*/ Relleno) {

				rellenos++;

			}

		}

		if (rellenos == this.contenido.length) {

			return false;

		} else {

			return true;

		}

	};

	this.fichaSuperior = function() {

		for (var i = 0; i < this.contenido.length; i++) {

			if (!(this.contenido[i] instanceof Relleno)) {

				return this.contenido[i];

			}

		}

	};

	this.quitarFichaSuperior = function() {

		for (var i = 0; i < this.contenido.length; i++) {

			if (!(this.contenido[i] instanceof Relleno)) {

				fichaSeleccionada = this.contenido[i];
				this.contenido[i] = new Relleno();

				break;

			}

		}	

	};

	this.insertarFichaSuperior = function() {

		for (var i = this.contenido.length - 1; i >= 0; i--) {

			if (this.contenido[i] instanceof Relleno) {

				this.contenido[i] = fichaSeleccionada;

				break;

			}

		}

	};

	this.redibujarCaja = function() {

		while (this.caja.hasChildNodes() /*Si el elemento tiene elementos dentro*/) {

			this.caja.removeChild(this.caja.lastChild);

		}

		for (var i = 0; i < this.contenido.length; i++) {

			this.caja.appendChild(this.contenido[i].caja);

		}

	};

}

function Ficha(anchoFicha, valorFicha) {

	this.caja = document.createElement("div");
	this.caja.style.width = anchoFicha + "%";
	this.caja.style.height = alturaFicha + "px";
	this.caja.style.backgroundColor = getRandomColor();
	this.caja.style.margin = "auto";
	this.caja.style.border = "1px solid #000";
	this.caja.style.borderBottom = "none";

	this.valor = valorFicha;

}

function getRandomColor() {

    var letters = '0123456789ABCDEF';
    var color = '#';

    for (var i = 0; i < 6; i++ ) {
    	/*Math.floor redondea*/
    	/*Math.random número entre 0 y 1*/
        color += letters[Math.floor(Math.random() * 16)];

    }

    return color;

}

function Relleno() {

	this.caja = document.createElement("div");
	this.caja.style.width = "100%";
	this.caja.style.height = alturaFicha + "px";

}

function rellenarContenido(numeroFichas) {

	var contenido = new Array();

	for (var i = 0; i < (numeroFichas + 1); i++) {

		contenido[i] = new Relleno();

	}

	return contenido;

}

function rellenarFichas(numeroFichas) {

	var contenido = new Array();
	var valorFicha = 0;

	contenido[0] = new Relleno();

	for (var i = 1; i < (numeroFichas + 1); i++) {

		contenido[i] = new Ficha(this.anchoFicha, valorFicha);

		this.anchoFicha += 10;

		valorFicha++;

	}

	return contenido;

}

function seleccionarOrigenDestino(cuadro) {

	if (origen == undefined /*No tiene ningún valor*/) {

		if (cuadro.tieneFichas()) {

			cuadro.caja.style.backgroundColor = "#ADFAFF";

			origen = cuadro;
			origen.elegido = true;

		}

	} else if (origen != undefined && destino == undefined) {

		destino = cuadro;
		destino.elegido = true;

		if (origen != destino) {

			if (!destino.tieneFichas() || (origen.fichaSuperior().valor < destino.fichaSuperior().valor)) {

				origen.quitarFichaSuperior();
				origen.redibujarCaja();

				destino.insertarFichaSuperior();
				destino.redibujarCaja();

				movimientos++;

				actualizarContador();

			}

		}

	}

	if (destino != undefined && origen != undefined) {

		reiniciarOrigenDestino(cuadros);

	}

	if (comprobarVictoria()) {

		victoria();

	}

}

function comprobarVictoria() {

	var bool = true;

	if (cuadros[2].contenido[0] instanceof Relleno) {

		for (var i = 1; i < (numeroFichas + 1); i++) {

			if (!(cuadros[2].contenido[i] instanceof Ficha)) {

				bool = false;

				return bool;

			}

		}	

	} else {

		return false;

	}

	if (bool) {

		return true;

	}

}

function victoria() {

	var movimientosMinimos = Math.pow(2, numeroFichas) - 1;

	var textoTitulo;

	if (movimientos == movimientosMinimos) {

		textoTitulo = document.createTextNode("¡HAS GANADO!");

	} else {

		textoTitulo = document.createTextNode("No has conseguido el mínimo número de movimientos");

	}

	var textoSubtitulo = document.createTextNode("Movimientos Utilizados: " + movimientos);
	var textoConsejo = document.createTextNode("Recarga la Página para volver a jugar");

	for (var i = 0; i < 3; i++) {

		juego.removeChild(cuadros[i].caja);

	}
	
	juego.removeChild(document.getElementById("contador"));

	var titulo = document.createElement("h2");

	titulo.appendChild(textoTitulo);

	titulo.style.marginTop = "3%";
	titulo.style.color = "red";
	titulo.style.fontFamily = "amatic";
	titulo.style.fontSize = "50px";
	titulo.style.fontWeight = "bold";

	var subtitulo = document.createElement("p");

	subtitulo.appendChild(textoSubtitulo);

	subtitulo.style.marginTop = "3%";
	subtitulo.style.fontFamily = "amatic";
	subtitulo.style.fontSize = "28px";
	subtitulo.style.fontWeight = "bold";

	var consejo = document.createElement("p");

	consejo.appendChild(textoConsejo);

	consejo.style.marginTop = "3%";
	consejo.style.marginBottom = "3%";
	consejo.style.fontFamily = "amatic";
	consejo.style.fontSize = "28px";
	consejo.style.fontWeight = "bold";

	juego.appendChild(titulo);
	juego.appendChild(subtitulo);
	juego.appendChild(consejo);
}

function reiniciarOrigenDestino() {

	if (origen != undefined) {

		origen.caja.style.backgroundColor = "#FFFFFF";
		origen.elegido = "false";

	}
	
	if (destino != undefined) {

		destino.caja.style.backgroundColor = "#FFFFFF";
		destino.elegido = "false";

	}
	
	origen = undefined;
	destino = undefined;

	cuadros[0].elegido = false;
	cuadros[1].elegido = false;
	cuadros[2].elegido = false;

}

function actualizarContador() {

	var parrafo = document.getElementById("contador");

	parrafo.innerHTML = "Movimientos: " + movimientos;

}

function iniciar() {

	juego = document.getElementById("juego");

	juego.style.borderTop = "none";
	juego.style.borderBottom = "4px solid #7aa3a1";

	var texto1 = document.createTextNode("MOVIMIENTOS MÍNIMOS: " + (Math.pow(2, numeroFichas) - 1));
	var parrafo1 = document.createElement("p");

	parrafo1.style.clear = "both";
	parrafo1.style.paddingTop = "3%";
	parrafo1.style.fontFamily = "amatic";
	parrafo1.style.fontSize = "28px";
	parrafo1.style.fontWeight = "bold";

	parrafo1.appendChild(texto1);
	juego.appendChild(parrafo1);

	crearCuadros(numeroFichas);

	var texto2 = document.createTextNode("Movimientos: " + movimientos);
	var parrafo2 = document.createElement("p");

	parrafo2.style.clear = "both";
	parrafo2.style.paddingBottom = "3%";
	parrafo2.style.fontFamily = "amatic";
	parrafo2.style.fontSize = "28px";
	parrafo2.style.fontWeight = "bold";

	parrafo2.setAttribute("id", "contador");
	parrafo2.appendChild(texto2);
	juego.appendChild(parrafo2);

}

function confirmar() {

	botonConfirmar = document.getElementById("confirmar");
	inputFichas = document.getElementById("numeroFichas");

	inputFichas.addEventListener("keypress", function(e) {

		if (e) {

			if (e.which == 13) {

				prepararJuego();

			}

		}

	}, false);

	botonConfirmar.addEventListener("click", prepararJuego, false);

}

function prepararJuego() {

	numeroFichas = parseInt(inputFichas.value);

	if (numeroFichas >= 2 && numeroFichas <= 8) {

		var formJuego = document.getElementById("torresHanoi");

		formJuego.style.display = "none";

		iniciar();

	}

}

window.addEventListener("load", confirmar, false);