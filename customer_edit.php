<?php
include 'config.php';

$id = $_GET['id'];
$response = makeApiRequest("customers/$id", 'GET');
$customer = $response['customer'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $xml = new SimpleXMLElement('<prestashop/>');
    $customerXml = $xml->addChild('customer');
    $customerXml->addChild('id', $id);
    $customerXml->addChild('firstname', $_POST['firstname']);
    $customerXml->addChild('lastname', $_POST['lastname']);
    $customerXml->addChild('email', $_POST['email']);

    $xml_data = $xml->asXML();
    $response = makeApiRequest("customers/$id", 'PUT', $xml_data);

    if (isset($response->customer->id)) {
        header('Location: customers.php');
        exit;
    } else {
        $error = "No se pudo actualizar el cliente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Cliente</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Cliente</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="firstname">Nombre:</label>
                <input type="text" class="form-control" name="firstname" value="<?= $customer['firstname'] ?>" required>
            </div>
            <div class="form-group">
                <label for="lastname">Apellido:</label>
                <input type="text" class="form-control" name="lastname" value="<?= $customer['lastname'] ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" name="email" value="<?= $customer['email'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
        </form>
        <a href="customers.php" class="btn btn-secondary mt-2">Regresar a Clientes</a>
    </div>
</body>
</html>
