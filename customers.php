<?php
include 'api.php';

// Obtener la lista de clientes
$response = callAPI('GET', 'customers');

// Imprime la respuesta en crudo para depuración
echo "<pre>";
echo htmlspecialchars($response);
echo "</pre>";

$customers = simplexml_load_string($response);
if ($customers === false) {
    echo "Error: No se pudo cargar el XML";
    exit;
}
?>
