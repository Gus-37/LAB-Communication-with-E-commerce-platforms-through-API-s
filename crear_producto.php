<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
</head>
<body>
    <h1>Crear Nuevo Producto</h1>

    <form action="guardar_producto.php" method="POST">
        <label for="name">Nombre del Producto:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="price">Precio:</label>
        <input type="text" id="price" name="price" required><br><br>

        <label for="quantity">Cantidad:</label>
        <input type="text" id="quantity" name="quantity" required><br><br>

        <label for="reference">Referencia:</label>
        <input type="text" id="reference" name="reference" required><br><br>

        <label for="category_id">ID de la Categoría:</label>
        <input type="text" id="category_id" name="category_id" required><br><br>

        <label for="active">Activado:</label>
        <input type="checkbox" name="active" id="active" value="1" checked><br><br>


        <input type="submit" value="Crear Producto">
    </form>

    <br>
    <a href="listar_productos.php">Volver a la lista de Productos</a></br>
    <a href="index.php">Volver al Inicio</a>
</body>
</html>





