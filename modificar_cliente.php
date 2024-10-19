<?php
// Habilitar la visualización de errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// URL de la API de Prestashop para modificar clientes
$apiUrl = 'http://192.168.1.86/api/customers/';

// Clave API
$apiKey = 'M7VEYVVB67TD5DZBSUUR74ELKPZHP6YF';

include 'api.php'; // Asegúrate de incluir tu archivo de funciones

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $customerId = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $passwd = $_POST['passwd'];

    // Validación básica
    if (empty($customerId) || empty($firstname) || empty($lastname) || empty($email) || empty($passwd)) {
        echo "Todos los campos son requeridos.";
        exit;
    }

    // Crear el array de datos
    $data = [
        'customer' => [
            'id' => $customerId,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'passwd' => $passwd
        ]
    ];

    // Convertir el array a XML
    $xmlData = convert_array_to_xml($data); // Asegúrate de que esta función esté definida

    // URL del cliente específico para modificar
    $customerUrl = $apiUrl . $customerId;

    // Inicializa cURL
    $ch = curl_init();

    // Configura las opciones de cURL para la modificación del cliente
    curl_setopt($ch, CURLOPT_URL, $customerUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/xml']);

    // Ejecuta la solicitud
    $response = curl_exec($ch);

    // Manejo de errores de cURL
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Manejo de la respuesta
    if (strpos($response, '<errors>') !== false) {
        echo 'Error al actualizar el cliente: ' . $response;
    } else {
        echo 'Cliente actualizado correctamente. <a href="clientes.php">Ver lista de clientes</a>';
    }

    // Cierra cURL
    curl_close($ch);
} else {
    echo "Método no permitido.";
}

// Función para convertir un array a XML
function convert_array_to_xml($data, $rootElement = 'prestashop', $xml = null) {
    if ($xml === null) {
        $xml = simplexml_load_string("<?xml version=\"1.0\" encoding=\"UTF-8\"?><$rootElement></$rootElement>");
    }

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            convert_array_to_xml($value, $key, $xml->addChild($key));
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }

    return $xml->asXML();
}
?>
