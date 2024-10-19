<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la API
$apiUrl = 'http://192.168.1.86/api/customers';
$apiKey = 'M7VEYVVB67TD5DZBSUUR74ELKPZHP6YF';

function callApi($endpoint, $method = 'GET', $data = null) {
    global $apiUrl, $apiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');
    
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    } elseif ($method == 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }
    
    curl_close($ch);
    return json_decode($response, true);
}

$response = callApi('unavailable_endpoint'); // Cambia a un endpoint no permitido
if (isset($response['error'])) {
    echo 'Error: ' . $response['error'];
} else {
    echo 'Acceso permitido al endpoint restringido.';
}

?>
