<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Crear el XML para el nuevo producto
    $xml = new SimpleXMLElement('<prestashop/>');
    $product = $xml->addChild('product');
    $product->addChild('active', 1);

    // Nombre del producto
    $name = $product->addChild('name');
    $language = $name->addChild('language', $_POST['name']);
    $language->addAttribute('id', '1'); // ID de idioma (1 para inglés)

    // Precio del producto
    $product->addChild('price', $_POST['price']);

    // Descripción del producto
    $description = $product->addChild('description');
    $desc_language = $description->addChild('language', $_POST['description']);
    $desc_language->addAttribute('id', '1'); // ID de idioma

    // Convertir el XML en una cadena para enviar
    $xml_data = $xml->asXML();

    // Llamada a la función de la API para crear el producto
    $response = makeApiRequest('products', 'POST', $xml_data);

    // Verificar la respuesta
    if (isset($response->product->id)) {
        header('Location: products.php'); // Redirigir a la lista de productos
        exit;
    } else {
        // Mostrar el error de respuesta en caso de fallo
        $error = "No se pudo crear el producto. Verifica los datos ingresados.";
        if (isset($response['error'])) {
            $error .= " Detalles: " . htmlspecialchars($response['error']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Producto</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Crear Producto</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="name">Nombre del Producto:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" step="0.01" class="form-control" name="price" required>
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Crear Producto</button>
        </form>
        <a href="products.php" class="btn btn-secondary mt-2">Regresar a Productos</a>
    </div>
</body>
</html>
