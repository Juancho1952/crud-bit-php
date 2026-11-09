CREATE DATABASE IF NOT EXISTS mi_crud
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE mi_crud;

CREATE TABLE IF NOT EXISTS tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(120) NOT NULL,
    materia VARCHAR(100) NOT NULL,
    prioridad ENUM('Alta', 'Media', 'Baja') NOT NULL,
    horas_estimadas DECIMAL(6,2) NOT NULL DEFAULT 0.00
);

-- Registros iniciales (ejecutar este bloque una sola vez).
INSERT INTO tareas (titulo, materia, prioridad, horas_estimadas) VALUES
('Investigar modelo OSI', 'Redes', 'Alta', 3.00),
('Realizar mapa conceptual', 'Bases de datos', 'Media', 2.50),
('Terminar ejercicios de PHP', 'Programación', 'Alta', 4.00),
('Leer material de clase', 'Inglés', 'Baja', 1.00);
