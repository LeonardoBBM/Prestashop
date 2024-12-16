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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Producto</title>
</head>
<body>
    <h1>Detalles del Producto</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID:</th>
            <td><?= htmlspecialchars($product['id'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <th>Nombre:</th>
            <td>
                <?php
                $name = 'Sin Nombre';
                if (isset($product['name']['language'])) {
                    $nameData = $product['name']['language'];
                    $name = is_array($nameData) ? htmlspecialchars($nameData[0]) : htmlspecialchars($nameData);
                }
                echo $name;
                ?>
            </td>
        </tr>
        <tr>
            <th>Precio:</th>
            <td><?= htmlspecialchars($product['price'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <th>Activo:</th>
            <td><?= isset($product['active']) && $product['active'] == '1' ? 'Sí' : 'No'; ?></td>
        </tr>
        <tr>
            <th>Fecha de Creación:</th>
            <td><?= htmlspecialchars($product['date_add'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <th>Fecha de Actualización:</th>
            <td><?= htmlspecialchars($product['date_upd'] ?? 'N/A'); ?></td>
        </tr>
    </table>
    <br>
    <a href="products.php">Regresar a la lista de productos</a>
</body>
</html>
