<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tiendavirtual";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recoger datos del formulario
$usuario = $_POST['usuario'];
$contrasena = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
$email = $_POST['email'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$genero = $_POST['genero'];

// Verificar si el usuario ya existe
$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('El nombre de usuario ya está en uso.'); window.location.href = 'Registrarse.html';</script>";
    exit();
}

// Verificar si el email ya existe
$sql = "SELECT * FROM clientes WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('El correo electrónico ya está registrado.'); window.location.href = 'Registrarse.html';</script>";
    exit();
}

// Insertar en la tabla usuarios
$sql = "INSERT INTO usuarios (usuario, `contrasena`) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario, $contrasena);

if ($stmt->execute()) {
    // Insertar en la tabla clientes
    $sql = "INSERT INTO clientes (usuario, nombre, `contrasena`, email, apellidos, fecha_nacimiento, genero) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $usuario, $nombre, $contrasena, $email, $apellidos, $fecha_nacimiento, $genero);
    
    if ($stmt->execute()) {
        echo "<script>alert('¡Registro completado con éxito!'); window.location.href = 'Login.html';</script>";
    } else {
        echo "<script>alert('Error al registrar en la tabla clientes: " . $conn->error . "'); window.location.href = 'Registrarse.html';</script>";
    }
} else {
    echo "<script>alert('Error al registrar en la tabla usuarios: " . $conn->error . "'); window.location.href = 'Registrarse.html';</script>";
}

$conn->close();
?>