<?php
include 'api.php';

// Crear un nuevo cliente si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    $xml = <<<EOD
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <customer>
    <id_default_group><![CDATA[3]]></id_default_group>
    <id_lang><![CDATA[1]]></id_lang>
    <firstname><![CDATA[$firstname]]></firstname>
    <lastname><![CDATA[$lastname]]></lastname>
    <email><![CDATA[$email]]></email>
    <passwd><![CDATA[password123]]></passwd>
    <active><![CDATA[1]]></active>
  </customer>
</prestashop>
EOD;

    $result = callAPI('POST', 'customers', $xml);
    header("Location: customers.php");
    exit;
}

// Obtener la lista de clientes
$response = callAPI('GET', 'customers');
$customers = simplexml_load_string($response);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gestión de Clientes</h1>

        <!-- Formulario para crear cliente -->
        <div class="card mt-3">
            <div class="card-header">Crear Cliente</div>
            <div class="card-body">
                <form method="POST" action="customers.php">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Apellido</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Cliente</button>
                </form>
            </div>
        </div>

        <!-- Lista de clientes -->
        <h2 class="mt-5">Lista de Clientes</h2>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($customers && isset($customers->customer)): ?>
                    <?php foreach ($customers->customer as $customer): ?>
                    <tr>
                        <td><?= $customer['id'] ?></td>
                        <td><?= $customer->firstname ?></td>
                        <td><?= $customer->lastname ?></td>
                        <td><?= $customer->email ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
