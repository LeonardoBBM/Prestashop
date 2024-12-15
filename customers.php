<?php
include 'config.php';

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

    $response = makeApiRequest('customers', 'POST', $xml);
    header("Location: customers.php");
    exit;
}

// Obtener la lista de clientes
$response = makeApiRequest('customers');

// Depurar respuesta cruda para verificar que llega correctamente
echo "<pre>";
var_dump($response);
echo "</pre>";

$customers = simplexml_load_string($response);
if ($customers === false) {
    echo "Error al convertir XML:";
    print_r(libxml_get_errors());
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <!-- Integración de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Clientes</h1>

        <!-- Formulario para crear cliente -->
        <div class="card mb-4">
            <div class="card-header">Crear Cliente</div>
            <div class="card-body">
                <form method="POST" action="customers.php">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="firstname">Nombre</label>
                            <input type="text" class="form-control" name="firstname" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="lastname">Apellido</label>
                            <input type="text" class="form-control" name="lastname" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Cliente</button>
                </form>
            </div>
        </div>

        <!-- Tabla de clientes -->
        <h2>Lista de Clientes</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
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
                        <tr>
                            <td><?= isset($customer['id']) ? $customer['id'] : 'N/A' ?></td>
                            <td><?= isset($customer->firstname) ? $customer->firstname : 'N/A' ?></td>
                            <td><?= isset($customer->lastname) ? $customer->lastname : 'N/A' ?></td>
                            <td><?= isset($customer->email) ? $customer->email : 'N/A' ?></td>
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
