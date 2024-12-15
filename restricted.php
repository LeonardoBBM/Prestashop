<?php
include 'config.php';

// Intentar acceder a un endpoint no autorizado
$response = callAPI('GET', 'order_details');
$xml = simplexml_load_string($response);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Endpoint Restringido</title>
</head>
<body>
    <h1>Prueba de Endpoint Restringido</h1>
    <pre>
        <?= htmlentities($response); ?>
    </pre>
</body>
</html>
