<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la API
$apiUrl = 'http://192.168.1.86/api/products'; // Cambiado a productos
$apiKey = '9UHDV8UDYKX4YFFNY9D5Q1QW92JUXNRM';

function callApi($endpoint, $method = 'GET', $data = null) {
    global $apiUrl, $apiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . '/' . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');
    
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    } elseif ($method == 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    // Manejo para PUT
    if ($method == 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml']);
    }

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }
    
    curl_close($ch);
    return json_decode($response, true);
}