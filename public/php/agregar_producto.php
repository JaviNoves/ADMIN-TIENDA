<!DOCTYPE html>
<html>
<head>
    <title>Agregar Nuevo Producto</title>
</head>
<body>
    <h1>Agregar Nuevo Producto</h1>

    <form method="POST" action="procesar_agregar_producto.php">

        <label for="nueva_descripcion">Descripción:</label>
        <input type="text" id="nueva_descripcion" name="nueva_descripcion" required><br>

        <label for="nuevo_precio">Precio:</label>
        <input type="number" id="nuevo_precio" name="nuevo_precio" required><br>

        <label for="nuevas_existencias">Existencias:</label>
        <input type="number" id="nuevas_existencias" name="nuevas_existencias" required><br>

        <label for="nuevo_titulo">Título de la imagen:</label>
        <input type="text" id="nuevo_titulo" name="nuevo_titulo" required><br>

        <label for="nueva_imagen">Ruta de la imagen:</label>
        <input type="text" id="nueva_imagen" name="nueva_imagen" required><br>

        <input type="submit" value="Agregar Producto">
    </form>
</body>
</html>
