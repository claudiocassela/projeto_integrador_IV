<?php

$da='2025-02-7';
$da=explode('-',$da);
$di=$da[2]; $me=$da[1]; $ye=$da[0];
include "conn.php";
$sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0'");
$dataPoints = array();
while ($li = mysqli_fetch_array($sql)) {
    array_push($dataPoints, array("x"=> $li['ye'], "y"=> (int)$li['tp']));
}

?>

<html>
<head>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Temperaturas dos dias [07/02] na cidade de Sorocaba/SP"
	},
	data: [{
		type: "column", //change type to bar, line, area, pie, etc  
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>


<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>