<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
</head>
<br>
    <h1>Crear Cliente</h1>
    <form action="guardar_cliente.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" required><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" id="contraseña" required><br>

        <label for="active">Activado:</label>
        <input type="checkbox" name="active" id="active" value="1" checked><br>

        <input type="submit" value="Crear Cliente">
    </form>
    <br>
    <a href="clientes.php">Volver a la lista de clientes</a></br>
    <a href="index.php">Volver al Inicio</a>
</body>
</html>
