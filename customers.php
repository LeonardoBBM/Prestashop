<?php
include 'api.php';

// Obtener la lista de clientes
$response = callAPI('GET', 'customers');

// Depurar la respuesta para ver el XML en bruto
echo "<pre>";
var_dump($response); // Ver el contenido del XML devuelto por la API
echo "</pre>";

// Cargar el XML en un objeto SimpleXML
$customers = simplexml_load_string($response);

// Manejar errores en la carga del XML
if ($customers === false) {
    echo "Error: No se pudo cargar el XML de clientes. Verifique la respuesta de la API.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Clientes</h1>

        <!-- Formulario para crear cliente -->
        <div class="card mb-4">
            <div class="card-header">Crear Cliente</div>
            <div class="card-body">
                <form method="POST" action="customers.php">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="firstname" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" required>
                        </div>
                        <div class="col">
                            <label for="lastname" class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" required>
                        </div>
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Cliente</button>
                </form>
            </div>
        </div>

        <!-- Lista de clientes -->
        <div class="card">
            <div class="card-header">Lista de Clientes</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Iterar sobre los clientes si existen
                        if (isset($customers->customer)) {
                            foreach ($customers->customer as $customer): ?>
                                <tr>
                                    <td><?= htmlspecialchars($customer['id']); ?></td>
                                    <td><?= htmlspecialchars($customer->firstname ?? 'N/A'); ?></td>
                                    <td><?= htmlspecialchars($customer->lastname ?? 'N/A'); ?></td>
                                    <td><?= htmlspecialchars($customer->email ?? 'N/A'); ?></td>
                                </tr>
                            <?php 
                            endforeach;
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No se encontraron clientes.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
