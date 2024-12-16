<?php
include 'config.php';

// Función para obtener la lista de productos o realizar una búsqueda específica
function getProducts() {
    $productsList = makeApiRequest('products', 'GET');
    $products = [];

    // Verificar si la lista de productos contiene elementos
    if (isset($productsList['products']['product'])) {
        $productRefs = $productsList['products']['product'];
        foreach ($productRefs as $productRef) {
            $productId = $productRef['@attributes']['id'] ?? null;
            if ($productId) {
                // Obtener detalles individuales del producto
                $productDetails = makeApiRequest("products/$productId", 'GET');
                if (isset($productDetails['product'])) {
                    $products[] = $productDetails['product'];
                }
            }
        }
    }
    return $products;
}

// Obtener los productos
$products = getProducts();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
</head>
<body>
    <h1>Lista de Productos</h1>

    <!-- Botón para agregar un nuevo producto -->
    <a href="product_create.php">
        <button>Agregar Nuevo Producto</button>
    </a>
    <br><br>

    <!-- Tabla de productos -->
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['id'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($product['name']['language'] ?? 'Sin Nombre'); ?></td>
                        <td><?= htmlspecialchars($product['price'] ?? 'N/A'); ?></td>
                        <td>
                            <a href="product_view.php?id=<?= htmlspecialchars($product['id']); ?>">Ver</a> |
                            <a href="product_edit.php?id=<?= htmlspecialchars($product['id']); ?>">Editar</a> |
                            <a href="product_delete.php?id=<?= htmlspecialchars($product['id']); ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No se encontraron productos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
