<?php

	header("Content-type: application/x-rar-compressed"); 
	header("Content-Disposition: attachment; filename=torreshanoi.rar");
	readfile(SERVIDOR . "secciones/proyectos/torreshanoi/torreshanoi.rar");

?>