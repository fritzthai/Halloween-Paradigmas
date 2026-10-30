CREATE DATABASE IF NOT EXISTS halloween CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE halloween;
CREATE TABLE disfraces (
 id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 nombre varchar(50) NOT NULL,
 descripcion text NOT NULL,
 votos int(11) NOT NULL,
 foto varchar(20) NOT NULL,
 foto_blob blob NOT NULL,
 eliminado int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB;
CREATE TABLE usuarios (
 id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 nombre varchar(50) NOT NULL,
 clave text NOT NULL,
 UNIQUE (nombre)
) ENGINE=InnoDB;
CREATE TABLE votos (
 id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 id_usuario int(11) NOT NULL,
 id_disfraz int(11) NOT NULL,
 UNIQUE (id_usuario, id_disfraz),
 FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
 FOREIGN KEY (id_disfraz) REFERENCES disfraces(id)
) ENGINE=InnoDB;
INSERT INTO usuarios (nombre, clave) VALUES ('admin', '$2b$12$1nDeLl/4OlOq08Yjlp6XFe4cmCSbdIY1k6dC.2KkfMMDVpUba8Op6');
