CREATE TABLE usuarios (
  id_usuario INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre TEXT NOT NULL,
  correo TEXT NOT NULL UNIQUE,
  contraseña TEXT NOT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  foto_perfil BLOB,
  fecha_nacimiento DATE,
  puntos int DEFAULT 0
);

CREATE TABLE grupos (
  id_grupo INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre_grupo TEXT NOT NULL,
  id_maestro INTEGER NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_maestro) REFERENCES usuarios(id_usuario)
);

CREATE TABLE miembros (
  id_miembro INTEGER PRIMARY KEY AUTOINCREMENT,
  id_usuario INTEGER NOT NULL,
  id_grupo INTEGER NOT NULL,
  fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo)
); 

CREATE TABLE tareas (
  id_tarea INTEGER PRIMARY KEY AUTOINCREMENT,
  id_grupo INTEGER NOT NULL,
  titulo TEXT NOT NULL,
  descripcion TEXT,
  fecha_entrega DATE NOT NULL,
  puntos_totales INTEGER NOT NULL,
  estado TEXT CHECK(estado IN ('pendiente', 'completada', 'vencida')) DEFAULT 'pendiente',
  FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo)
);

CREATE TABLE entregas (
  id_entrega INTEGER PRIMARY KEY AUTOINCREMENT,
  id_tarea INTEGER NOT NULL,
  id_alumno INTEGER NOT NULL,
  fecha_entrega TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  puntos_obtenidos INTEGER DEFAULT 0,
  entregado_a_tiempo BOOLEAN DEFAULT 1,
  FOREIGN KEY (id_tarea) REFERENCES tareas(id_tarea),
  FOREIGN KEY (id_alumno) REFERENCES usuarios(id_usuario)
);

CREATE TABLE mensajes (
  id_mensaje INTEGER PRIMARY KEY AUTOINCREMENT,
  id_emisor INTEGER NOT NULL,
  id_receptor INTEGER NOT NULL,
  contenido TEXT NOT NULL,
  fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_emisor) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_receptor) REFERENCES usuarios(id_usuario)
);

CREATE TABLE publicaciones (
  id_publicacion INTEGER PRIMARY KEY AUTOINCREMENT,
  id_usuario INTEGER NOT NULL,
  mensaje TEXT NOT NULL,
  fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_grupo INTEGER NOT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo)
);
