<?php
// Definir la URL completa de la API de Prestashop
define('API_URL', 'http://localhost:8080/prestashop/api/');
define('API_KEY', 'MGEU9V95CGNSWTBNMEUC4Q5U1DKFLFYT'); // Tu clave API actualizada

// Función para realizar solicitudes a la API de Prestashop
function makeApiRequest($endpoint, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_URL . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_USERPWD, API_KEY . ':');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/xml',
    ]);

    // Enviar datos si es POST o PUT
    if ($method == 'POST' || $method == 'PUT') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Manejar la respuesta
    if ($httpCode >= 200 && $httpCode < 300) {
        return simplexml_load_string($response); // Decodificar XML
    } else {
        return [
            "error" => "Error en la solicitud",
            "http_code" => $httpCode,
            "response" => $response
        ];
    }
}
