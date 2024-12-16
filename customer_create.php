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
    <title>Crear Cliente</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Crear Cliente</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="firstname">Nombre:</label>
                <input type="text" class="form-control" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Apellido:</label>
                <input type="text" class="form-control" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Cliente</button>
        </form>
        <a href="customers.php" class="btn btn-secondary mt-2">Regresar a Clientes</a>
    </div>
</body>
</html>
