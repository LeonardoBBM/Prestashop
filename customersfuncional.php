<?php
include 'config.php';

echo "<pre>";
$response = callAPI('customers');
var_dump($response); // Imprime para verificar si se recibe el XML correctamente
echo "</pre>";

// Crear un nuevo cliente si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xml = <<<EOD
<prestashop>
  <customer>
    <id_default_group>3</id_default_group>
    <id_lang>1</id_lang>
    <firstname>{$_POST['firstname']}</firstname>
    <lastname>{$_POST['lastname']}</lastname>
    <email>{$_POST['email']}</email>
    <passwd>password123</passwd>
    <active>1</active>
  </customer>
</prestashop>
EOD;

    $result = callAPI('customers', 'POST', $xml);
    header("Location: customers.php");
    exit;
}

// Si el XML es válido, obtener los clientes
$customers = [];
if ($response && $response->customers) {
    foreach ($response->customers->customer as $customer) {
        // Realizar una nueva solicitud para cada cliente
        $customerDetails = callAPI("customers/{$customer['id']}");
        if ($customerDetails) {
            $customers[] = $customerDetails->customer;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-4 mb-4 text-center">Gestión de Clientes</h1>

    <!-- Formulario para crear cliente -->
    <h2>Crear Cliente</h2>
    <form method="POST" class="mb-4">
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Nombre:</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="col">
                <label class="form-label">Apellido:</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="col">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Crear Cliente</button>
    </form>

    <!-- Tabla de clientes -->
    <h2>Lista de Clientes</h2>
    <table class="table table-bordered table-striped">
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
                    <td><?= $customer->id ?></td>
                    <td><?= $customer->firstname ?></td>
                    <td><?= $customer->lastname ?></td>
                    <td><?= $customer->email ?></td>
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
