<?php
// Definir la URL completa de la API de Prestashop
define('API_URL', 'http://localhost:8080/prestashop/api/');
define('API_KEY', 'MGEU9V95CGNSWTBNMEUC4Q5U1DKFLFYT');

// Función para realizar solicitudes a la API de Prestashop
function makeApiRequest($endpoint, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_URL . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_USERPWD, API_KEY . ':');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/xml',
    ));

    if ($method == 'POST' || $method == 'PUT') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Envía los datos como XML
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Depuración: Verificar la respuesta cruda
    error_log("HTTP Code: " . $httpCode);
    error_log("Response: " . $response);

    if ($httpCode >= 200 && $httpCode < 300) {
        // Convertir XML a un arreglo
        $xml = simplexml_load_string($response);
        if ($xml === false) {
            error_log("Error al cargar XML: " . implode(', ', libxml_get_errors()));
            return [];
        }
        return json_decode(json_encode($xml), true);
    } else {
        return [
            "error" => "Error en la solicitud",
            "http_code" => $httpCode,
            "response" => $response
        ];
    }
}
?>
