<?php // Asegúrate de usar <?php en lugar de <? al inicio
$host = 'localhost';
$dbname = 'tiendavirtual';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>