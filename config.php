<?php
// Configuración básica de la API de PrestaShop
define('BASE_URL', 'http://localhost:8080/prestashop/api/');
define('API_KEY', 'MGEU9V95CGNSWTBNMEUC4Q5U1DKFLFYT');

// Función para realizar peticiones a la API
function callAPI($method, $endpoint, $data = null) {
    $url = BASE_URL . $endpoint;
    $curl = curl_init();

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_USERPWD => API_KEY . ':',
        CURLOPT_CUSTOMREQUEST => $method,
    ];

    if ($data) {
        $options[CURLOPT_POSTFIELDS] = $data;
        $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/xml'];
    }

    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
?>
