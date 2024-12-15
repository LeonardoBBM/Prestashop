<?php
include 'config.php';

// Listar todos los productos
$products = callAPI('GET', 'products');
$xml = simplexml_load_string($products);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
</head>
<body>
    <h1>Gestión de Productos</h1>
    <h2>Lista de Productos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Enlace</th>
        </tr>
        <?php foreach ($xml->product as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><a href="<?= $product['xlink:href'] ?>">Ver</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
