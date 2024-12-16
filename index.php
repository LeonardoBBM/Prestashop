<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Gestión de Prestashop</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Encabezado -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Gestión de Prestashop</a>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-5 text-center">
        <h1 class="mb-4">Bienvenido a la Aplicación de Gestión</h1>
        <p class="lead">Desde esta aplicación puedes gestionar clientes y productos de la tienda Prestashop.</p>

        <!-- Tarjetas de Sección -->
        <div class="row justify-content-center mt-5">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Gestión de Clientes</h5>
                        <p class="card-text">Administra los usuarios registrados en tu tienda.</p>
                        <a href="customers.php" class="btn btn-primary">Ir a Clientes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Gestión de Productos</h5>
                        <p class="card-text">Administra los productos disponibles en tu tienda.</p>
                        <a href="products.php" class="btn btn-success">Ir a Productos</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prueba de Restricción -->
        <div class="mt-4">
            <a href="restricted_test.php" class="btn btn-danger">Probar Restricción de Endpoint</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y'); ?> Aplicación de Gestión de Prestashop | Desarrollado en PHP</p>
        </div>
    </footer>
</body>
</html>
