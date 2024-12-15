<?php
// Configuración básica de la API de PrestaShop
define('API_URL', 'http://localhost:8080/prestashop/api/');
define('API_KEY', 'MGEU9V95CGNSWTBNMEUC4Q5U1DKFLFYT');

// Función para llamar a la API
function callAPI($endpoint, $method = 'GET', $data = null) {
    $url = API_URL . $endpoint;
    $curl = curl_init();

    $headers = ['Authorization: Basic ' . base64_encode(API_KEY . ':')];

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_FAILONERROR => true,
    ]);

    if ($method === 'POST' || $method === 'PUT') {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $headers[] = 'Content-Type: application/xml';
    }

    $response = curl_exec($curl);

    // Manejo de errores
    if (curl_errno($curl)) {
        echo "cURL Error: " . curl_error($curl);
    }

    curl_close($curl);
    return simplexml_load_string($response);
}
?>
