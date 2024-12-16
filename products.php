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
    <title>Lista de Productos</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <?php include 'partials/sidebar.php'; ?>
        
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'partials/topbar.php'; ?>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Productos</h1>
                    <button onclick="location.href='product_create.php'" class="btn btn-success mb-3">Crear Producto</button>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Mostrar los detalles de cada producto
                            foreach ($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product->id) ?></td>
                                    <td><?= htmlspecialchars($product->name->language) ?></td>
                                    <td><?= htmlspecialchars($product->price) ?></td>
                                    <td>
                                        <a href="product_view.php?id=<?= htmlspecialchars($product->id) ?>" class="btn btn-primary btn-sm">Ver</a>
                                        <a href="product_edit.php?id=<?= htmlspecialchars($product->id) ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="product_delete.php?id=<?= htmlspecialchars($product->id) ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
