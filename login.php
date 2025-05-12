<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

     if (empty($usuario) || empty($contraseña)) {
        echo "Usuario y contraseña son obligatorios.";
    } else {
        try {
             $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['usuario' => $usuario]);
            $user = $stmt->fetch();

            if ($user && password_verify($contraseña, $user['contraseña'])) {
                echo "Login exitoso!";

                } else {
                echo "Credenciales incorrectas.";
            }
        } catch (PDOException $e) {
            echo "Error al iniciar sesión: " . $e->getMessage();
        }
    }
}



?>