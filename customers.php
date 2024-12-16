<?php
include 'config.php';

// Llamar a la API para obtener la lista de clientes
$response = callAPI('customers');

// Mostrar la respuesta cruda para depuración
echo "<pre>Respuesta cruda de la API:\n";
var_dump($response);
echo "</pre>";

// Convertir la respuesta en un objeto SimpleXML
$responseXML = simplexml_load_string($response);

// Verificar si se recibió una respuesta válida
$customers = [];
if ($responseXML && isset($responseXML->customers->customer)) {
    foreach ($responseXML->customers->customer as $customer) {
        // Obtener detalles del cliente individual
        $customerDetails = callAPI("customers/{$customer['id']}");
        $customerXML = simplexml_load_string($customerDetails);
        if ($customerXML && isset($customerXML->customer)) {
            $customers[] = $customerXML->customer;
        }
    }
}

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

    // Llamada a la API para crear el cliente
    callAPI('customers', 'POST', $xml);

    // Redireccionar para evitar reenvío del formulario
    header("Location: customers.php");
    exit;
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
<div class="container mt-5">
    <h1 class="text-center">Gestión de Clientes</h1>

    <!-- Formulario para crear cliente -->
    <div class="card mb-4">
        <div class="card-header">Crear Cliente</div>
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
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
                </div>
                <button type="submit" class="btn btn-primary">Crear Cliente</button>
            </form>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <h2 class="mt-4">Lista de Clientes</h2>
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
                        <td><?= htmlspecialchars($customer->id) ?></td>
                        <td><?= htmlspecialchars($customer->firstname) ?></td>
                        <td><?= htmlspecialchars($customer->lastname) ?></td>
                        <td><?= htmlspecialchars($customer->email) ?></td>
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
