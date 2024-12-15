<?php
include 'api.php';

// Obtener la lista de clientes
$response = callAPI('GET', 'customers');
$customers = simplexml_load_string($response);

if ($customers === false) {
    echo "Error: No se pudo cargar el XML.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Gestión de Clientes</h1>

        <!-- Formulario para crear cliente -->
        <div class="mt-4">
            <h2>Crear Cliente</h2>
            <form method="POST" action="customers.php" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="firstname" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="lastname" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Crear Cliente</button>
                </div>
            </form>
        </div>

        <!-- Lista de clientes -->
        <div class="mt-5">
            <h2>Lista de Clientes</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($customers->customer)): ?>
                        <?php foreach ($customers->customer as $customer): ?>
                        <?php
                            // Obtener datos de cada cliente con una petición GET específica
                            $customerDetails = callAPI('GET', 'customers/' . $customer['id']);
                            $details = simplexml_load_string($customerDetails);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($customer['id']) ?></td>
                            <td><?= htmlspecialchars($details->firstname) ?></td>
                            <td><?= htmlspecialchars($details->lastname) ?></td>
                            <td><?= htmlspecialchars($details->email) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No se encontraron clientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
