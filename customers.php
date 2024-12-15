<?php
include 'config.php';

echo "Pantalla customers";

// Crear un nuevo cliente si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    $xml = <<<EOD
<prestashop>
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

    $result = makeApiRequest('customers', 'POST', $xml);
    header("Location: customers.php");
    exit;
}

// Obtener la lista de clientes
$response = makeApiRequest('customers');

if (isset($response->error)) {
    echo "<pre>Error: {$response['error']}</pre>";
    echo "<pre>HTTP Code: {$response['http_code']}</pre>";
    echo "<pre>Response: {$response['response']}</pre>";
    exit;
}

$customers = $response;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Gestión de Clientes</h1>

    <!-- Formulario para crear cliente -->
    <h2>Crear Cliente</h2>
    <form method="POST" action="customers.php" class="mb-4">
        <div class="row mb-3">
            <div class="col">
                <label>Nombre:</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="col">
                <label>Apellido:</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="col">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Crear Cliente</button>
    </form>

    <!-- Lista de clientes -->
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
            <?php foreach ($customers->customer as $customer): ?>
            <tr>
                <td><?= $customer['id'] ?></td>
                <td><?= $customer->firstname ?></td>
                <td><?= $customer->lastname ?></td>
                <td><?= $customer->email ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
