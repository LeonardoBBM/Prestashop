<?php
include 'config.php';

// Obtener el ID del producto desde la URL
$id = $_GET['id'] ?? null;

// Validar si el ID existe
if (!$id) {
    die("Error: ID del producto no especificado.");
}

// Obtener los detalles del producto desde la API
$response = makeApiRequest("products/$id", 'GET');

if (!isset($response['product'])) {
    die("Error: Producto no encontrado.");
}

$product = $response['product'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);

    if (empty($name) || empty($price)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Crear XML con los datos actualizados
        $xml = new SimpleXMLElement('<prestashop/>');
        $productXml = $xml->addChild('product');
        $productXml->addChild('id', $id);
        $productXml->addChild('price', htmlspecialchars($price));

        // Actualizar nombre en idioma predeterminado (id=1)
        $nameElement = $productXml->addChild('name');
        $nameElement->addChild('language', htmlspecialchars($name))->addAttribute('id', '1');

        // Convertir a XML y enviar la solicitud PUT
        $xml_data = $xml->asXML();
        $updateResponse = makeApiRequest("products/$id", 'PUT', $xml_data);

        if (isset($updateResponse['product']['id'])) {
            header('Location: products.php');
            exit;
        } else {
            $error = "No se pudo actualizar el producto. Verifica los datos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Editar Producto</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del Producto:</label>
                <input type="text" name="name" value="<?= $name ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Precio:</label>
                <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price'] ?? '') ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">Actualizar Producto</button>
        </form>
        <a href="products.php" class="btn btn-secondary mt-3">Regresar a la lista de productos</a>
    </div>
</body>
</html>
