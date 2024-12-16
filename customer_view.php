<?php
include 'config.php';

$id = $_GET['id'];
$response = makeApiRequest("customers/$id", 'GET');

$customer = $response['customer'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Detalles del Cliente</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Detalles del Cliente</h2>
        <p><strong>Nombre:</strong> <?= $customer['firstname'] . ' ' . $customer['lastname'] ?></p>
        <p><strong>Correo Electrónico:</strong> <?= $customer['email'] ?></p>
        <a href="customers.php" class="btn btn-secondary mt-2">Regresar a Clientes</a>
    </div>
</body>
</html>
