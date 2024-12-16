<?php
include 'config.php';

// Función para obtener la lista de productos
function getProducts() {
    $productsList = makeApiRequest('products', 'GET');
    $products = [];

    if (isset($productsList['products']['product'])) {
        $productRefs = $productsList['products']['product'];
        foreach ($productRefs as $productRef) {
            $productId = $productRef['@attributes']['id'] ?? null;
            if ($productId) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Lista de Productos</h1>
        <a href="product_create.php" class="btn btn-success mb-3">Agregar Nuevo Producto</a>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
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
                            <td><?= htmlspecialchars($product['price'] ?? 'N/A'); ?></td>
                            <td>
                                <a href="product_view.php?id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-primary btn-sm">Ver</a>
                                <a href="product_edit.php?id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="product_delete.php?id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No se encontraron productos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
