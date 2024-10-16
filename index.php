<?php
    session_start();
    if(isset($_POST["off"])){
        session_unset();
        session_destroy();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="js/jquery-1.4.min.js"></script>
	<link rel="icon" type="image/png" href="cold.png" />
	<title>Precipitação pluviométrica e Temperaturas médias na cidade de Sorocaba/SP</title>
</head>
<body>
<?php
    include "conn.php";
    if(isset($_SESSION["id"])){
        include "start.php";
    } else {
        include "login.php";
    }
?>
</body>
</html>