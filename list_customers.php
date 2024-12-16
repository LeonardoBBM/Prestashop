<?php
// Incluir configuración
require 'config.php';

// Obtener todos los clientes
list($httpCode, $customers) = prestashopRequest('customers', 'GET');

// Verificar si la solicitud fue exitosa
if ($httpCode != 200) {
    echo "Error al obtener la lista de clientes. Código HTTP: " . $httpCode;
    exit;
}

// Decodificar el JSON para extraer la información de los clientes
$customers = json_decode($customers, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <!-- Enlace a Bootstrap para estilos -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Lista de Clientes</h1>
        
        <!-- Mostrar mensaje de éxito si existe -->
        <?php if (isset($_GET['message'])) : ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($customers['customers'])) : ?>
                    <?php foreach ($customers['customers'] as $customer) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($customer['id']); ?></td>
                            <td><?php echo htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td>
                                <!-- Botón de Consultar -->
                                <a href="customer_view.php?id=<?php echo htmlspecialchars($customer['id']); ?>" class="btn btn-primary btn-sm">Consultar</a>
                                <!-- Botón de Eliminar con confirmación -->
                                <a href="customer_delete.php?id=<?php echo htmlspecialchars($customer['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
