<?php
include 'api.php';

// Obtener la lista de clientes
$response = callAPI('GET', 'customers');

// Mostrar la respuesta XML para depuración
echo "<pre>";
var_dump($response);
echo "</pre>";

// Cargar XML
$customers = simplexml_load_string($response);

// Depuración de estructura
if ($customers === false) {
    echo "Error: No se pudo cargar el XML.";
    exit;
}

// Intenta acceder a los nodos "customer" directamente
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Gestión de Clientes</h1>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Intentar acceder a los clientes
            if (isset($customers->customers->customer)) {
                foreach ($customers->customers->customer as $customer) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($customer['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer->firstname ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($customer->lastname ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($customer->email ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
            } elseif (isset($customers->customer)) {
                // Si los clientes no están anidados en "customers"
                foreach ($customers->customer as $customer) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($customer['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer->firstname ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($customer->lastname ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($customer->email ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No se encontraron clientes</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
