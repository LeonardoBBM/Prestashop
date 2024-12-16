<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $xml = new SimpleXMLElement('<prestashop/>');
        $customer = $xml->addChild('customer');
        $customer->addChild('active', 1);
        $customer->addChild('firstname', htmlspecialchars($firstname));
        $customer->addChild('lastname', htmlspecialchars($lastname));
        $customer->addChild('email', htmlspecialchars($email));
        $customer->addChild('passwd', $password);
        $customer->addChild('id_default_group', 3);

        $xml_data = $xml->asXML();
        $response = makeApiRequest('customers', 'POST', $xml_data);

        if (isset($response['customer']['id'])) {
            header('Location: customers.php');
            exit;
        } else {
            $error = "No se pudo crear el cliente. Verifica los datos ingresados.";
        }
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
            <h2>Crear Cliente</h2>
            <a href="customers.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Regresar</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" class="p-4 border rounded shadow-sm bg-light">
            <div class="mb-3">
                <label for="firstname" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="firstname" required>
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="lastname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Crear Cliente</button>
        </form>
    </div>
</body>

</html>
