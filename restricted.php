<?php
include 'config.php';

// Endpoint no autorizado
$restrictedEndpoint = 'categories'; // Cambia 'categories' por otro endpoint restringido si es necesario

// Intentar acceder al endpoint restringido
$response = makeApiRequest($restrictedEndpoint, 'GET');

// Mostrar resultado en pantalla
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Prueba de Restricción</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
    <div class='container mt-5'>
        <h1 class='mb-4'>Prueba de Restricción de Endpoint</h1>";

if (isset($response['error']) || (isset($response['http_code']) && $response['http_code'] == 403)) {
    echo "<div class='alert alert-success'>
            <strong>Acceso Restringido:</strong> Intentar acceder al endpoint <code>$restrictedEndpoint</code> fue bloqueado correctamente.
          </div>";
} elseif (isset($response['http_code']) && $response['http_code'] == 401) {
    echo "<div class='alert alert-warning'>
            <strong>Sin Autorización:</strong> El servidor devolvió un error 401 (No autorizado) para el endpoint <code>$restrictedEndpoint</code>.
          </div>";
} else {
    echo "<div class='alert alert-danger'>
            <strong>Advertencia:</strong> Parece que el endpoint <code>$restrictedEndpoint</code> no está restringido. Verifica tu configuración de permisos.
          </div>";
}

echo "<a href='products.php' class='btn btn-primary mt-3'>Regresar a la Lista de Productos</a>";
echo "</div></body></html>";
?>
