<?php
// Habilitar la visualización de errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'api.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $customerId = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $treatment = $_POST['treatment'];
    $passwd = $_POST['passwd']; // Asegúrate de que este campo se incluya en el formulario

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
            'id_gender' => ($treatment === 'Sr.') ? '1' : '2', // Asumiendo 1 es masculino y 2 es femenino
            'passwd' => $passwd // Contraseña requerida para actualizar
        ]
    ];

    // Convertir el array a XML
    $xmlData = convert_to_xml($data); // Usa la función convert_to_xml

    // URL del cliente específico para modificar
    $customerUrl = "http://192.168.1.86/api/customers/$customerId";

    // Inicializa cURL
    $ch = curl_init();

    // Configura las opciones de cURL para la modificación del cliente
    curl_setopt($ch, CURLOPT_URL, $customerUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:");
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
        echo 'Error al actualizar el cliente: ' . htmlspecialchars($response);
    } else {
        echo 'Cliente actualizado correctamente. <a href="clientes.php">Ver lista de clientes</a>';
    }

    // Cierra cURL
    curl_close($ch);
} else {
    echo "Método no permitido.";
}

// Función para convertir array a XML
function array_to_xml($data, &$xmlData) {
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            if (is_numeric($key)) {
                $key = 'item' . $key; // Cambia el nombre de la clave si es numérica
            }
            $subnode = $xmlData->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xmlData->addChild("$key", htmlspecialchars("$value"));
        }
    }
}

// Función para convertir a XML
function convert_to_xml($data) {
    $xmlData = new SimpleXMLElement('<?xml version="1.0"?><prestashop></prestashop>');
    array_to_xml($data, $xmlData);
    return $xmlData->asXML();
}
?>
