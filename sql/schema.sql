-- Crear base de datos
CREATE DATABASE IF NOT EXISTS discoteca_el_mono_rumbero;
USE discoteca_el_mono_rumbero;

-- Tabla usuarios
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    fecha_nacimiento DATE,
    password VARCHAR(255) NOT NULL,
    puntos_beneficio INT DEFAULT 0,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

-- Tabla eventos
CREATE TABLE eventos (
    id_evento INT PRIMARY KEY AUTO_INCREMENT,
    nombre_evento VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha_evento DATETIME NOT NULL,
    artista VARCHAR(150),
    genero_musical VARCHAR(100),
    imagen_url VARCHAR(255),
    precio_entrada DECIMAL(10,2),
    capacidad INT,
    estado ENUM('activo', 'cancelado', 'finalizado') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla entradas
CREATE TABLE entradas (
    id_entrada INT PRIMARY KEY AUTO_INCREMENT,
    id_evento INT NOT NULL,
    id_usuario INT,
    tipo_entrada ENUM('general', 'vip', 'palco') NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    codigo_qr VARCHAR(100) UNIQUE,
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activa', 'usada', 'cancelada') DEFAULT 'activa',
    FOREIGN KEY (id_evento) REFERENCES eventos(id_evento),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabla beneficios
CREATE TABLE beneficios (
    id_beneficio INT PRIMARY KEY AUTO_INCREMENT,
    nombre_beneficio VARCHAR(150) NOT NULL,
    descripcion TEXT,
    puntos_requeridos INT NOT NULL,
    descuento_porcentaje INT,
    tipo_beneficio ENUM('descuento', 'entrada_gratis', 'consumo_gratis', 'acceso_vip') NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

-- Tabla usuario_beneficios
CREATE TABLE usuario_beneficios (
    id_usuario_beneficio INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_beneficio INT NOT NULL,
    fecha_canje TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('canjeado', 'usado') DEFAULT 'canjeado',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_beneficio) REFERENCES beneficios(id_beneficio)
);

-- Insertar datos de ejemplo
INSERT INTO eventos (nombre_evento, descripcion, fecha_evento, artista, genero_musical, precio_entrada, capacidad) VALUES
('Noche de Reggaeton', 'La mejor noche de reggaeton con los hits del momento', '2025-11-15 22:00:00', 'DJ Perreke', 'Reggaeton', 30.00, 500),
('Fiesta Electrónica', 'Música electrónica hasta el amanecer', '2025-11-20 23:00:00', 'DJ Thunder', 'Electrónica', 40.00, 400);


INSERT INTO beneficios (nombre_beneficio, descripcion, puntos_requeridos, descuento_porcentaje, tipo_beneficio) VALUES
('Descuento 20%', 'Obtén 20% de descuento en tu próxima entrada', 100, 20, 'descuento'),
('Entrada Gratis VIP', 'Entrada gratis con acceso VIP', 500, 0, 'entrada_gratis'),
('Consumo Gratis', '2 tragos gratis en tu próxima visita', 200, 0, 'consumo_gratis'),
('Acceso VIP Mensual', 'Acceso VIP por un mes completo', 1000, 0, 'acceso_vip');