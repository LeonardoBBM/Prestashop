<?php
// Definir la URL completa de la API de PrestaShop
define('API_URL', 'http://localhost:8080/prestashop/api/');
define('API_KEY', 'MGEU9V95CGNSWTBNMEUC4Q5U1DKFLFYT');

// Función para realizar solicitudes a la API de PrestaShop
function makeApiRequest($endpoint, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_URL . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, API_KEY . ':');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/xml'
    ]);

    // Configurar método y datos si es POST o PUT
    if ($method === 'POST' || $method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Manejar errores
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
    }

    curl_close($ch);

    // Devolver la respuesta
    return $response;
}
?>
