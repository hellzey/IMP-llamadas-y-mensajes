CREATE TABLE usuarios (
  id_usuario INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(255) NOT NULL UNIQUE,
  contra VARCHAR(255) NOT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  foto_perfil MEDIUMBLOB,
  fecha_nacimiento DATE,
  puntos INT DEFAULT 0
);

CREATE TABLE grupos (
  id_grupo INT PRIMARY KEY AUTO_INCREMENT,
  nombre_grupo VARCHAR(100) NOT NULL,
  id_maestro INT NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_maestro) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE miembros (
  id_miembro INT PRIMARY KEY AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  id_grupo INT NOT NULL,
  fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE CASCADE
); 

CREATE TABLE tareas (
  id_tarea INT PRIMARY KEY AUTO_INCREMENT,
  id_grupo INT NOT NULL,
  titulo VARCHAR(255) NOT NULL,
  descripcion TEXT,
  fecha_entrega DATE NOT NULL,
  puntos_totales INT NOT NULL,
  estado ENUM('pendiente', 'completada', 'vencida') DEFAULT 'pendiente',
  FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE CASCADE
);

CREATE TABLE entregas (
  id_entrega INT PRIMARY KEY AUTO_INCREMENT,
  id_tarea INT NOT NULL,
  id_alumno INT NOT NULL,
  fecha_entrega TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  puntos_obtenidos INT DEFAULT 0,
  entregado_a_tiempo TINYINT(1) DEFAULT 1,
  FOREIGN KEY (id_tarea) REFERENCES tareas(id_tarea) ON DELETE CASCADE,
  FOREIGN KEY (id_alumno) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE mensajes (
  id_mensaje INT PRIMARY KEY AUTO_INCREMENT,
  id_emisor INT NOT NULL,
  id_receptor INT NOT NULL,
  contenido TEXT NOT NULL,
  fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_emisor) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_receptor) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE publicaciones (
  id_publicacion INT PRIMARY KEY AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  mensaje TEXT NOT NULL,
  fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_grupo INT NOT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo) ON DELETE CASCADE
);


ALTER TABLE usuarios ADD COLUMN username VARCHAR(50) NOT NULL UNIQUE;
