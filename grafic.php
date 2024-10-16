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

$var='';
//['2013',  12,      10],

//$dataPoints = array();
$i=0;   $s=0;   $tp=0;  $tb=0;
while ($li = mysqli_fetch_array($sql)) {
    $var.="['".$li['ye']."',  ".number_format($li['tp'],0).",      ".number_format($li['pr'],0)."],";
    //array_push($dataPoints, array("x"=> $li['ye'], "y"=> (int)$li['tp']));
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

//temperatura

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

//precipitação pluviométrica

$a=mysqli_query($conn,"select avg(PRECIPTACAO) as ap from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0'");
while($g=mysqli_fetch_array($a)){
    $ap=$g["ap"];
}
$z=mysqli_query($conn,"select MAX(PRECIPTACAO) as mp from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di'");
while($b=mysqli_fetch_array($z)){
    $mp=$b["mp"];
}
$o=mysqli_query($conn,"select COUNT(PRECIPTACAO) as pp from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and PRECIPTACAO<>'0'");
while($b=mysqli_fetch_array($o)){
    $pp=$b["pp"];
}

//datas desconsideradas
$o=mysqli_query($conn,"select COUNT(PRECIPTACAO) as dd from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and (PRECIPTACAO='0' and TEMPERATURA='0')");
while($b=mysqli_fetch_array($o)){
    $dd=$b["dd"];
}
$k=mysqli_query($conn,"select COUNT(PRECIPTACAO) as dt from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di'");
while($b=mysqli_fetch_array($k)){
    $dt=$b["dt"];
}

$c='

<fieldset style="margin-top:20px; margin-bottom:20px; width:80%;">
            <legend style="color:red">Dados registrados manualmente dos dias '.$di.'/'.$me.' entre os anos de 1961 à 2024 na cidade de Sorocaba/SP - <i>INMET(2024)</i></legend>

    <fieldset style="margin-top:20px; margin-bottom:20px; width:80%;">
            <legend>Dados a serem considerados no período</legend>
    <div class="div">
        <p class="p">parâmetro geral de medição manual no período ==> '.number_format($dt,0).' dias</p>
    </div>
    <div class="div">
        <p class="p">datas desconsideradas por falta de medição manual no período ==> '.number_format($dd,0).' dias</p>
    </div>
    </fieldset>

    <fieldset style="margin-top:20px; margin-bottom:20px; width:80%;">
            <legend>Dados a serem considerados - temperartura</legend>
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
    </fieldset>

    <fieldset style="margin-top:20px; margin-bottom:20px; width:80%;">
            <legend>Dados a serem considerados - precipitação pluviométrica</legend>
    <div class="div">
        <p class="p">precipitação pluviométrica máxima registrada no período ==> '.number_format($mp,0).'mm</p>
    </div>
    <div class="div">
        <p class="p">precipitação pluviométrica média registrada no período ==> '.number_format($ap,2).'mm</p>
    </div>
    <div class="div">
        <p class="p">precipitação pluviométrica dias registrados no período ==> '.number_format($pp,0).' dias</p>
    </div>
    </fieldset>

</fieldset>
';
?>


<html>
<head>
	<link rel="icon" type="image/png" href="cold.png" />
    <link rel="stylesheet" href="style.css">
	<title>Precipitação pluviométrica e Temperaturas médias na cidade de Sorocaba/SP</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Ano', 'Temperatura', 'Precipitação Pluviométrica'],
          <?php echo $var; ?>
        ]);

        var options = {
          title: '<?php echo "Gráfico comparativo de  oscilações entre temperaturas e precipitações pluviométricas dos dias ".$di."/".$me." entre os anos de 1961 à 2024 na cidade de Sorocaba/SP - INMET(2024)"; ?>',
          hAxis: {title: 'Ano',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
 <body style="padding-top:50px; width:100%;">
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
<?php
    echo $c;
?>
  </body>
</html>