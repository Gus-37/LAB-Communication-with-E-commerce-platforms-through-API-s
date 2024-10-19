<?php
// Habilitar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// URL de la API y clave
$apiUrl = 'http://192.168.1.86/api/products';
$apiKey = 'M7VEYVVB67TD5DZBSUUR74ELKPZHP6YF';

// Inicializa cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '?display=full'); // Obtener todos los datos de productos
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');

// Ejecuta la solicitud
$response = curl_exec($ch);

// Verifica errores
if (curl_errno($ch)) {
    echo 'Error en cURL: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

// Cierra cURL
curl_close($ch);

// Convertir la respuesta XML en un objeto SimpleXMLElement
$productsXml = simplexml_load_string($response);

// Verifica si se cargó el XML correctamente
if ($productsXml === false) {
    echo "Error al cargar el XML.";
    exit;
}

// Inicializa una tabla para mostrar los productos
echo "<h1>Lista de Productos</h1>";
echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Referencia</th>
            <th>Categoría</th>
            <th>Precio (imp. excl.)</th>
            <th>Precio (imp. incl.)</th>
            <th>Cantidad</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>";

// Recorre cada producto y extrae los datos necesarios
foreach ($productsXml->products->product as $product) {
    $id = (int)$product->id;
    $name = (string)$product->name->language;
    $reference = (string)$product->reference;
    $categoryId = (int)$product->id_category_default;
    $priceExclTax = (float)$product->price;
    $priceInclTax = $priceExclTax * 1.16; // Suponiendo un IVA del 16%
    $quantity = (int)$product->quantity;
    $active = ((int)$product->active == 1) ? 'Activo' : 'Inactivo';

    // Verifica si el producto tiene una imagen asignada
    if (isset($product->id_default_image)) {
        $imageId = (int)$product->id_default_image;
        // Construye la URL de la imagen
        $imageUrl = "http://192.168.1.86/api/images/products/$id/$imageId";

        // Obtener la imagen con cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');
        $imageData = curl_exec($ch);
        curl_close($ch);

        // Convertir la imagen a base64 para mostrarla
        $imageBase64 = base64_encode($imageData);
        $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;

        // Muestra los datos en la tabla
        echo "<tr>
                <td>$id</td>
                <td><img src='$imageSrc' alt='Imagen de producto' style='max-width: 100px;'></td>
                <td>$name</td>
                <td>$reference</td>
                <td>$categoryId</td>
                <td>$$priceExclTax</td>
                <td>$$priceInclTax</td>
                <td>$quantity</td>
                <td>$active</td>
                <td>
                    <a href='editproduct.php?id=$id'>Editar</a> | 
                    <a href='eliminar_producto.php?id=$id' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\");'>Eliminar</a>
                </td>
            </tr>";
    } else {
        echo "<tr>
                <td>$id</td>
                <td>No disponible</td>
                <td>$name</td>
                <td>$reference</td>
                <td>$categoryId</td>
                <td>$$priceExclTax</td>
                <td>$$priceInclTax</td>
                <td>$quantity</td>
                <td>$active</td>
                <td>
                    <a href='editproduct.php?id=$id'>Editar</a> | 
                    <a href='eliminar_producto.php?id=$id' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\");'>Eliminar</a>
                </td>
            </tr>";
    }
}

// Cierra la tabla
echo "</table>";

// Agrega el enlace para volver al índice
echo "<br><a href='crear_producto.php'>Crear Nuevos Productos</a></br>";
echo "<a href='index.php'>Volver al Inicio</a>";
?>
