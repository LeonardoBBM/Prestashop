<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    // Validar que los campos no estén vacíos
    if (empty($name) || empty($price) || empty($quantity)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Crear el XML para la API de Prestashop
        $xml = new SimpleXMLElement('<prestashop/>');
        $product = $xml->addChild('product');

        // Añadir los campos requeridos
        $product->addChild('active', 1); // Producto activo
        $product->addChild('price', htmlspecialchars($price));
        
        // Nombre del producto con lenguaje predeterminado
        $nameElement = $product->addChild('name');
        $nameElement->addChild('language', htmlspecialchars($name))
                    ->addAttribute('id', '1'); // ID del idioma predeterminado (1 es español por defecto)

        // Cantidad del producto (Stock)
        $stockAvailable = $xml->addChild('stock_available');
        $stockAvailable->addChild('quantity', htmlspecialchars($quantity));

        // Convertir a XML
        $xml_data = $xml->asXML();
        $response = makeApiRequest('products', 'POST', $xml_data);

        // Verificar respuesta
        if (isset($response['product']['id'])) {
            header('Location: products.php');
            exit;
        } else {
            $error = "No se pudo crear el producto. Verifica los datos ingresados.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
</head>
<body>
    <h1>Crear Producto</h1>
    <?php if (isset($error)): ?>
        <div style="color: red;"><?= $error ?></div>
    <?php endif; ?>

    <!-- Formulario para crear un nuevo producto -->
    <form method="post">
        <div>
            <label for="name">Nombre del Producto:</label>
            <input type="text" name="name" required>
        </div>
        <br>
        <div>
            <label for="price">Precio:</label>
            <input type="number" step="0.01" name="price" required>
        </div>
        <br>
        <div>
            <label for="quantity">Cantidad:</label>
            <input type="number" name="quantity" required>
        </div>
        <br>
        <button type="submit">Crear Producto</button>
    </form>
    <br>
    <a href="products.php">Regresar a la lista de productos</a>
</body>
</html>
