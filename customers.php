<?php
include 'config.php';

function createCustomer($firstname, $lastname, $email) {
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

    return callAPI('POST', 'customers', $xml);
}

function deleteCustomer($id) {
    return callAPI('DELETE', "customers/$id");
}

// Crear cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    createCustomer($firstname, $lastname, $email);
    header("Location: customers.php");
}

// Eliminar cliente
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    deleteCustomer($id);
    header("Location: customers.php");
}

// Obtener la lista actualizada de clientes
$customers = callAPI('GET', 'customers');
$xml = simplexml_load_string($customers);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
</head>
<body>
    <h1>Gestión de Clientes</h1>

    <!-- Formulario para crear cliente -->
    <h2>Crear Cliente</h2>
    <form method="POST" action="customers.php">
        <label for="firstname">Nombre:</label>
        <input type="text" name="firstname" required>
        <label for="lastname">Apellido:</label>
        <input type="text" name="lastname" required>
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>
        <button type="submit" name="create">Crear Cliente</button>
    </form>

    <!-- Tabla de clientes -->
    <h2>Lista de Clientes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($xml->customer as $customer): ?>
            <tr>
                <td><?= $customer['id'] ?></td>
                <td><?= $customer->firstname ?? 'N/A' ?></td>
                <td><?= $customer->lastname ?? 'N/A' ?></td>
                <td><?= $customer->email ?? 'N/A' ?></td>
                <td>
                    <a href="customers.php?delete_id=<?= $customer['id'] ?>" onclick="return confirm('¿Eliminar este cliente?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
