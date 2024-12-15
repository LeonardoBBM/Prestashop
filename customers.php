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
    <title>Clientes</title>
</head>
<body>
    <h1>Gestión de Clientes</h1>

    <!-- Formulario para crear cliente -->
    <h2>Crear Cliente</h2>
    <form method="POST" action="customers.php">
        <label>Nombre:</label>
        <input type="text" name="firstname" required>
        <label>Apellido:</label>
        <input type="text" name="lastname" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Crear Cliente</button>
    </form>

    <!-- Lista de clientes -->
    <h2>Lista de Clientes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
        </tr>
        <?php foreach ($customers->customer as $customer): ?>
        <tr>
            <td><?= $customer['id'] ?></td>
            <td><?= $customer->firstname ?></td>
            <td><?= $customer->lastname ?></td>
            <td><?= $customer->email ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
