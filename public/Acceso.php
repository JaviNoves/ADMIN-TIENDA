<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Realiza la conexión a la base de datos (asegúrate de reemplazar los valores de conexión con los tuyos)
  $conexion = mysqli_connect("localhost", "root", "", "daw");

  // Verifica la conexión a la base de datos
  if (!$conexion) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
  }

  // Escapa los valores de los campos de usuario y contraseña para evitar ataques de inyección SQL
  $username = mysqli_real_escape_string($conexion, $username);
  $password = mysqli_real_escape_string($conexion, $password);

  // Consulta SQL para verificar el usuario y la contraseña en la base de datos
  $query = "SELECT * FROM usuarios WHERE usuario = '$username' AND clave = '$password'";
  $resultado = mysqli_query($conexion, $query);

  // Verifica si se encontró un usuario con el nombre de usuario y contraseña proporcionados
  if (mysqli_num_rows($resultado) == 1) {
    $fila = mysqli_fetch_assoc($resultado);

    // Verifica si el usuario es un administrador
    if ($fila["admin"] == 1) {
      // Inicio de sesión exitoso

      // Crea una variable de sesión para almacenar el nombre de usuario
      $_SESSION["username"] = $username;
      $_SESSION['userid'] = 1; //Se crea una variable de sesión cuando el login es correcto.

      // Redirecciona al dashboard o a la página que desees mostrar después del inicio de sesión exitoso
      header("Location: ./php/dashboard.php");
      exit();
    } else {
      // No es un administrador, muestra un mensaje de error
      $error_message = "Acceso denegado. Debes ser administrador para ingresar.";
    }
  } else {
    // Usuario o contraseña incorrectos, muestra un mensaje de error
    $error_message = "Nombre de usuario o contraseña incorrectos.";
  }

  // Cierra la conexión a la base de datos
  mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Formulario de inicio de sesión</title>
  <link rel="stylesheet" type="text/css" href="./css/estiloLogin.css">
</head>
<body>
  <h1>Identifícate</h1>
  <?php if (isset($error_message)) : ?>
    <p><?php echo $error_message; ?></p>
  <?php endif; ?>
  <form method="POST" action="">
    <label for="username">Nombre de usuario:</label>
    <input type="text" id="username" name="username"><br>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password"><br>

    <input type="submit" value="Iniciar sesión">
  </form>
  <footer>
    <p>&copy; 2023 GAMERS</p>
  </footer>
</body>
</html>
