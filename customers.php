<?php
include 'config.php';

// Función para obtener la lista de clientes o realizar una búsqueda específica
function getCustomers($searchQuery = null) {
    if ($searchQuery) {
        // Intentar con filtro (correo electrónico o ID)
        $endpoint = "customers?filter[email]=" . urlencode($searchQuery) . "&filter[id]=" . urlencode($searchQuery);
    } else {
        // Obtener todos los clientes
        $endpoint = "customers";
    }

    // Llamar a la API
    return makeApiRequest($endpoint, 'GET');
}

// Obtener datos
$searchQuery = $_POST['search_query'] ?? null;
$response = getCustomers($searchQuery);

// Depuración: Mostrar la respuesta de la API
echo "<pre>";
print_r($response);
echo "</pre>";

// Procesar los clientes
if (isset($response['error'])) {
    $customers = [];
    $error = $response['error'] . " (Código HTTP: " . $response['http_code'] . ")";
} elseif (isset($response['customers']['customer'])) {
    $customers = $response['customers']['customer']; // Ajustado para el formato correcto
} else {
    $customers = [];
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

        <!-- Botón para crear un nuevo cliente -->
        <a href="customer_create.php" class="btn btn-primary mb-3">Crear Nuevo Cliente</a>

        <!-- Formulario de consulta de clientes -->
        <form method="post" class="form-inline mb-3">
            <div class="form-group">
                <input type="text" class="form-control" name="search_query" placeholder="Buscar por ID o correo" required>
            </div>
            <button type="submit" class="btn btn-secondary ml-2">Consultar</button>
        </form>

        <!-- Mensaje de error -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

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
                            <td><?= htmlspecialchars($customer['id'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($customer['firstname'] ?? '') . ' ' . htmlspecialchars($customer['lastname'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($customer['email'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($customer['date_add'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars(($customer['id_gender'] ?? 0) == 1 ? 'Hombre' : (($customer['id_gender'] ?? 0) == 2 ? 'Mujer' : 'Otro')); ?></td>
                            <td>
                                <a href="customer_view.php?id=<?= htmlspecialchars($customer['id'] ?? ''); ?>" class="btn btn-info btn-sm">Ver</a>
                                <a href="customer_edit.php?id=<?= htmlspecialchars($customer['id'] ?? ''); ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="customer_delete.php?id=<?= htmlspecialchars($customer['id'] ?? ''); ?>" class="btn btn-danger btn-sm">Eliminar</a>
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
