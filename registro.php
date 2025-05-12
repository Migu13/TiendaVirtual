<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento']; // Cambiado para coincidir con el name del formulario
    $genero = $_POST['genero'];

    if (empty($usuario) || empty($_POST['contraseña']) || empty($email) || 
        empty($nombre) || empty($apellidos) || empty($fecha_nacimiento) || empty($genero)) {
        die("Todos los campos son obligatorios.");
    }

    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Insertar en tabla clientes
        $sql_clientes = "INSERT INTO clientes 
                (usuario, nombre, contraseña, email, apellidos, fecha_nacimiento, genero) 
                VALUES 
                (:usuario, :nombre, :contraseña, :email, :apellidos, :fecha_nacimiento, :genero)";
        
        $stmt_clientes = $pdo->prepare($sql_clientes);
        $stmt_clientes->execute([
            'usuario' => $usuario,
            'nombre' => $nombre,
            'contraseña' => $contraseña,
            'email' => $email,
            'apellidos' => $apellidos,
            'fecha_nacimiento' => $fecha_nacimiento,
            'genero' => $genero
        ]);

        // Insertar en tabla usuarios
        $sql_usuarios = "INSERT INTO usuarios (usuario, contraseña) 
                        VALUES (:usuario, :contraseña)";
        
        $stmt_usuarios = $pdo->prepare($sql_usuarios);
        $stmt_usuarios->execute([
            'usuario' => $usuario,
            'contraseña' => $contraseña
        ]);

        // Confirmar transacción
        $pdo->commit();
        
        header("Location: login.php?registro=exitoso");
        exit();
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        die("Error al registrar usuario: " . $e->getMessage());
    }
}
?>