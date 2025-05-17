<?php
session_start();

if (empty($_SESSION["carrito"])) {
    header("Location: tienda.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $required_fields = ['tarjeta', 'fecha', 'cvv', 'nombre', 'direccion', 'ciudad', 'cp'];
    $is_valid = true;
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $is_valid = false;
            break;
        }
    }
    
    if ($is_valid) {
        header("Location: confirmacion.php");
        exit();
    } else {
        $error = "Por favor complete todos los campos requeridos";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pago - TechZone</title>
    <link rel="stylesheet" href="pago.css">
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

<div class="contenedor-pago">
    <h1>FINALIZAR COMPRA</h1>
    
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="grid-pago">
        <section class="resumen-carrito">
            <h2>Tu Pedido</h2>
            <?php
            $total = 0;
            foreach ($_SESSION["carrito"] as $item) {
                $subtotal = $item["precio"] * $item["cantidad"];
                $total += $subtotal;
                echo '<div class="producto">';
                echo '<p>' . htmlspecialchars($item["nombre"]) . ' x' . $item["cantidad"] . ' <span>$' . number_format($subtotal, 2) . '</span></p>';
                echo '</div>';
            }
            ?>
            <div class="total">
                <p>Total <span>$<?php echo number_format($total, 2); ?></span></p>
            </div>
        </section>

        <section class="formulario-pago">
            <h2>Datos de Pago</h2>
            <form action="pago.php" method="post">
                <div class="grupo-formulario">
                    <label for="tarjeta">Número de Tarjeta</label>
                    <input type="text" id="tarjeta" name="tarjeta" placeholder="1234 5678 9012 3456" required>
                </div>

                <div class="grupo-doble">
                    <div class="grupo-formulario">
                        <label for="fecha">Fecha Exp.</label>
                        <input type="text" id="fecha" name="fecha" placeholder="MM/AA" required>
                    </div>
                    <div class="grupo-formulario">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" required>
                    </div>
                </div>

                <div class="grupo-formulario">
                    <label for="nombre">Nombre en la Tarjeta</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <h3>Dirección de Envío</h3>
                <div class="grupo-formulario">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>

                <div class="grupo-doble">
                    <div class="grupo-formulario">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" required>
                    </div>
                    <div class="grupo-formulario">
                        <label for="cp">Código Postal</label>
                        <input type="text" id="cp" name="cp" required>
                    </div>
                </div>

                <button type="submit" class="boton-pagar">Realizar Pago</button>
            </form>
        </section>
    </div>
</div>
</body>
</html>