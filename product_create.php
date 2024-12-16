<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    if (empty($name) || empty($price) || empty($quantity)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Crear XML para el producto
        $xml = new SimpleXMLElement('<prestashop/>');
        $product = $xml->addChild('product');

        $product->addChild('active', 1); // Producto activo
        $product->addChild('price', htmlspecialchars($price));

        // Nombre del producto (lenguaje predeterminado: id=1)
        $nameElement = $product->addChild('name');
        $nameElement->addChild('language', htmlspecialchars($name))
                    ->addAttribute('id', '1');

        // Categoría predeterminada (obligatorio)
        $product->addChild('id_category_default', '3'); // ID de una categoría existente
        $product->addChild('id_tax_rules_group', '1'); // Grupo de impuestos predeterminado (opcional)

        // Guardar el XML y enviar a la API
        $xml_data = $xml->asXML();
        $response = makeApiRequest('products', 'POST', $xml_data);

        if (isset($response['product']['id'])) {
            $productId = $response['product']['id'];

            // Actualizar stock usando stock_availables
            $stockXml = new SimpleXMLElement('<prestashop/>');
            $stock = $stockXml->addChild('stock_available');
            $stock->addChild('id_product', $productId);
            $stock->addChild('quantity', htmlspecialchars($quantity));

            makeApiRequest('stock_availables', 'POST', $stockXml->asXML());

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Crear Producto</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del Producto:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Precio:</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Cantidad:</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Producto</button>
        </form>
        <a href="products.php" class="btn btn-secondary mt-3">Regresar a la lista de productos</a>
    </div>
</body>
</html>
