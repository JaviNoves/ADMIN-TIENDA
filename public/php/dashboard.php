<?php
session_start();
if (!isset($_SESSION["userid"])) {
    
    header("Location: Acceso.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <div class="container">
    <h2>Welcome, <?php echo $_SESSION["username"]; ?></h2>
    <div class="buttons">
      <a href="./page1.php">Page 1</a>
      <a href="./page2.php">Page 2</a>
      <a href="./page3.php">Page 3</a>
    </div>
    <a href="./logout.php">Logout</a>
  </div>
</body>
</html>
