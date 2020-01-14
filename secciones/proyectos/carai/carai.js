var aiPos = 110;			// Posicion del coche en la carretera. 125 px abajo (carretera 300 px | coche 50 px)
var walls = 0;				// Muros
var previous_crash = 0;		// Anteriores crashes
var crash = 0;				// Crashes
var avoid = 0;				// Muros esquivados
var tick_interval;			// Accion cada X segundos
var car_speed = 10;			// Velocidad del coche
var wall_speed = 20;		// Velocidad del muro
var offset = 0;				// OffsetTop hasta la carretera (barra de navegacion + titulo + cesped)
var state = 0

/**** START ****/

function run_sim(go) {
	if (state == 0 && go == 1) {
		state = 1;
		document.getElementById("wall").style.display = "block";
		aiPos = 110;
		previous_crash = 0;
		crash = 0;
		avoid = 0;
		walls = 0;
		wall_speed = parseInt(document.getElementById("wall_speed").value);
		car_speed = parseInt(document.getElementById("car_speed").value);
		random_wall();
		tick_interval = setInterval("tick();", 20);
	} else if (state == 1 && go == 0){
		state = 0;
		clearInterval(tick_interval);
		document.getElementById("wall").style.display = "none";
		document.getElementById("wall").style.left = null;
		document.getElementById("wall").style.right = "0px";
		document.getElementById("ai").style.marginTop = "110px";
	}
}

/**** TICK ****/

function tick() {
	move_wall();
	check_collision();
}

/**** WALL MOVEMENT****/

function random_wall() {
	var randomPos = Math.floor(Math.random() * (200 + 1)); // [0, 1)
	document.getElementById("wall").style.marginTop = randomPos + "px";
}

function move_wall() {
	var wallX = document.getElementById("wall").offsetLeft;
	var wallY = document.getElementById("wall").offsetTop;
	if (wallX <= 0) {							// El muro ya ha pasado
		if (crash == previous_crash) {
			avoid++;
		} else {
			previous_crash++;
		}
		walls++;
		random_wall();
		document.getElementById("wall").style.left = null;
		document.getElementById("wall").style.right = "0px";
		document.getElementById("ai").style.backgroundColor = "transparent";
	} else {
		wallX = wallX - wall_speed;
		document.getElementById("wall").style.left = wallX + "px";
	}
}

/**** CAR MOVEMENT ****/

function check_collision() {
	offset = document.getElementById("road").offsetTop;
	var wallX = document.getElementById("wall").offsetLeft;
	var wallY = document.getElementById("wall").offsetTop - offset;
	if (wallX <= 1000) { 									// Esta en el rango del sensor
		if ((wallY+100) >= aiPos && wallY < (aiPos+80)) {				// Al menos toca el coche
			// Move
			if ((wallY+100) > 220) {
				move_car("up");
			} else if (wallY < 80) {
				move_car("down");
			} else {
				if ((wallY+50) >= (aiPos+40)) {
					move_car("up");
				} else {
					move_car("down");
				}
			}
			// Crash
			if (wallX < 100 && crash == previous_crash) {
				document.getElementById("ai").style.backgroundColor = "red";
				crash++;
			}
		} else {
			if (aiPos > 150 && (wallY+100) < (aiPos-car_speed)) {
				move_car("up");
			} else if (aiPos < 100 && wallY > (aiPos+80+car_speed)) {
				move_car("down");
			}
		}
	} else {
		if (aiPos < (110 - car_speed/2)) {
			move_car("down");
		} else if (aiPos > (110 + car_speed/2)) {
			move_car("up");
		}
	}
	var success = Math.floor((avoid/(avoid + crash)) * 100);
	document.getElementById("td_walls").innerHTML = walls;
	document.getElementById("td_crash").innerHTML = crash;
	document.getElementById("td_avoid").innerHTML = avoid;
	document.getElementById("td_success").innerHTML = success;
}

function move_car(direction) {
	if (direction == "down") {
		aiPos = aiPos + car_speed;
	} else {
		aiPos = aiPos - car_speed;
	}
	if (aiPos < 0) {
		aiPos = 0;
	}
	if (aiPos > 220) {
		aiPos = 220;
	}
	document.getElementById("ai").style.marginTop = aiPos + "px";
}