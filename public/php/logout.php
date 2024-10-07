<?php
session_start();

// Destruir
session_destroy();

// Redireccionar
header("Location: ../Acceso.php");
exit();
?>
