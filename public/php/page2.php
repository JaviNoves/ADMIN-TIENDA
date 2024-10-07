<?php
session_start();
if (!isset($_SESSION["userid"])) {
    // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    header("Location: ../Acceso.php");
    exit();
}

// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
$host = "localhost";
$usuario_db = "root";
$clave_db = "";
$nombre_db = "daw";

$conn = new mysqli($host, $usuario_db, $clave_db, $nombre_db);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Verificar si se ha enviado una solicitud de modificación de producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["producto_id"]) && isset($_POST["descripcion"])) {
    $producto_id = $_POST["producto_id"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $existencias = $_POST["existencias"];
    
    // Realizar la actualización del producto en la base de datos
    $sql = "UPDATE productos SET descripcion = '$descripcion', precio = '$precio', existencias = '$existencias' WHERE codigo = $producto_id";
    if ($conn->query($sql) === TRUE) {
        $mensaje = "Producto modificado correctamente";
    } else {
        $mensaje = "Error al modificar el producto: " . $conn->error;
    }
}

// Consultar la información de los productos desde la base de datos
$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Página 2</title>
</head>
<body>
    <h1>Modificar productos</h1>

    <?php if (isset($mensaje)) { ?>
        <p><?php echo $mensaje; ?></p>
    <?php } ?>

    <table>
        <tr>
            <th>Producto ID</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Existencias</th>
            <th>Acción</th>
        </tr>
        <?php
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $producto_id = $fila["codigo"];
                $descripcion = $fila["descripcion"];
                $precio = $fila["precio"];
                $existencias = $fila["existencias"];

                echo "<tr>";
                echo "<form method='post' action='page2.php'>";
                echo "<td>$producto_id</td>";
                echo "<td>$descripcion</td>";
                echo "<td><input type='text' name='precio' value='$precio'></td>";
                echo "<td><input type='text' name='existencias' value='$existencias'></td>";
                echo "<input type='hidden' name='producto_id' value='$producto_id'>";
                echo "<input type='hidden' name='descripcion' value='$descripcion'>";
                echo "<td><input type='submit' value='Modificar'></td>";
                echo "</form>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No se encontraron productos</td></tr>";
        }
        ?>
    </table>
    <a href="agregar_producto.php?redirect=page2">Agregar Nuevo Producto</a>
    <a href="./dashboard.php">Volver</a>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>



