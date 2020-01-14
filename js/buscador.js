var search = document.getElementById("search");
var buscador = document.getElementById("busqueda");

function iniciar() {

	search.addEventListener("focus", function() {

		if (search.value == "BÚSQUEDA") {

			search.value = "";

		}

	}, false);

	search.addEventListener("focusout", function() {

		if (search.value == "") {

			search.value = "BÚSQUEDA";

		}

	}, false);

	search.addEventListener("keypress", function(e) {

		if (e) {

			if (e.which == 13) {

				busqueda.submit();

			}

		}

	}, false);

}

window.addEventListener("load", iniciar, false);