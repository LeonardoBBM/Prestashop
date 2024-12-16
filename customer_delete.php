<?php
include 'config.php';

$id = $_GET['id'];
$response = makeApiRequest("customers/$id", 'DELETE');

if (!isset($response['error'])) {
    header('Location: customers.php');
    exit;
} else {
    $error = "No se pudo eliminar el cliente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Eliminar Cliente</title>
</head>
<body>
    <div class="container mt-4">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <a href="customers.php" class="btn btn-secondary">Regresar a Clientes</a>
    </div>
</body>
</html>
