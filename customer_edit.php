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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos Bootstrap (Opcional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Editar Cliente</h2>
            <a href="customers.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Regresar</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" class="p-4 border rounded shadow-sm bg-light">
            <div class="mb-3">
                <label for="firstname" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="firstname" value="<?= htmlspecialchars($customer['firstname'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="lastname" value="<?= htmlspecialchars($customer['lastname'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Actualizar Cliente</button>
        </form>
    </div>
</body>

</html>
