<?php
include 'config.php';

// Obtener la lista de clientes
$response = makeApiRequest('customers');

// Depuración de la respuesta
echo "<pre>";
echo "Respuesta cruda de la API:\n";
var_dump($response);
echo "</pre>";

// Convertir a XML
libxml_use_internal_errors(true);
$customers = simplexml_load_string($response);

// Imprimir errores de XML si existen
if ($customers === false) {
    echo "Error al convertir XML:<br>";
    foreach (libxml_get_errors() as $error) {
        echo $error->message . "<br>";
    }
    exit;
}

// Crear un cliente si se envía el formulario
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

    $result = makeApiRequest('customers', 'POST', $xml);
    header("Location: customers.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Gestión de Clientes</h1>

        <!-- Formulario para crear cliente -->
        <div class="card mb-4">
            <div class="card-header">Crear Cliente</div>
            <div class="card-body">
                <form method="POST" action="customers.php">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="firstname" class="form-label">Nombre</label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="lastname" class="form-label">Apellido</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Cliente</button>
                </form>
            </div>
        </div>

        <!-- Lista de clientes -->
        <div class="card">
            <div class="card-header">Lista de Clientes</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
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
                                    <td><?= isset($customer['id']) ? htmlspecialchars($customer['id']) : 'N/A' ?></td>
                                    <td><?= isset($customer->firstname) ? htmlspecialchars((string)$customer->firstname) : 'N/A' ?></td>
                                    <td><?= isset($customer->lastname) ? htmlspecialchars((string)$customer->lastname) : 'N/A' ?></td>
                                    <td><?= isset($customer->email) ? htmlspecialchars((string)$customer->email) : 'N/A' ?></td>
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
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
