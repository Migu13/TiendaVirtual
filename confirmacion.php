<?php
session_start();


if (empty($_SESSION["carrito"])) {
    header("Location: tienda.php");
    exit();
}


$total = 0;
foreach ($_SESSION["carrito"] as $item) {
    $total += $item["precio"] * $item["cantidad"];
}


unset($_SESSION["carrito"]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Compra Exitosa - TechZone</title>
    <link rel="stylesheet" href="Estilos/confirmacion.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="imagenes\logo.jpg" alt="Logo de TechZone">
    </div>
    <nav class="menu">
        <ul>
            <li><a href="logout.php">Log-Out</a></li>
            <li><a href="tienda.php">Tienda</a></li>
        </ul>
    </nav>
</header>

<div class="contenedor-confirmacion">
    <div class="tarjeta-confirmacion">
        <div class="icono-exito">✓</div>
        <h1>¡Compra realizada con éxito!</h1>
        <p>Total pagado: $<?php echo number_format($total, 2); ?></p>
        <p>Tu pedido llegará en 7 días laborales aproximadamente.</p>
        <p>Hemos enviado un correo con los detalles de tu compra.</p>
        <a href="tienda.php" class="boton-volver">Volver a la Tienda</a>
    </div>
</div>
</body>
</html>