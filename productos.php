<?php
include('conexion.php');

$sql = "SELECT * FROM productos";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll();

foreach ($productos as $producto) {
    echo "<div>";
    echo "<h3>" . $producto['nombre'] . "</h3>";
    echo "<p>Precio: " . $producto['precio'] . "€</p>";
    echo "<button>Comprar</button>";
    echo "</div>";
}
?>

?>