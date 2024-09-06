<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<link rel="icon" type="image/png" href="cold.png" />
	<title>Preciptação pluviométrica e Temperaturas médias na cidade de Sorocaba/SP</title>
</head>
<body>
<?php
    session_start();
    include "conn.php";
    if(isset($_SESSION["id"])){
        include "start.php";
    } else {
        include "login.php";
    }
?>
</body>
</html>