<?php
include 'config.php';

$id = $_GET['id'];
$response = makeApiRequest("customers/$id", 'GET');

if (!isset($response['customer'])) {
    die("Error: Cliente no encontrado.");
}

$customer = $response['customer'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Actualizar solo los campos editables, pero incluir todos los existentes
    $customer['firstname'] = trim($_POST['firstname']);
    $customer['lastname'] = trim($_POST['lastname']);
    $customer['email'] = trim($_POST['email']);

    // Crear XML con todos los campos
    $xml = new SimpleXMLElement('<prestashop/>');
    $customerXml = $xml->addChild('customer');

    foreach ($customer as $key => $value) {
        if (!is_array($value) && !empty($value)) { // Ignorar arrays vacíos o nulos
            $customerXml->addChild($key, htmlspecialchars($value));
        }
    }

    // Enviar solicitud PUT
    $xml_data = $xml->asXML();
    $response = makeApiRequest("customers/$id", 'PUT', $xml_data);

    

    // Validar la respuesta
    if (!empty($response['customer']) && isset($response['customer']['id'])) {
        header('Location: customers.php');
        exit;
    } else {
        $error = "El cliente fue actualizado, pero la validación no lo detectó. Verifica los datos.";
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
                <input type="text" class="form-control" name="firstname" value="<?= htmlspecialchars($customer['firstname'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="lastname">Apellido:</label>
                <input type="text" class="form-control" name="lastname" value="<?= htmlspecialchars($customer['lastname'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
        </form>
        <a href="customers.php" class="btn btn-secondary mt-2">Regresar a Clientes</a>
    </div>
</body>
</html>
