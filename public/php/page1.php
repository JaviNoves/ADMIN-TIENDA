<?php
session_start();
if (!isset($_SESSION["userid"])) {   //control flujo
    
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

// Verificar si se ha enviado una solicitud de cambio de estado de usuario
if (isset($_POST["usuario_id"]) && isset($_POST["accion"])) {
    $usuario_id = $_POST["usuario_id"];
    $accion = $_POST["accion"];

    // Realizar la actualización del estado del usuario en la base de datos
    $sql = "UPDATE usuarios SET activo = ($accion + 1) % 2 WHERE codigo = $usuario_id";
    if ($conn->query($sql) === TRUE) {
        // Redireccionar a la página actual después de actualizar el estado
        header("Location: page1.php");
        exit();
    } else {
        echo "Error al actualizar el estado del usuario: " . $conn->error;
    }
}

// Consultar la información de los usuarios desde la base de datos
$sql = "SELECT * FROM usuarios";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Página 1</title>
</head>
<body>
    <h1>Usuarios</h1>
    <table>
        <tr>
            <th>Nombre de usuario</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        <?php
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $usuario_id = $fila["codigo"];
                $usuario = $fila["usuario"];
                $estado = ($fila["activo"] == 1) ? "Activo" : "Inactivo";
                $accion = ($fila["activo"] == 1) ? "Dar de baja" : "Dar de alta";

                echo "<tr>";
                echo "<td>$usuario</td>";
                echo "<td>$estado</td>";
                echo "<td>
                        <form method='post' action='page1.php'>
                            <input type='hidden' name='usuario_id' value='$usuario_id'>
                            <input type='hidden' name='accion' value='" . $fila["activo"] . "'>
                            <input type='submit' value='$accion'>
                        </form>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron usuarios</td></tr>";
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

