<?php
// Habilitar la visualización de errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'api.php';

// URL de la API de Prestashop para clientes
$apiUrl = 'http://192.168.1.86/api/customers';

// Clave API
$apiKey = 'M7VEYVVB67TD5DZBSUUR74ELKPZHP6YF';

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$contraseña = $_POST['contraseña']; // Asegúrate de que esto esté configurado
$activo = isset($_POST['activo']) ? 1 : 0;

// Crear un nuevo cliente en formato XML
$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
    <customer>
        <firstname><![CDATA[$nombre]]></firstname>
        <lastname><![CDATA[$apellidos]]></lastname>
        <email><![CDATA[$email]]></email>
        <passwd><![CDATA[$contraseña]]></passwd> <!-- Asegúrate de incluir la contraseña aquí -->
        <active><![CDATA[$activo]]></active>
        <id_default_group><![CDATA[3]]></id_default_group> <!-- Cambia esto si usas otro ID de grupo -->
    </customer>
</prestashop>
XML;

// Inicializar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

// Ejecutar la solicitud
$response = curl_exec($ch);

// Manejar errores de cURL
if (curl_errno($ch)) {
    echo 'Error en cURL: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

// Cerrar cURL
curl_close($ch);

// Mostrar la respuesta de la API
if (strpos($response, '<errors>') !== false) {
    echo "Error al crear el cliente: <br>";
    echo htmlentities($response);
} else {
    echo "Cliente creado con éxito. <br>";
}

// Redirigir a la página de lista de clientes después de 3 segundos
header("refresh:3;url=clientes.php");
echo "<br>Redirigiendo a la lista de clientes...";
?>
