create DATABASE tiendavirtual;
use tiendavirtual;

CREATE TABLE usuarios (
    usuario varchar(50) PRIMARY KEY,
    contrasena varchar(100) not null
    );

CREATE TABLE clientes (
    id int AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    nombre varchar(50) NOT null,
    contrasena VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    apellidos varchar(100) not null,
    fecha_nacimiento date,
    genero ENUM('Masculino', 'Femenino', 'Otro')
    );

CREATE TABLE productos (
    referencia varchar(20) PRIMARY KEY,
    nombre varchar(100) not null,
    precio decimal(10, 2) not null
    );

INSERT into productos (referencia, nombre, precio) VALUES
('PROD001', 'Laptop HP Pavilion', 899.99),
('PROD002', 'Smartphone Samsung Galaxy S21', 749.99),
('PROD003', 'Teclado mecánico RGB', 89.99),
('PROD004', 'Monitor 24" Full HD', 199.99),
('PROD005', 'Auriculares inalámbricos Sony', 129.99),
('PROD006', 'Impresora Epson EcoTank', 299.99),
('PROD007', 'Disco Duro SSD 1TB', 119.99),
('PROD008', 'Ratón gaming Logitech', 59.99),
('PROD009', 'Altavoz Bluetooth JBL', 79.99),
('PROD010', 'Tablet Amazon Fire HD', 149.99);

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    direccion VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(20) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES clientes(id)
);

CREATE TABLE detalles_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT NOT NULL,
    producto_referencia VARCHAR(20) NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (compra_id) REFERENCES compras(id),
    FOREIGN KEY (producto_referencia) REFERENCES productos(referencia)
);