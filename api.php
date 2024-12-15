<?php
include 'config.php';

function callAPI($method, $endpoint, $data = null) {
    $url = API_URL . $endpoint;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode(API_KEY . ':'), 
        'Content-Type: application/xml'
    ]);

    switch ($method) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            break;
        case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            break;
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
    }

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
    }
    curl_close($ch);

    return $response;
}
?>
