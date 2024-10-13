<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="js/jquery-1.4.min.js"></script>
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