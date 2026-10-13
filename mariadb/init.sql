CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('user','admin') DEFAULT 'user',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tareas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  completada TINYINT(1) DEFAULT 0,
  creada_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- ⚠️ SUSTITUIR por los hashes generados en el Paso 2
INSERT INTO usuarios (email, password_hash, rol) VALUES
('admin@demo.com', '$2y$10$oxdHvTmY8Lu.RUG.RETr8eftl57EW8adV/BzATHNLaDYu.gKDrOBC', 'admin'),
('user@demo.com',  '$2y$10$w87/uATFWhW7WUkl3Gmyc.TFNr5XAC.yKFayam/YDJ4JIw46wU466', 'user');