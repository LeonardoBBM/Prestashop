<?php
// utils.php
define('API_URL', 'http://localhost:8080/prestashop/api/'); // Cambia si es necesario
define('API_KEY', 'MGEU9V95CGNSWTBNMEUC4Q5U1DKFLFYT'); // Coloca tu clave API

// Función genérica para llamar a la API de PrestaShop
function callAPI($endpoint, $method = 'GET', $data = null) {
    $url = API_URL . $endpoint;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode(API_KEY . ':'),
        'Content-Type: application/json'
    ]);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $response = curl_exec($ch);
    if (!$response) {
        die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
    }

    curl_close($ch);
    return simplexml_load_string($response);
}
?>
