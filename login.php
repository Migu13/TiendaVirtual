<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tiendavirtual";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($contrasena, $row['contrasena'])) {
        $_SESSION['usuario'] = $usuario;
        
        $sql = "SELECT * FROM clientes WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $cliente = $stmt->get_result()->fetch_assoc();
        
        $_SESSION['nombre'] = $cliente['nombre'];
        $_SESSION['email'] = $cliente['email'];
        
        header("Location: tienda.php");
    } else {
        echo "<script>alert('Contraseña incorrecta.'); window.location.href = 'Login.html';</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado.'); window.location.href = 'Login.html';</script>";
}

$conn->close();
?>