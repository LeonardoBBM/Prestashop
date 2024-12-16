<?php
include 'config.php';

// Obtener el ID del producto desde la URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Hacer la solicitud DELETE a la API de PrestaShop
    $response = makeApiRequest("products/$productId", 'DELETE');

    if (isset($response['error'])) {
        echo "Error al eliminar el producto: " . $response['response'];
    } else {
        header('Location: products.php'); // Redireccionar a la lista de productos después de eliminar
        exit;
    }
} else {
    echo "ID de producto no especificado.";
}
?>
