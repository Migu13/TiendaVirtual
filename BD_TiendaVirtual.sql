create DATABASE tiendavirtual;
use tiendavirtual;

CREATE TABLE usuarios (
    usuario varchar(50) PRIMARY KEY,
    contraseña varchar(100) not null
    );

CREATE TABLE clientes (
    id int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) NOT null,
    apellidos varchar(100) not null,
    correo varchar(100) UNIQUE not null,
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