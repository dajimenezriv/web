var entradas = document.getElementById("entradas");
var nuevaEntrada = document.getElementById("nuevaEntrada");
var extras = document.getElementById("extras");

var tituloFormulario = document.getElementById("tituloFormulario");

var listaEntradas = document.getElementById("listaEntradas");
var formEditar = document.getElementById("formNuevaEntrada");
var formExtras = document.getElementById("formExtras");

function iniciar() {

	entradas.addEventListener("click", function() {

		tituloFormulario.innerHTML = "Entradas";
		formExtras.style.display = "none";
		formEditar.style.display = "none";
		listaEntradas.style.display = "block";

	}, false);

	nuevaEntrada.addEventListener("click", function() {

		tituloFormulario.innerHTML = "Nueva Entrada";
		formExtras.style.display = "none";
		listaEntradas.style.display = "none";
		formEditar.style.display = "block";

	}, false);

	extras.addEventListener("click", function() {

		tituloFormulario.innerHTML = "Extras";
		formEditar.style.display = "none";
		listaEntradas.style.display = "none";
		formExtras.style.display = "block";

	}, false);

}

window.addEventListener("load", iniciar, false);