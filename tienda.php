<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "tiendavirtual");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$imagenes = [
    'Laptop HP Pavilion' => 'imagenes\laptop.jpg',
    'Smartphone Samsung Galaxy S21' => 'imagenes\smartphone.jpg',
    'Teclado mecánico RGB' => 'imagenes\teclado.jpg',
    'Monitor 24" Full HD' => 'imagenes\monitor.jpg',
    'Auriculares inalámbricos Sony' => 'imagenes\auriculares.jpg',
    'Impresora Epson EcoTank' => 'imagenes\impresora.jpg',
    'Disco Duro SSD 1TB' => 'imagenes\ssd.jpg',
    'Ratón gaming Logitech' => 'imagenes\raton.jpg',
    'Altavoz Bluetooth JBL' => 'imagenes\altavoz.jpg',
    'Tablet Amazon Fire HD' => 'imagenes\tablet.jpg'
];

$descripciones = [
    'Laptop HP Pavilion' => 'Potente laptop con procesador Intel Core i7, 16GB RAM, 512GB SSD y pantalla Full HD de 15.6". Ideal para trabajo y entretenimiento.',
    'Smartphone Samsung Galaxy S21' => 'Elegante smartphone con pantalla AMOLED de 6.2", cámara triple de 64MP, 8GB RAM y 128GB almacenamiento. 5G integrado.',
    'Teclado mecánico RGB' => 'Teclado gaming mecánico con retroiluminación RGB personalizable, switches azules y diseño ergonómico para largas sesiones de juego.',
    'Monitor 24" Full HD' => 'Monitor IPS de 24" con resolución Full HD (1920x1080), 75Hz, tiempo de respuesta de 5ms y conexiones HDMI y DisplayPort.',
    'Auriculares inalámbricos Sony' => 'Auriculares Bluetooth con cancelación de ruido, 30 horas de autonomía, micrófono integrado y calidad de sonido excepcional.',
    'Impresora Epson EcoTank' => 'Impresora multifunción con sistema de tinta EcoTank, impresión a color, escáner y copiadora. Bajo coste por página.',
    'Disco Duro SSD 1TB' => 'SSD SATA de 1TB con velocidades de lectura/escritura de hasta 550MB/s, ideal para acelerar tu PC o consola.',
    'Ratón gaming Logitech' => 'Ratón gaming con sensor óptico de 16000 DPI, 8 botones programables, iluminación RGB y diseño ergonómico para diestros.',
    'Altavoz Bluetooth JBL' => 'Altavoz portátil con Bluetooth, resistencia al agua IPX7, 20 horas de autonomía y sonido estéreo potente y claro.',
    'Tablet Amazon Fire HD' => 'Tablet de 10.1" con pantalla Full HD, 32GB almacenamiento, Alexa integrada y hasta 12 horas de batería. Perfecta para multimedia.'
];


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["producto"])) {
    $referencia = $_POST["producto"];

    $stmt = $conexion->prepare("SELECT referencia, nombre, precio FROM productos WHERE referencia = ?");
    $stmt->bind_param("s", $referencia);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        
        
        if (!isset($_SESSION["carrito"])) {
            $_SESSION["carrito"] = array();
        }
        
        
        $encontrado = false;
        foreach ($_SESSION["carrito"] as &$item) {
            if ($item["referencia"] === $producto["referencia"]) {
                $item["cantidad"] += 1;
                $encontrado = true;
                break;
            }
        }
        
        if (!$encontrado) {
            $producto["cantidad"] = 1;
            $_SESSION["carrito"][] = $producto;
        }
    }

    $stmt->close();
    header("Location: tienda.php");
    exit();
}


if (isset($_GET["eliminar"])) {
    $indice = $_GET["eliminar"];
    if (isset($_SESSION["carrito"][$indice])) {
        unset($_SESSION["carrito"][$indice]);
        $_SESSION["carrito"] = array_values($_SESSION["carrito"]); 
    }
    header("Location: tienda.php");
    exit();
}

$sql = "SELECT referencia, nombre, precio FROM productos";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="tienda.css">
    <title>TechZone - Tienda</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="imagenes\logo.jpg" alt="Logo de TechZone">
    </div>
    <nav class="menu">
        <ul>
            <li><a href="logout.php">Log-Out</a></li>
            <li><a href="#carrito-contenido">Carrito (<?php echo isset($_SESSION["carrito"]) ? count($_SESSION["carrito"]) : 0; ?>)</a></li>
        </ul>
    </nav>
</header>

<div class="Contenedor">
    <h1>NUESTROS PRODUCTOS</h1>
    <div class="productos-grid">
        <?php
        if ($resultado->num_rows > 0) {
            while ($producto = $resultado->fetch_assoc()) {
                $nombre = $producto["nombre"];
                $imagen = $imagenes[$nombre] ?? 'placeholder.jpg';
                $descripcion = $descripciones[$nombre] ?? 'Descripción no disponible.';

                echo '<div class="producto">';
                echo '<img src="' . $imagen . '" alt="' . htmlspecialchars($nombre) . '">';
                echo '<h3>' . htmlspecialchars($nombre) . '</h3>';
                echo '<p class="descripcion">' . htmlspecialchars($descripcion) . '</p>';
                echo '<p class="precio">$' . number_format($producto["precio"], 2) . '</p>';
                echo '<form action="tienda.php" method="post">';
                echo '<input type="hidden" name="producto" value="' . htmlspecialchars($producto["referencia"]) . '">';
                echo '<input type="submit" class="boton-comprar" value="Añadir al carrito">';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        ?>
    </div>

    <div class="carrito" id="carrito-contenido">
        <h2>Tu Carrito</h2>
        <div>
            <?php
            $total = 0.0;
            if (!empty($_SESSION["carrito"])) {
                echo '<ul>';
                foreach ($_SESSION["carrito"] as $indice => $item) {
                    echo '<li>';
                    echo htmlspecialchars($item["nombre"]) . " - $" . number_format($item["precio"], 2);
                    echo " (Cantidad: " . $item["cantidad"] . ")";
                    echo ' <a href="tienda.php?eliminar=' . $indice . '" class="eliminar">[X]</a>';
                    echo '</li>';
                    $total += $item["precio"] * $item["cantidad"];
                }
                echo '</ul>';
            } else {
                echo "<p>Tu carrito está vacío.</p>";
            }
            ?>
        </div>
        <div class="total">
            <p>Total: $<?php echo number_format($total, 2); ?></p>
            <form action="pago.php" method="get">
                <input type="submit" class="boton-pagar <?php echo ($total > 0) ? 'habilitado' : ''; ?>" 
                      value="Proceder al pago" <?php echo ($total > 0) ? '' : 'disabled'; ?>>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php
$conexion->close();
?>