<?php
include 'config.php';

// Listar todos los clientes
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
    <h2>Lista de Clientes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Enlace</th>
        </tr>
        <?php foreach ($xml->customer as $customer): ?>
            <tr>
                <td><?= $customer['id'] ?></td>
                <td><a href="<?= $customer['xlink:href'] ?>">Ver</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
