<?php
include 'config.php';

// Obtener lista de productos (solo IDs)
$productsList = makeApiRequest('products', 'GET');

$products = [];
if (isset($productsList->products->product)) {
    foreach ($productsList->products->product as $product) {
        $productId = (string)$product['id'];
        // Obtener detalles del producto individualmente
        $productDetails = makeApiRequest("products/$productId", 'GET');
        
        if (isset($productDetails->product)) {
            $products[] = $productDetails->product;
        }
    }
} else {
    echo "No se encontraron productos.";
    exit;
}
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
    <a href="product_create.php">Crear Producto</a>
    <br><br>

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
                        <td><?= htmlspecialchars($product->id) ?></td>
                        <td>
                            <?= isset($product->name->language) 
                                ? htmlspecialchars($product->name->language) 
                                : 'Sin Nombre'; ?>
                        </td>
                        <td><?= htmlspecialchars($product->price) ?></td>
                        <td>
                            <a href="product_view.php?id=<?= htmlspecialchars($product->id) ?>">Ver</a> |
                            <a href="product_edit.php?id=<?= htmlspecialchars($product->id) ?>">Editar</a> |
                            <a href="product_delete.php?id=<?= htmlspecialchars($product->id) ?>">Eliminar</a>
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
