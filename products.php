<?php
include 'utils.php';

// Listar todos los productos
$products = callAPI('products');

echo "<h1>Lista de Productos</h1>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Link</th></tr>";
foreach ($products->product as $product) {
    echo "<tr>";
    echo "<td>" . $product['id'] . "</td>";
    echo "<td><a href='" . $product['xlink:href'] . "'>Ver Detalle</a></td>";
    echo "</tr>";
}
echo "</table>";
?>
