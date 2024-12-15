<?php
include 'api.php';

// Obtener la lista de clientes
$response = callAPI('GET', 'customers');
$customersList = simplexml_load_string($response);

function getCustomerDetails($url) {
    // Obtener detalles del cliente con una llamada individual
    return simplexml_load_string(callAPI('GET', $url));
}

$customers = [];
if ($customersList && isset($customersList->customer)) {
    foreach ($customersList->customer as $customer) {
        // Obtener detalles completos de cada cliente
        $customerDetails = getCustomerDetails($customer['xlink:href']);
        $customers[] = [
            'id' => (string)$customerDetails->id,
            'firstname' => (string)$customerDetails->firstname,
            'lastname' => (string)$customerDetails->lastname,
            'email' => (string)$customerDetails->email
        ];
    }
}
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
    <h2>Lista de Clientes</h2>
    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['id']); ?></td>
                        <td><?= htmlspecialchars($customer['firstname']); ?></td>
                        <td><?= htmlspecialchars($customer['lastname']); ?></td>
                        <td><?= htmlspecialchars($customer['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No se encontraron clientes</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
