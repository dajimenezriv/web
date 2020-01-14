		<section id="main">
		
			<div id="seccion">

				<h2>CAR AI</h2>

			</div>

			<div class="scene">

				<div class="grass">

					<table>

						<tr>
							<td>Walls:     </td>
							<td id="td_walls"></td>
						</tr>
						<tr>
							<td>Crashes:   </td>
							<td id="td_crash"></td>
						</tr>
						<tr>
							<td>Avoids:    </td>
							<td id="td_avoid"></td>
						</tr>
						<tr>
							<td>Success:   </td>
							<td id="td_success"></td>
						</tr>

					</table>

					<div class="debug_area">

						<input type="button" value="Start" onClick="run_sim('1')" />
						<input type="button" value="Stop" onClick="run_sim('0')" />

					</div>

					<div class="debug_area">

						<label for="wall_speed">Wall Speed: </label>
							<input type="text" value="20" id="wall_speed"/> 
						<label for="car_speed">Car Speed: </label>
							<input type="text" value="10" id="car_speed"/>

					</div>

				</div>

				<div class="road" id="road">

					<img src="<?php echo SERVIDOR . 'secciones/proyectos/carai/Car.png' ?>" class="ai" id="ai">

					<img src="<?php echo SERVIDOR . 'secciones/proyectos/carai/Wall.jpg' ?>" class="wall" id="wall">

					<div class="linea_blanca"></div>

				</div>

				<div class="grass"></div>

			</div>

			<script type="text/javascript" src="<?php echo SERVIDOR . 'secciones/proyectos/carai/carai.js'; ?>"></script>

		</section>