CREATE DATABASE geek DEFAULT CHARACTER SET utf8;

USE geek;

CREATE TABLE usuarios (
	
	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	nombre VARCHAR(25) NOT NULL UNIQUE,
	#VARCHAR = caracteres varios(número de caracteres)
	email VARCHAR(255) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	fecha_registro DATETIME NOT NULL,
	activo TINYINT NOT NULL,
	registrado TINYINT NOT NULL,
	PRIMARY KEY(id)

);

CREATE TABLE recuperacion (
	
	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	usuario_id INT NOT NULL,
	url_secreta VARCHAR(255) NOT NULL,
	tipo VARCHAR(255) NOT NULL,
	fecha DATETIME NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(usuario_id)
		REFERENCES usuarios(id)
		ON UPDATE CASCADE
		ON DELETE RESTRICT

);

CREATE TABLE entradas (

	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	autor_id INT NOT NULL,
	url VARCHAR(255) NOT NULL UNIQUE,
	seccion VARCHAR(255) NOT NULL,
	imagen VARCHAR(255) NOT NULL,
	titulo VARCHAR(255) NOT NULL UNIQUE,
	texto TEXT CHARACTER SET utf8 NOT NULL,
	fecha DATETIME NOT NULL,
	activa TINYINT NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(autor_id) 
		REFERENCES usuarios(id) 
		ON UPDATE CASCADE # Si se actualiza un usuario
		ON DELETE RESTRICT # Si se borra un usuario

);

CREATE TABLE entradas_favoritas (
	
	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	usuario_id INT NOT NULL,
	entrada_id INT NOT NULL,
	fecha DATETIME NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(usuario_id) 
		REFERENCES usuarios(id) 
		ON UPDATE CASCADE
		ON DELETE RESTRICT,
	FOREIGN KEY(entrada_id)
		REFERENCES entradas(id)
		ON UPDATE CASCADE
		ON DELETE RESTRICT

);

CREATE TABLE comentarios (

	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	autor_id INT NOT NULL,
	entrada_id INT NOT NULL,
	titulo VARCHAR(255) NOT NULL,
	texto TEXT CHARACTER SET utf8 NOT NULL,
	referencia INT NOT NULL,
	fecha DATETIME NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(autor_id) 
		REFERENCES usuarios(id) 
		ON UPDATE CASCADE # Si se actualiza un usuario
		ON DELETE RESTRICT, # Si se borra un usuario
	FOREIGN KEY(entrada_id)
		REFERENCES entradas(id)
		ON UPDATE CASCADE
		ON DELETE RESTRICT

);

CREATE TABLE extras (

	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	advertencia TEXT NOT NULL,
	consejo TEXT NOT NULL,
	sabias_que TEXT NOT NULL,
	fecha DATETIME NOT NULL

);

CREATE TABLE novedades (

	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	texto TEXT NOT NULL,
	enlace VARCHAR(255) NOT NULL,
	fecha DATETIME NOT NULL

);

INSERT INTO usuarios (id, nombre, email, password, fecha_registro, activo, registrado) VALUES (NULL, 'Zepovop', 'mail@gmail.com', '$2y$10$dOFF.KJ2HM0CZJIBWZnur.d2axkX/aotA2Adb24FJxBYGcZYBxNt2', '0000-00-00 00:00:00', '1', '1'); 
INSERT INTO `entradas` (`id`, `autor_id`, `url`, `seccion`, `imagen`, `titulo`, `texto`, `fecha`, `activa`) VALUES (NULL, '1', 'como-funciona-geek', 'General', 'http://localhost/geek/imagenes/imagenInicio.jpg', '¿Cómo funciona Geek?', 'Prueba', '2018-10-01 00:00:00', '1');
INSERT INTO `entradas` (`id`, `autor_id`, `url`, `seccion`, `imagen`, `titulo`, `texto`, `fecha`, `activa`) VALUES (NULL, '1', 'torres-hanoi', 'Proyectos', 'http://localhost/geek/imagenes/imagenInicio.jpg', 'Torres de Hanoi', '<a href="../proyectos/torres-hanoi">Torres Hanoi</a>', '2018-11-15 00:00:00', '1');
INSERT INTO `entradas` (`id`, `autor_id`, `url`, `seccion`, `imagen`, `titulo`, `texto`, `fecha`, `activa`) VALUES (NULL, '1', 'car-ai', 'Proyectos', 'http://localhost/geek/imagenes/imagenInicio.jpg', 'Car AI', '<a href="../proyectos/car-ai">Car AI</a>', '2018-11-15 00:00:00', '1');
INSERT INTO `entradas` (`id`, `autor_id`, `url`, `seccion`, `imagen`, `titulo`, `texto`, `fecha`, `activa`) VALUES (NULL, '1', 'prueba', 'Hacking', 'http://localhost/geek/secciones/hacking/imagenes/adios-box.jpg', 'Prueba', '<img src="http://localhost/geek/secciones/hacking/imagenes/adios-box.jpg">\r\nHola', '2018-11-08 00:00:00', '1');