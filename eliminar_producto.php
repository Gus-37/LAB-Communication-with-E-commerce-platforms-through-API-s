<?php
// Habilitar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// URL de la API y clave
$apiUrl = 'http://192.168.1.86/api/products';
$apiKey = 'M7VEYVVB67TD5DZBSUUR74ELKPZHP6YF';

// Verificar si se ha pasado el ID del producto
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Inicializa cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . '/' . $productId); // URL para eliminar el producto
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // Método DELETE
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');

    // Ejecuta la solicitud
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Verifica si hay errores en cURL
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
    } else {
        // Verificar si la eliminación fue exitosa
        if ($httpCode == 200 || $httpCode == 204) {
            echo "Producto eliminado exitosamente!";
            // Redirigir a la lista de productos después de eliminar
            header('Location: listar_productos.php');
            exit();
        } else {
            echo "Error al eliminar el producto. Código HTTP: " . $httpCode;
        }
    }

    // Cierra cURL
    curl_close($ch);
} else {
    echo "ID del producto no proporcionado.";
}
?>
