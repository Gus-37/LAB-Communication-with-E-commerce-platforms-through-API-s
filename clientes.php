<?php
// Habilitar la visualización de errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'api.php';

// URL de tu tienda Prestashop con el endpoint de clientes
$apiUrl = 'http://192.168.1.86/api/customers';

// Tu clave API generada desde el back-office de Prestashop
$apiKey = 'M7VEYVVB67TD5DZBSUUR74ELKPZHP6YF';

// Inicializa cURL
$ch = curl_init();

// Configura las opciones de cURL para obtener la lista de clientes
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');

// Ejecuta la solicitud
$response = curl_exec($ch);

// Manejo de errores de cURL
if (curl_errno($ch)) {
    echo 'Error en cURL: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

// Intenta convertir el XML
try {
    $customers = new SimpleXMLElement($response);

    // Encabezado de la tabla
    echo "<h3>Listado de Clientes:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Dirección de correo electrónico</th>
            <th>Acciones</th>
          </tr>";

    // Verifica que hay clientes
    if (isset($customers->customers->customer) && count($customers->customers->customer) > 0) {
        // Recorre los clientes y obtiene sus detalles
        foreach ($customers->customers->customer as $customer) {
            $customerId = (string)$customer['id'];

            // Llama a la API para obtener detalles del cliente
            $detailUrl = "http://192.168.1.86/api/customers/$customerId";

            // Configura cURL para la segunda llamada
            curl_setopt($ch, CURLOPT_URL, $detailUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $detailResponse = curl_exec($ch);

            // Verifica la respuesta del detalle
            if (curl_errno($ch)) {
                echo 'Error al obtener detalles del cliente: ' . curl_error($ch);
                continue;
            }

            // Convierte la respuesta en detalle
            $customerDetails = new SimpleXMLElement($detailResponse);

            // Obtiene los detalles requeridos
            $firstname = (string)$customerDetails->customer->firstname;
            $lastname = (string)$customerDetails->customer->lastname;
            $email = (string)$customerDetails->customer->email;

            // Imprime los detalles en la tabla
            echo "<tr>
                    <td>$customerId</td>
                    <td>$firstname</td>
                    <td>$lastname</td>
                    <td>$email</td>
                    <td>
                        <a href='modificar_cliente.php?id=$customerId'>Modificar</a> | 
                        <a href='eliminar_cliente.php?id=$customerId'>Eliminar</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No hay clientes disponibles.</td></tr>";
    }
    echo "</table>";
    echo "<a href='crear_cliente.php'>Crear Cliente</a></br>";
    echo "<a href='index.php'>Volver al Inicio</a>";
} catch (Exception $e) {
    echo 'Error al analizar la respuesta XML: ' . $e->getMessage();
}

// Cierra cURL
curl_close($ch);
?>
