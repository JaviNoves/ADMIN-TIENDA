<?php
session_start();
if (!isset($_SESSION["userid"])) {
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pedido_id"]) && isset($_POST["nuevo_estado"])) {
    $pedido_id = $_POST["pedido_id"];
    $nuevo_estado = $_POST["nuevo_estado"];
    
    // Realizar la actualización del estado del pedido en la base de datos
    $sql = "UPDATE pedidos SET estado = '$nuevo_estado' WHERE codigo = $pedido_id";
    if ($conn->query($sql) === TRUE) {
        $mensaje = "Estado del pedido modificado correctamente";
    } else {
        $mensaje = "Error al modificar el estado del pedido: " . $conn->error;
    }
}

// Consultar la información de los pedidos y sus productos desde la base de datos
$sql_pedidos = "SELECT p.codigo, u.nombre AS cliente, GROUP_CONCAT(pr.descripcion SEPARATOR '<br>') AS productos, e.descripcion AS estado
                FROM pedidos p
                INNER JOIN usuarios u ON p.persona = u.codigo
                INNER JOIN estados e ON p.estado = e.codigo
                INNER JOIN detalle d ON p.codigo = d.codigo_pedido
                INNER JOIN productos pr ON d.codigo_producto = pr.codigo";

// Verificar si se ha enviado una solicitud de filtro por usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["filtro_usuario"]) && !empty($_POST["filtro_usuario"])) {
    $filtro_usuario = $_POST["filtro_usuario"];
    $sql_pedidos .= " WHERE u.nombre = '$filtro_usuario'";
}

// Verificar si se ha enviado una solicitud de filtro por producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["filtro_producto"]) && !empty($_POST["filtro_producto"])) {
    $filtro_producto = $_POST["filtro_producto"];
    if (strpos($sql_pedidos, "WHERE") !== false) {
        $sql_pedidos .= " AND pr.descripcion = '$filtro_producto'";
    } else {
        $sql_pedidos .= " WHERE pr.descripcion = '$filtro_producto'";
    }
}

// Verificar si se ha enviado una solicitud de filtro por fecha igual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["filtro_fecha_igual"]) && !empty($_POST["filtro_fecha_igual"])) {
    $filtro_fecha_igual = $_POST["filtro_fecha_igual"];
    if (strpos($sql_pedidos, "WHERE") !== false) {
        $sql_pedidos .= " AND p.fecha = '$filtro_fecha_igual'";
    } else {
        $sql_pedidos .= " WHERE p.fecha = '$filtro_fecha_igual'";
    }
}

$sql_pedidos .= " GROUP BY p.codigo";
$resultado_pedidos = $conn->query($sql_pedidos);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Página 3</title>
</head>
<body>
    <h1>Manejo del estado de los pedidos</h1>

    <?php if (isset($mensaje)) { ?>
        <p><?php echo $mensaje; ?></p>
    <?php } ?>

    <h2>Filtros</h2>
    <form method="POST" action="page3.php">
        <label for="filtro_usuario">Filtrar por Usuario:</label>
        <select name="filtro_usuario" id="filtro_usuario">
            <option value="">Todos</option>
            <?php
            $sql_usuarios = "SELECT nombre FROM usuarios";
            $resultado_usuarios = $conn->query($sql_usuarios);

            if ($resultado_usuarios->num_rows > 0) {
                while ($fila_usuario = $resultado_usuarios->fetch_assoc()) {
                    $nombre_usuario = $fila_usuario["nombre"];
                    echo "<option value='$nombre_usuario'>$nombre_usuario</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Filtrar">
    </form>

    <form method="POST" action="page3.php">
        <label for="filtro_producto">Filtrar por Producto:</label>
        <select name="filtro_producto" id="filtro_producto">
            <option value="">Todos</option>
            <?php
            $sql_productos = "SELECT descripcion FROM productos";
            $resultado_productos = $conn->query($sql_productos);

            if ($resultado_productos->num_rows > 0) {
                while ($fila_producto = $resultado_productos->fetch_assoc()) {
                    $nombre_producto = $fila_producto["descripcion"];
                    echo "<option value='$nombre_producto'>$nombre_producto</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Filtrar">
    </form>

    <form method="POST" action="page3.php">
        <label for="filtro_fecha_igual">Filtrar por Fecha igual a:</label>
        <input type="date" name="filtro_fecha_igual" id="filtro_fecha_igual">
        <input type="submit" value="Filtrar">
    </form>

    <table>
        <tr>
            <th>Pedido ID</th>
            <th>Cliente</th>
            <th>Productos</th>
            <th>Estado</th>
            <th>Cambiar Estado</th>
        </tr>
        <?php
        if ($resultado_pedidos->num_rows > 0) {
            while ($fila_pedido = $resultado_pedidos->fetch_assoc()) {
                $pedido_id = $fila_pedido["codigo"];
                $cliente = $fila_pedido["cliente"];
                $productos = $fila_pedido["productos"];
                $estado = $fila_pedido["estado"];

                echo "<tr>";
                echo "<td>$pedido_id</td>";
                echo "<td>$cliente</td>";
                echo "<td>$productos</td>";
                echo "<td>$estado</td>";
                echo "<td>";
                echo "<form method='POST' action='page3.php'>";
                echo "<input type='hidden' name='pedido_id' value='$pedido_id'>";
                echo "<select name='nuevo_estado'>";
                echo "<option value='1'>Pendiente</option>";
                echo "<option value='2'>Enviado</option>";
                echo "<option value='3'>Entregado</option>";
                echo "<option value='4'>Cancelado</option>";
                echo "</select>";
                echo "<input type='submit' value='Cambiar'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No se encontraron pedidos</td></tr>";
        }
        ?>
    </table>
    <a href="./dashboard.php">Volver</a>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
