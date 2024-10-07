<?php
// Conexión a la base de datos (reemplaza los valores con los de tu configuración)
$host = "localhost";
$usuario_db = "root";
$clave_db = "";
$nombre_db = "daw";

$conn = new mysqli($host, $usuario_db, $clave_db, $nombre_db);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Verificar si se ha enviado una solicitud de agregar un nuevo producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nueva_descripcion"]) && isset($_POST["nuevo_precio"]) && isset($_POST["nuevas_existencias"]) && isset($_POST["nuevo_titulo"]) && isset($_POST["nueva_imagen"])) {

    $descripcion = $_POST["nueva_descripcion"];
    $precio = $_POST["nuevo_precio"];
    $existencias = $_POST["nuevas_existencias"];
    $titulo_imagen = $_POST["nuevo_titulo"];
    $ruta_imagen = $_POST["nueva_imagen"];
    

        $sql_insertar = "INSERT INTO productos (descripcion, precio, existencias, titulo, imagen) VALUES ( '$descripcion', '$precio', '$existencias','$titulo_imagen','$ruta_imagen')";
        if ($conn->query($sql_insertar) === TRUE) {
            $mensaje = "Nuevo producto agregado correctamente";
        } else {
            $mensaje = "Error al agregar el producto: " . $conn->error;
        }
    }
 else {
    $mensaje = "Por favor, rellena todos los campos del formulario";
}

// Redireccionar a la página 2
header("Location: page2.php?mensaje=" . urlencode($mensaje));
exit();

// Cerrar la conexión a la base de datos
$conn->close();
?>
