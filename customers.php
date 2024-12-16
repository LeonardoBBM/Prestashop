<?php
include 'config.php';

// Función para obtener la lista de clientes o realizar una búsqueda específica
function getCustomers($searchQuery = null) {
    if ($searchQuery) {
        // Realiza la búsqueda por correo electrónico o ID
        $endpoint = "customers?filter[email]=$searchQuery|filter[id]=$searchQuery";
    } else {
        // Obtiene la lista completa de clientes
        $endpoint = "customers";
    }

    // Llamada a la API para obtener la lista de clientes
    return makeApiRequest($endpoint, 'GET');
}

// Verifica si se realizó una búsqueda
$searchQuery = $_POST['search_query'] ?? null;
$response = getCustomers($searchQuery);
$customers = isset($response['customers']) ? $response['customers'] : [];
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

        <!-- Botón para crear un nuevo cliente -->
        <a href="customer_create.php" class="btn btn-primary mb-3">Crear Nuevo Cliente</a>

        <!-- Formulario de consulta de clientes -->
        <form method="post" class="form-inline mb-3">
            <div class="form-group">
                <input type="text" class="form-control" name="search_query" placeholder="Buscar por ID o correo" required>
            </div>
            <button type="submit" class="btn btn-secondary ml-2">Consultar</button>
        </form>

        <!-- Tabla de clientes -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Correo Electrónico</th>
                    <th>Teléfono</th>
                    <th>Fecha de Registro</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= htmlspecialchars($customer['id']); ?></td>
                            <td><?= htmlspecialchars($customer['firstname']) . ' ' . htmlspecialchars($customer['lastname']); ?></td>
                            <td><?= htmlspecialchars($customer['email']); ?></td>
                            <td><?= htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($customer['date_add']); ?></td>
                            <td><?= htmlspecialchars($customer['id_gender'] == 1 ? 'Hombre' : ($customer['id_gender'] == 2 ? 'Mujer' : 'Otro')); ?></td>
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

