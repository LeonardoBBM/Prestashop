<?php
include 'utils.php';

// Listar todos los clientes
$customers = callAPI('customers');

echo "<h1>Lista de Clientes</h1>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Link</th></tr>";
foreach ($customers->customer as $customer) {
    echo "<tr>";
    echo "<td>" . $customer['id'] . "</td>";
    echo "<td><a href='" . $customer['xlink:href'] . "'>Ver Detalle</a></td>";
    echo "</tr>";
}
echo "</table>";
?>
