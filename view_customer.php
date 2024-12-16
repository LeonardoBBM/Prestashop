<?php
include 'config.php';

$customer_id = $_GET['id'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_URL . "customers/$customer_id?ws_key=" . API_KEY . "&output_format=JSON");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$customer = json_decode($response, true)['customer'];

echo "<h1>Detalles del Cliente</h1>";
echo "ID: {$customer['id']}<br>";
echo "Nombre: {$customer['firstname']}<br>";
echo "Apellido: {$customer['lastname']}<br>";
echo "Email: {$customer['email']}<br>";
?>
