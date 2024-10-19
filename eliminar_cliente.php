<?php
// Habilitar la visualización de errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'api.php';

// URL de la API de Prestashop para eliminar clientes
$apiUrl = 'http://192.168.1.86/api/customers/';

// Clave API
$apiKey = 'M7VEYVVB67TD5DZBSUUR74ELKPZHP6YF';

// Obtener el ID del cliente a eliminar
$customerId = $_GET['id'];

if (!empty($customerId)) {
    // Inicializar cURL
    $ch = curl_init();

    // Configura las opciones de cURL para eliminar el cliente
    curl_setopt($ch, CURLOPT_URL, $apiUrl . $customerId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

    // Ejecuta la solicitud
    $response = curl_exec($ch);

    // Manejo de errores de cURL
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Cerrar cURL
    curl_close($ch);

    // Mostrar la respuesta de la API
    if (strpos($response, '<errors>') !== false) {
        echo "Error al eliminar el cliente: <br>";
        echo htmlentities($response);
    } else {
        echo "Cliente eliminado con éxito. <br>";
    }
} else {
    echo "ID del cliente no especificado.";
}

// Redirigir a la página de lista de clientes después de 3 segundos
header("refresh:3;url=clientes.php");
echo "<br>Redirigiendo a la lista de clientes...";
?>
