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
</head>
<body>
    <h1>Editar Producto</h1>
    <?php if (isset($error)): ?>
        <div style="color: red;"><?= $error ?></div>
    <?php endif; ?>

    <!-- Formulario para editar el producto -->
    <form method="post">
        <div>
            <label for="name">Nombre del Producto:</label>
            <?php
            $name = 'Sin Nombre';
            if (isset($product['name']['language'])) {
                $nameData = $product['name']['language'];
                $name = is_array($nameData) ? htmlspecialchars($nameData[0]) : htmlspecialchars($nameData);
            }
            ?>
            <input type="text" name="name" value="<?= $name ?>" required>
        </div>
        <br>
        <div>
            <label for="price">Precio:</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price'] ?? '') ?>" required>
        </div>
        <br>
        <button type="submit">Actualizar Producto</button>
    </form>
    <br>
    <a href="products.php">Regresar a la lista de productos</a>
</body>
</html>
