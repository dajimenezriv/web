<?php

	header("Content-type: application/x-rar-compressed"); 
	header("Content-Disposition: attachment; filename=carai.rar");
	readfile(SERVIDOR . "secciones/proyectos/carai/carai.rar");

?>