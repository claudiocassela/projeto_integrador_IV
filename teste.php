<?php
session_start();
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
<script>
    function logoff(){
        if(confirm('Deseja realmente sair do Sistema?')){
            document.flogoff.submit();
        } else {
            alert('Operação Cancelada!');
        }
    }
</script>
<form name="flogoff" method="post" action="index.php"><input type="hidden" name="off" value="0"></form>
<div class="div">
    <p class="p" id="b">Seja bem vindo(a) <?php echo $_SESSION["id"]; ?></p>
    <img class="search" src="logoff.png" onclick="logoff()" title="Clique para sair do Sistema">
</div>
    <?php    
    include "banner.php";
    //inicio da medicao diaria

if(isset($_POST["d"]) and isset($_POST["m"])){
$di=$_POST["d"];
$me=$_POST["m"];
} else {
$da='2025-02-07';
$da=explode('-',$da);
$di=$da[2]; $me=$da[1];
}
include "conn.php";
$sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye, day(MEDICAO) as di, month(MEDICAO) as mu from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by MEDICAO");

$var='';
$i=0;   $s=0;   $tp=0;  $tb=0;
while ($li = mysqli_fetch_array($sql)) {
    $var.="['".$li['di']."/".$li['mu']."/".$li['ye']."',  ".number_format($li['tp'],0).",      ".number_format($li['pr'],0)."],";
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
    $sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'  order by tp LIMIT 1 OFFSET $i1");
    while ($li = mysqli_fetch_array($sql)) {
        $v=$li["tp"];
    }
    $sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'  order by tp LIMIT 1 OFFSET $i2");
    while ($li = mysqli_fetch_array($sql)) {
        $j=$li["tp"];
    }
    $j=($v+$j)/2;
    $sql=mysqli_query($conn,"select PRECIPTACAO as tp,year(MEDICAO) as ye from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'  order by tp LIMIT 1 OFFSET $i1");
    while ($li = mysqli_fetch_array($sql)) {
        $vp=$li["tp"];
    }
    $sql=mysqli_query($conn,"select preciptacao as tp,year(MEDICAO) as ye from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'  order by tp LIMIT 1 OFFSET $i2");
    while ($li = mysqli_fetch_array($sql)) {
        $jp=$li["tp"];
    }
    $jp=($vp+$jp)/2;
} else {
    $sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by tp LIMIT 1 OFFSET $i3");
    while ($li = mysqli_fetch_array($sql)) {
        $j=$li["tp"];
    }
    $sql=mysqli_query($conn,"select PRECIPTACAO as tp,year(MEDICAO) as ye from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by tp LIMIT 1 OFFSET $i3");
    while ($li = mysqli_fetch_array($sql)) {
        $jp=$li["tp"];
    }
}
$s=number_format($s,0);

//temperatura

$r=mysqli_query($conn,"select MIN(TEMPERATURA) as mn from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'");
while($l=mysqli_fetch_array($r)){
    $my=$l["mn"];
}
$z=mysqli_query($conn,"select MAX(TEMPERATURA) as mx from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'");
while($b=mysqli_fetch_array($z)){
    $mx=$b["mx"];
}
$a=mysqli_query($conn,"select avg(TEMPERATURA) as av from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'");
while($g=mysqli_fetch_array($a)){
    $av=$g["av"];
}
$a=mysqli_query($conn,"SELECT TEMPERATURA as tp, COUNT(TEMPERATURA) AS mo FROM projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' GROUP BY TEMPERATURA ORDER BY mo DESC LIMIT 1;");
while($g=mysqli_fetch_array($a)){
    $md=$g["tp"];
    $mv=$g["mo"];
}

//precipitação pluviométrica

$a=mysqli_query($conn,"select avg(PRECIPTACAO) as ap from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'");
while($g=mysqli_fetch_array($a)){
    $ap=$g["ap"];
}
$z=mysqli_query($conn,"select MAX(PRECIPTACAO) as mp from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'");
while($b=mysqli_fetch_array($z)){
    $mp=$b["mp"];
}
$z=mysqli_query($conn,"select MIN(PRECIPTACAO) as mn from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'");
while($b=mysqli_fetch_array($z)){
    $mn=$b["mn"];
}
$o=mysqli_query($conn,"select COUNT(PRECIPTACAO) as pp from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50'");
while($b=mysqli_fetch_array($o)){
    $pp=$b["pp"];
}
$a=mysqli_query($conn,"SELECT PRECIPTACAO as tp, COUNT(PRECIPTACAO) AS mo FROM projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' GROUP BY PRECIPTACAO ORDER BY mo DESC LIMIT 1;");
while($g=mysqli_fetch_array($a)){
    $mdp=$g["tp"];
    $mvp=$g["mo"];
}

//datas desconsideradas
$dd=0;
$k=mysqli_query($conn,"select COUNT(PRECIPTACAO) as dt from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by MEDICAO");
while($b=mysqli_fetch_array($k)){
    $dt=$b["dt"];
}

//indices
$rs=$dt-$dd;
$k=mysqli_query($conn,"select COUNT(PRECIPTACAO) as dt from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and PRECIPTACAO>'50' limit 1");
while($b=mysqli_fetch_array($k)){
    $ia=$b["dt"];  
    $ie=($ia/$rs)*100;
    $ia=($ia/$pp)*100;
}
$k=mysqli_query($conn,"select COUNT(PRECIPTACAO) as dt from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and (PRECIPTACAO>'25' and PRECIPTACAO<'50') limit 1");
while($b=mysqli_fetch_array($k)){
    $ib=$b["dt"];  
    $if=($ib/$rs)*100;
    $ib=($ib/$pp)*100;
}
$k=mysqli_query($conn,"select COUNT(PRECIPTACAO) as dt from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and (PRECIPTACAO>='5' and PRECIPTACAO<='25') limit 1");
while($b=mysqli_fetch_array($k)){
    $ic=$b["dt"];  
    $ig=($ic/$rs)*100;
    $ic=($ic/$pp)*100;
}
$k=mysqli_query($conn,"select COUNT(PRECIPTACAO) as dt from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and (PRECIPTACAO<'5' and PRECIPTACAO<>'0') limit 1");
while($b=mysqli_fetch_array($k)){
    $id=$b["dt"];  
    $ih=($id/$rs)*100;
    $id=($id/$pp)*100;
}

$df=($mx-$my);  $col='';   $dz=0;
for($w=0;$w<=$df;$w++){
    $rg=$my+$w;
    $k=mysqli_query($conn,"select COUNT(TEMPERATURA) as dt from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA='$rg' limit 1");
    while($b=mysqli_fetch_array($k)){
        $t[$w]=$b["dt"];  
        $dz=($t[$w]/$rs)*100;
        $col.='
            <div class="div lo">
                <p class="p">probabilidade de temperatura ==> '.number_format($dz,2).'% <i style="text-transform:lowercase;">para</i> '.number_format($rg,0).'&ordm;C</p>
            </div>

        ';
    }
}



$c='

<fieldset style="margin-top:20px; margin-bottom:20px; width:80%;">
            <legend style="color:red">precipitações pluviométricas >50mm registradas entre os anos de 1961 à 2024 na cidade de Sorocaba/SP -  <i>INMET(2024)</i></legend>

    <fieldset style="margin-top:20px; margin-bottom:20px; width:80%;">
            <legend>Dados a serem considerados no período</legend>
    <div class="div" style="border:1px solid white;">
        <p class="p"><b>parâmetro geral de medição manual no período ==> '.number_format($dt,0).' dias</b></p>
    </div>
    </fieldset>

    <fieldset style="margin-top:20px; margin-bottom:20px; width:80%;">
            <legend>Dados a serem considerados - temperartura</legend>
    <div class="div">
        <p class="p">temperatura máxima registrada no período ==> '.number_format($mx,0).'&ordm;C</p>
    </div>
    <div class="div">
        <p class="p">temperatura mínima registrada no período ==> '.number_format($my,0).'&ordm;C</p>
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
        <p class="p">precipitação pluviométrica mínima registrada no período ==> '.number_format($mn,0).'mm</p>
    </div>
    <div class="div">
        <p class="p">precipitação pluviométrica média registrada no período ==> '.number_format($ap,2).'mm</p>
    </div>
    <div class="div">
        <p class="p">precipitação pluviométrica moda registrada no período ==> '.number_format($mdp,0).'mm</p>
    </div>
    <div class="div">
        <p class="p">precipitação pluviométrica mediana registrada no período ==> '.number_format($jp,0).'mm</p>
    </div>
    </fieldset>

</fieldset>
';
?>

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
          title: '<?php echo "Gráfico comparativo entre temperaturas e precipitações pluviométricas >50mm entre os anos de 1961 à 2024 na cidade de Sorocaba/SP - INMET(2024)"; ?>',
          hAxis: {title: 'Ano',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
<?php
    echo $c;

//fim da medicao diaria
                    switch($me){
                        case 1: $zm='Janeiro'; break;
                        case 2: $zm='Fevereiro'; break;
                        case 3: $zm='Março'; break;
                        case 4: $zm='Abril'; break;
                        case 5: $zm='Maio'; break;
                        case 6: $zm='Junho'; break;
                        case 7: $zm='Julho'; break;
                        case 8: $zm='Agosto'; break;
                        case 9: $zm='Setembro'; break;
                        case 10: $zm='Outubro'; break;
                        case 11: $zm='Novembro'; break;
                        case 12: $zm='Dezembro'; break;
                    }
    $re = ($pp*100)/($dt-$dd);
    $te = ($mv*100)/($dt-$dd);
    echo '
    <style>
        .area{
                position:absolute;
                z-index:5000;
                top:0;
                right:0;
                margin-top:3px;
                margin-right:3px;
                display:none; 
                width:100px;       
            }
        #content {
        display: none;
        }
        .loc{
            position:absolute;
            background-color:white; 
            z-index:5000; 
            font-weight:bolder; 
            right:0; 
            margin-right: 3px;
            top:0;
            margin-top:1px;
            }
        input[type="text"]{
            color: transparent;
            text-shadow: 0 0 0 #000;
            padding: 6px 12px;
            width: 100px;
            cursor: pointer;
            right:0;
            margin-right:150px;
            top:0; 
            margin-top:-24px;
            height:16px;
            border:none;
            background-color:transparent;
            font-weight:bolder;
            font-style:italic;
        }
            input[type="text"]:focus{
                outline: none;
        }
            input:focus + div#content {
            display: block;
        }
        .lo{
            border:none;
        }
        .u{
            font-weight:bolder;
            color:blue;
            border:1px solid blue;
            text-align:center;
            width:138px;
            margin-left:2px;
            position:relative;
            float:left;
        }
        .u:hover{
            cursor:pointer;
            color:white;
            background-color:blue;

        }
        .point:hover{
            cursor:pointer;
            background-color: #dde8ec;
        }
    </style>
    <script>
        function dia(){
                document.fdia.submit();
            }
        function tem(){
                document.ftem.submit();
            }
        function plu(){
                document.fplu.submit();
            }
        function mes(){
                document.fmes.submit();
            }
        function ano(){
                document.fano.submit();
            }
    </script>
    <form name="fano" method="post" action="teste.php"><input type="hidden" name="od" value="4"></form>
    <form name="fmes" method="post" action="teste.php"><input type="hidden" name="od" value="3"></form>
    <form name="fdia" method="post" action="teste.php"><input type="hidden" name="od" value="2"></form>
    <form name="ftem" method="post" action="teste.php"><input type="hidden" name="od" value="1"></form>
    <form name="fplu" method="post" action="teste.php"><input type="hidden" name="od" value="0"></form>
    <fieldset style="margin-top:20px; margin-bottom:20px; width:80%; border:1px solid red">
            <legend style="color:red">datas consideradas</legend>
            <div class="div" style="border:none; margin-bottom:10px; border-bottom:2px solid blue; border-top:2px solid blue;">
                <p class="p u" title="clique aqui para ordenar por dia" onclick="dia()">dia</p>
                <p class="p u" title="clique aqui para ordenar por mês" onclick="mes()">mês</p>
                <p class="p u" title="clique aqui para ordenar por ano" onclick="ano()">ano</p>
                <p class="p u" title="clique aqui para ordenar por temperatura" onclick="tem()">temperatura</p>
                <p class="p u" title="clique aqui para ordenar por precipitação pluviométrica" onclick="plu()" style="width:300px">precipitação pluviomátrica</p>
            </div>
            ';
if(isset($_POST["od"])){
    $to=$_POST["od"];
} else {
    $to=0;
}
    switch($to){
        case 0: $sql=mysqli_query($conn,"select TEMPERATURA as tp, PRECIPTACAO as pr, MEDICAO as dia from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by PRECIPTACAO");
        break;
        case 1: $sql=mysqli_query($conn,"select TEMPERATURA as tp, PRECIPTACAO as pr, MEDICAO as dia from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by TEMPERATURA");
        break;
        case 2: $sql=mysqli_query($conn,"select TEMPERATURA as tp, PRECIPTACAO as pr, MEDICAO as dia from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by MEDICAO");
        break;
        case 3: $sql=mysqli_query($conn,"select TEMPERATURA as tp, PRECIPTACAO as pr, MEDICAO as dia from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by month(MEDICAO),day(MEDICAO),year(MEDICAO)");
        break;
        case 4: $sql=mysqli_query($conn,"select TEMPERATURA as tp, PRECIPTACAO as pr, MEDICAO as dia from projeto_integrador where TEMPERATURA<>'0' and PRECIPTACAO>='50' order by year(MEDICAO),day(MEDICAO),month(MEDICAO)");
        break;
    }
    echo $POST["od"];
$en='';
while($l=mysqli_fetch_array($sql)){
    if($l["pr"]>=75){
        $en= 'red';
    } else {
        $en= 'black';
    }
    echo 
        '<div class="div point" style="width:32%; margin-left:2px; border:1px solid '.$en.'; float:left;">
                <p class="p" style="margin-left:0px; width:100%; text-align:center;"><span style="font-weight:bolder; color:'.$en.'; ">'.date("d/m/Y",strtotime($l["dia"])).' ==> '.$l["tp"].'&ordm;C ==> '.$l["pr"].'mm</span></p>                
        </div>';
}
    echo '</fieldset>';
    ?>
    </body>
</html>