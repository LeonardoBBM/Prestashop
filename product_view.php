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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Detalles del Producto</h1>
        <table class="table table-bordered">
            <tr>
                <th>ID:</th>
                <td><?= htmlspecialchars($product['id'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Nombre:</th>
                <td><?= $name; ?></td>
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
        <a href="products.php" class="btn btn-secondary">Regresar a la lista de productos</a>
    </div>
</body>
</html>
