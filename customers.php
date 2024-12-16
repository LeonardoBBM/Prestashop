<?php
include 'api.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Acción para el formulario
    $action = $_POST['action'];

    if ($action === 'create') {
        // Crear cliente
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
        callAPI('POST', 'customers', $xml);
    } elseif ($action === 'delete') {
        // Eliminar cliente
        $id = $_POST['id'];
        callAPI('DELETE', "customers/$id");
    } elseif ($action === 'edit') {
        // Editar cliente
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];

        $xml = <<<EOD
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <customer>
    <id><![CDATA[$id]]></id>
    <firstname><![CDATA[$firstname]]></firstname>
    <lastname><![CDATA[$lastname]]></lastname>
    <email><![CDATA[$email]]></email>
  </customer>
</prestashop>
EOD;
        callAPI('PUT', "customers/$id", $xml);
    }
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
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gestión de Clientes</h1>

        <!-- Formulario para Crear Cliente -->
        <h3>Crear Cliente</h3>
        <form method="POST" class="mb-3">
            <input type="hidden" name="action" value="create">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Nombre</label>
                    <input type="text" name="firstname" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Apellido</label>
                    <input type="text" name="lastname" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Crear Cliente</button>
        </form>

        <!-- Tabla de Clientes -->
        <h3>Lista de Clientes</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers->customer as $customer): ?>
                    <tr>
                        <td><?= $customer['id'] ?></td>
                        <td><?= $customer->firstname ?? 'N/A' ?></td>
                        <td><?= $customer->lastname ?? 'N/A' ?></td>
                        <td><?= $customer->email ?? 'N/A' ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                            <!-- Botón Editar -->
                            <form method="POST" style="display:inline;" action="edit_customer.php">
                                <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
