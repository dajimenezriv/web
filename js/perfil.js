var comentarios = document.getElementById("comentariosEscritos");
var tituloComentarios = document.getElementById("tituloComentarios");

var entradasFavoritas = document.getElementById("entradasFavoritas");
var tituloEntradasFavoritas = document.getElementById("tituloEntradasFavoritas");

var estadoComentarios = 0;
var estadoEntradasFavoritas = 0;

function iniciar() {

	tituloComentarios.addEventListener("click", function() {

		if (estadoComentarios) {

			comentarios.style.display = "none";
			tituloComentarios.style.backgroundColor = "#fff";
			tituloComentarios.style.color = "#7aa3a1";


		} else {

			if (estadoEntradasFavoritas == "1") {

				entradasFavoritas.style.display = "none";
				tituloEntradasFavoritas.style.backgroundColor = "#fff";
				tituloEntradasFavoritas.style.color = "#7aa3a1";

				estadoEntradasFavoritas = !estadoEntradasFavoritas;

			}

			comentarios.style.display = "block";
			tituloComentarios.style.backgroundColor = "#abc2e8";
			tituloComentarios.style.color = "#000";

		}

		estadoComentarios = !estadoComentarios;

	}, false);

	tituloEntradasFavoritas.addEventListener("click", function() {

		if (estadoEntradasFavoritas) {

			entradasFavoritas.style.display = "none";
			tituloEntradasFavoritas.style.backgroundColor = "#fff";
			tituloEntradasFavoritas.style.color = "#7aa3a1";

		} else {

			if (estadoComentarios == 1) {

				comentarios.style.display = "none";
				tituloComentarios.style.backgroundColor = "#fff";
				tituloComentarios.style.color = "#7aa3a1";

				estadoComentarios = !estadoComentarios;

			}

			entradasFavoritas.style.display = "block";
			tituloEntradasFavoritas.style.backgroundColor = "#abc2e8";
			tituloEntradasFavoritas.style.color = "#000";

		}

		estadoEntradasFavoritas = !estadoEntradasFavoritas;

	}, false);

}

window.addEventListener("load", iniciar, false);