<?php
session_start();


if (empty($_SESSION["carrito"]) || empty($_SESSION["usuario_id"])) {
    header("Location: tienda.php");
    exit();
}


$conexion = new mysqli("localhost", "root", "", "tiendavirtual");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
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
       
        $total = 0;
        foreach ($_SESSION["carrito"] as $item) {
            $total += $item["precio"] * $item["cantidad"];
        }
        
        
        $stmt = $conexion->prepare("INSERT INTO compras (usuario_id, direccion, ciudad, codigo_postal, total) 
                                   VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssd", $_SESSION["usuario_id"], $_POST["direccion"], 
                         $_POST["ciudad"], $_POST["cp"], $total);
        
        if ($stmt->execute()) {
            $compra_id = $conexion->insert_id;
            
            
            $stmt_detalle = $conexion->prepare("INSERT INTO detalles_compra 
                                              (compra_id, producto_referencia, cantidad, precio_unitario) 
                                              VALUES (?, ?, ?, ?)");
            
            foreach ($_SESSION["carrito"] as $item) {
                $stmt_detalle->bind_param("isid", $compra_id, $item["referencia"], 
                                         $item["cantidad"], $item["precio"]);
                $stmt_detalle->execute();
            }
            
            $stmt_detalle->close();
            
            
            header("Location: confirmacion.php");
            exit();
        } else {
            $error = "Error al procesar el pago. Por favor intente nuevamente.";
        }
        
        $stmt->close();
    } else {
        $error = "Por favor complete todos los campos requeridos";
    }
}

$conexion->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pago - TechZone</title>
    <link rel="stylesheet" href="Estilos/pago.css">
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
        <div class="error" style="color: red; padding: 10px; margin-bottom: 20px; background: #ffeeee; border-radius: 4px;">
            <?php echo $error; ?>
        </div>
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