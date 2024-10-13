<?php
if(isset($_POST["d"]) and isset($_POST["m"])){
$di=$_POST["d"];
$me=$_POST["m"];
} else {
$da='2025-02-07';
$da=explode('-',$da);
$di=$da[2]; $me=$da[1];
}
include "conn.php";
$sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0'");
$dataPoints = array();
$i=0;   $s=0;   $tp=0;  $tb=0;
while ($li = mysqli_fetch_array($sql)) {
    array_push($dataPoints, array("x"=> $li['ye'], "y"=> (int)$li['tp']));
    $s=$s+$li["tp"];
    if($li["tp"]!=0){
        $i++;
    }
}

if($i%2==0){
    $i1=$i/2;
    $i2=$i1+1;
} else {
    $i3=(($i-1)/2)+1;
}

if(isset($i1)){
    $sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0' order by tp LIMIT 1 OFFSET $i1");
    while ($li = mysqli_fetch_array($sql)) {
        $v=$li["tp"];
    }
    $sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0' order by tp LIMIT 1 OFFSET $i2");
    while ($li = mysqli_fetch_array($sql)) {
        $j=$li["tp"];
    }
    $j=($v+$j)/2;
} else {
    $sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0' order by tp LIMIT 1 OFFSET $i3");
    while ($li = mysqli_fetch_array($sql)) {
        $j=$li["tp"];
    }
}
$s=number_format($s,0);
$r=mysqli_query($conn,"select MIN(TEMPERATURA) as mn from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0'");
while($l=mysqli_fetch_array($r)){
    $mn=$l["mn"];
}
$z=mysqli_query($conn,"select MAX(TEMPERATURA) as mx from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di'");
while($b=mysqli_fetch_array($z)){
    $mx=$b["mx"];
}
$a=mysqli_query($conn,"select avg(TEMPERATURA) as av from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0'");
while($g=mysqli_fetch_array($a)){
    $av=$g["av"];
}
$a=mysqli_query($conn,"SELECT TEMPERATURA as tp, COUNT(TEMPERATURA) AS mo FROM projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0' GROUP BY TEMPERATURA ORDER BY mo DESC LIMIT 1;");
while($g=mysqli_fetch_array($a)){
    $md=$g["tp"];
}

$c='<style>
        .div{
                width:80%;
            }
    </style>
    <div class="div">
        <p class="p">temperatura máxima registrada no período ==> '.number_format($mx,0).'&ordm;C</p>
    </div>
    <div class="div">
        <p class="p">temperatura mínima registrada no período ==> '.number_format($mn,0).'&ordm;C</p>
    </div>
    <div class="div">
        <p class="p">temperatura média registrada no período ==> '.number_format($av,0).'&ordm;C</p>
    </div>
    <div class="div">
        <p class="p">temperatura moda registrada no período ==> '.number_format($md,0).'&ordm;C</p>
    </div>
    <div class="div">
        <p class="p">temperatura mediana registrada no período ==> '.number_format($j,0).'&ordm;C</p>
    </div>
';
?>

<html>
<head>
	<link rel="icon" type="image/png" href="cold.png" />
    <link rel="stylesheet" href="style.css">
	<title>Preciptação pluviométrica e Temperaturas médias na cidade de Sorocaba/SP</title>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "<?php echo "Gráfico de  oscilações das temperaturas dos dias ".$di."/".$me." entre os anos de 1961 à 2024 na cidade de Sorocaba/SP"; ?>"
	},
	data: [{
		type: "area", //change type to bar, line, area, pie, etc  
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body style="padding-top:50px;">
<div id="chartContainer" style="height: 370px; margin-top:150px; margin:auto; width: 80%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<?php
    echo $c;
?>
</body>
</html>