<?php
include 'config.php';

// Función para obtener la lista de clientes o realizar una búsqueda específica
function getCustomers($searchQuery = null) {
    if ($searchQuery) {
        $endpoint = "customers?filter[email]=" . urlencode($searchQuery) . "&filter[id]=" . urlencode($searchQuery);
    } else {
        $endpoint = "customers";
    }
    return makeApiRequest($endpoint, 'GET');
}

// Función para obtener detalles completos de un cliente
function getCustomerDetails($customerId) {
    $endpoint = "customers/$customerId";
    return makeApiRequest($endpoint, 'GET');
}

// Obtener la lista de clientes
$searchQuery = $_POST['search_query'] ?? null;
$response = getCustomers($searchQuery);

$customers = [];
if (isset($response['customers']['customer'])) {
    $customerRefs = $response['customers']['customer'];
    foreach ($customerRefs as $ref) {
        $customerId = $ref['@attributes']['id'] ?? null;
        if ($customerId) {
            $details = getCustomerDetails($customerId);
            if (isset($details['customer'])) {
                $customers[] = $details['customer'];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Lista de Clientes</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Lista de Clientes</h2>

        <a href="customer_create.php" class="btn btn-primary mb-3">Crear Nuevo Cliente</a>

        <form method="post" class="form-inline mb-3">
            <div class="form-group">
                <input type="text" class="form-control" name="search_query" placeholder="Buscar por ID o correo" required>
            </div>
            <button type="submit" class="btn btn-secondary ml-2">Consultar</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Correo Electrónico</th>
                    <th>Fecha de Registro</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= htmlspecialchars($customer['id'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($customer['firstname'] ?? '') . ' ' . htmlspecialchars($customer['lastname'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($customer['email'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($customer['date_add'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars(($customer['id_gender'] ?? 0) == 1 ? 'Hombre' : (($customer['id_gender'] ?? 0) == 2 ? 'Mujer' : 'Otro')); ?></td>
                            <td>
                                <a href="customer_view.php?id=<?= htmlspecialchars($customer['id']); ?>" class="btn btn-info btn-sm">Ver</a>
                                <a href="customer_edit.php?id=<?= htmlspecialchars($customer['id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="customer_delete.php?id=<?= htmlspecialchars($customer['id']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron clientes.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
