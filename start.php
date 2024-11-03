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
    if(isset($_POST["page"])){
    switch($_POST["page"]){
        case "0":
        echo '
            <div class="div" style="border:none; border-bottom:1px solid brown; margin-top:10px; margin-bottom:15px;">
                <p class="p" style="width:100%; left:0; text-align:center; font-size:22px;">
                    cenário geral de temperatura e precipitação pluviométrica na cidade de sorocaba/sp - INMET(2024)
                </p>
            </div>
        ';
            $i=0;
            $sqa=mysqli_query($conn,"select year(MEDICAO) as ano from projeto_integrador group by year(MEDICAO)");
            while($s=mysqli_fetch_array($sqa)){
                $a=$s["ano"];
                echo '<div class="div" style="background-color:beige; border:1px solid beige;"><p class="p"><b>ano ('.$a.')</b></p></div>';
                $sql=mysqli_query($conn,"select month(MEDICAO) as mes from projeto_integrador where year(MEDICAO)='$a' group by month(MEDICAO)");
                while($m=mysqli_fetch_array($sql)){
                    $z=$m["mes"];   
                    switch($z){
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
                    $sum=mysqli_query($conn,"select PRECIPTACAO as pr,TEMPERATURA as tp from projeto_integrador where year(MEDICAO)='$a' and month(MEDICAO)='$z'");
                    $rsum=0;    $tsum=0; $y=0; $t=0;    $xrsum=0;   $xtsum=0;   $link='';   $ps=0;
                    while($r=mysqli_fetch_array($sum)){
                        if($r["pr"]>0 or $r["tp"]>0){
                        $rsum=$rsum+$r["pr"];    $tsum=$tsum+$r["tp"];    $y++;   $t++;
                        $link='id="link"';  $ps=1;
                        }
                    }
                    if($rsum>0 or $tsum>0){
                    $xrsum=$rsum/$y;    $xrsum=number_format($xrsum,3);
                    $xtsum=$tsum/$t;    $xtsum=number_format($xtsum,1);
                    $color='white';
                    } else {
                        $color='#FB9C9C';
                    }
                    $i++;
                    if($ps==1){
                        $ps='document.fmes'.$i.'.submit();';
                    }
                    echo '
                    <script>
                        function mes'.$i.'(){
                            '.$ps.'
                        }
                    </script>
                    <form name="fmes'.$i.'" method="post" action="index.php">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="m" value="'.$z.'">
                        <input type="hidden" name="y" value="'.$a.'">
                    </form>
                    <div '.$link.' onclick="mes'.$i.'()" class="div" style="width:80%; border:1px solid red; background-color:'.$color.';"><p class="p">('.$zm.') ==>   precipitação pluviométrica ==> '.$rsum.'('.$y.')['.$xrsum.'mm&sup3;] // temperatura ==> '.$tsum.'('.$t.')['.$xtsum.'&ordm;]</p></div>
                    '; 
                }
            }
        break;
        case "1":
            if(isset($_POST["m"]) and isset($_POST["y"])){
                $m=$_POST["m"]; $y=$_POST["y"];
                    switch($m){
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
$sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,day(MEDICAO) as dia from projeto_integrador where month(MEDICAO)='$m' and year(MEDICAO)='$y' and TEMPERATURA<>'0'");

$var='';
$i=0;   $s=0;   $tp=0;  $tb=0;
while ($li = mysqli_fetch_array($sql)) {
    $var.="['".$li['dia']."',  ".number_format($li['tp'],0).",      ".number_format($li['pr'],0)."],";
    $s=$s+$li["tp"];
    if($li["tp"]!=0){
        $i++;
    }
}
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Dia', 'Temperatura', 'Precipitação Pluviométrica'],
          <?php echo $var; ?>
        ]);

        var options = {
          title: '<?php echo "Gráfico comparativo de  oscilações entre temperaturas e precipitações pluviométricas do mês de ".$zm."/".$y." na cidade de Sorocaba/SP - INMET(2024)"; ?>',
          hAxis: {title: '<?php echo $zm."/".$y; ?>',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>

<?php

                        echo '
                            <div class="div" style="border:none; border-bottom:1px solid brown; margin-top:10px; margin-bottom:15px;">
                                <p class="p" style="width:100%; left:0; text-align:center; font-size:22px;">
                                    cenário mensal de temperatura e precipitação pluviométrica na cidade de sorocaba/sp - INMET(2024)
                                </p>
                            </div>
                        ';

                echo '<div class="div" style="background-color:beige; border:1px solid beige;"><p class="p"><b>'.$zm.'/'.$y.'</b></p></div>';
                $sqm=mysqli_query($conn,"select PRECIPTACAO as pr,TEMPERATURA as tp, day(MEDICAO) as d from projeto_integrador where year(MEDICAO)='$y' and month(MEDICAO)='$m'");
                $date=0;
                while($l=mysqli_fetch_array($sqm)){                    
                    $i++;   $d=$l["d"]; $pr=$l["pr"];   $tp=$l["tp"];
                    echo '
                        <script>
                            function graf'.$i.'(){
                                document.fgraf'.$i.'.submit();
                            }
                        </script>
                        <form name="fgraf'.$i.'" action="index.php" method="post">
                            <input type="hidden" name="d" value="'.$d.'">
                            <input type="hidden" name="m" value="'.$m.'">
                            <input type="hidden" name="page" value="2">
                        </form>
                        <div onclick="graf'.$i.'()" class="div" id="link" style="width:80%; border:1px solid red;"><p class="p">dia ('.$d.') ==>   precipitação pluviométrica ==> ['.$pr.'] // temperatura ==> ['.$tp.'&ordm;]</p></div>
                    ';
                }
            }

        break;
        case "2":
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
$sql=mysqli_query($conn,"select TEMPERATURA as tp,PRECIPTACAO as pr,year(MEDICAO) as ye from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and TEMPERATURA<>'0'");

$var='';
$i=0;   $s=0;   $tp=0;  $tb=0;
while ($li = mysqli_fetch_array($sql)) {
    $var.="['".$li['ye']."',  ".number_format($li['tp'],0).",      ".number_format($li['pr'],0)."],";
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
    $my=$l["mn"];
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
    $mv=$g["mo"];
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
$z=mysqli_query($conn,"select MIN(PRECIPTACAO) as mn from projeto_integrador where month(MEDICAO)='$me' and day(MEDICAO)='$di' and PRECIPTACAO<>'0'");
while($b=mysqli_fetch_array($z)){
    $mn=$b["mn"];
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
        <p class="p">precipitação pluviométrica dias registrados no período ==> '.number_format($pp,0).' dias</p>
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
          title: '<?php echo "Gráfico comparativo de  oscilações entre temperaturas e precipitações pluviométricas dos dias ".$di."/".$me." entre os anos de 1961 à 2024 na cidade de Sorocaba/SP - INMET(2024)"; ?>',
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
        .div{
                z-index:100;
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
    </style>
        <fieldset style="margin-top:20px; margin-bottom:20px; width:80%; border:1px solid red">
            <legend style="color:red">resultados da análise para o dia '.$di.' do mês de '.$zm.' do ano de 2025</legend>
            <div class="div" style="border:none;"></div>
            <fieldset>
            <legend>precipitação pluviométrica</legend>            
            <div class="div" style="border:none;>
                <p class="p"><span style="font-weight:bolder;">probabilidade de precipitação pluviométrica ==> '.number_format($re,2).'%</span></p>                
            </div>
            <fieldset>
            <legend>cenário parcial</legend>
            <div class="div lo">
                <p class="p">probabilidade de precipitação pluviométrica com índice chuva muito forte ==> '.number_format($ia,2).'%</p>                
            </div>
            <div class="div lo">
                <p class="p">probabilidade de precipitação pluviométrica com índice chuva forte ==> '.number_format($ib,2).'%</p>                
            </div>
            <div class="div lo">
                <p class="p">probabilidade de precipitação pluviométrica com índice chuva moderada ==> '.number_format($ic,2).'%</p>                
            </div>
            <div class="div lo">
                <p class="p">probabilidade de precipitação pluviométrica com índice chuva fraca ==> '.number_format($id,2).'%</p>                
            </div>
            </fieldset>
            <fieldset>
            <legend>cenário geral</legend>
            <div class="div lo">
                <p class="p">probabilidade de precipitação pluviométrica com índice chuva muito forte ==> '.number_format($ie,2).'%</p>                
            </div>
            <div class="div lo">
                <p class="p">probabilidade de precipitação pluviométrica com índice chuva forte ==> '.number_format($if,2).'%</p>                
            </div>
            <div class="div lo">
                <p class="p">probabilidade de precipitação pluviométrica com índice chuva moderada ==> '.number_format($ig,2).'%</p>                
            </div>
            <div class="div lo">
                <p class="p">probabilidade de precipitação pluviométrica com índice chuva fraca ==> '.number_format($ih,2).'%</p>                
            </div>
            </fieldset>
            </fieldset>
            <div class="div" style="border:none; top:-335px; height:0">
            <input type="text" value="ÍNDICES***">
                <div id="content" class="loc">
                        &#9928;&nbsp;Chuva fraca: abaixo de 5,0 mm/h;<br>
                        &#9928;&nbsp;Chuva moderada: entre 5,0 e 25 mm/h;<br>
                        &#9928;&nbsp;Chuva forte: entre 25,1 e 50 mm/h;<br>
                        &#9928;&nbsp;Chuva muito forte: acima de 50,0 mm/h;
                </div>
            </div>


            <fieldset>
            <legend>temperatura</legend>
                <fieldset>
            '.$col.'
                </fieldset>
            </fieldset>
        </fieldset>

    ';
        break;
        case 3:
echo    '
            <style>
                .text{
                    position:relative;
                    text-transform:none;
                    width:100%;
                    text-align:justify;
                    font-size:18px;
                    top:0;
                    margin-top:2px;
                    line-height:2;
                    }
            </style>
            <div class="div" style="border:none; border-bottom:2px solid red; margin-top:40px; margin-bottom:40px; width:90%;">
                <p class="p" style="width:95%; text-align:center; font-weight:bolder;">
                    Instruções de utilização:
                </p>
            </div>
            <div class="div" style="border:none; width:80%; height:850px;">
                <p class="text">
                    &#9728;&nbsp;Acesse o menú visão geral;<br>
                        &nbsp;&nbsp;&#10053;&nbsp;Ao acessar esta página, você terá uma compilação geral do banco de dados, organizados anualmente e subdivididos mensalmente;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Informações referentes ao mês são acumulativas ao período e quantidade de dias aferidas manualmente;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Dados inconsistentes (background:vermelho) foram desconsiderados pelo Sistema;<br>
                    &#9728;&nbsp;Clique no mês, correspondente ao ano, para ter acesso a uma visão detalhada do mês;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Ao acessar esta página, você terá um detalhamento do período solicitado, um gráfico com os dados do mês preterido;   
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Disposição diária das informações de temperatura e precipitação pluviométrica do mês pesquisado;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Gráfico das métricas de temperatura e precipitação pluviométrica do mês pesquisado;<br>
                   &#9728;&nbsp;Clique no dia para ter acesso a análise detalhada do dia futuro;<br>  
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Disposição das informações de temperatura e precipitação pluviométrica do dia pesquisado;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Gráfico das métricas de temperatura e precipitação pluviométrica do dia pesquisado;<br> 
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Análise comportamental, em tempo de execução, das informações dispostas;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10053;&nbsp;Análise antecipativa da data pesquisada;<br><br>
                    &#9728;&nbsp;<b>Índices adotados</b> (https://canaltech.com.br/meio-ambiente/o-que-e-indice-pluviometrico-e-como-ele-mede-milimetros-de-chuva-240698/, acesso em 01.10.2024)<br><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#9928;&nbsp;Chuva fraca: abaixo de 5,0 mm/h;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#9928;&nbsp;Chuva moderada: entre 5,0 e 25 mm/h;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#9928;&nbsp;Chuva forte: entre 25,1 e 50 mm/h;<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#9928;&nbsp;Chuva muito forte: acima de 50,0 mm/h;<br>
                </p>
            </div>
        ';
        break;
    }
    
    } else {
        echo    '
            <style>
                .text{
                    position:relative;
                    text-transform:none;
                    width:100%;
                    text-align:justify;
                    font-size:18px;
                    top:0;
                    margin-top:2px;
                    line-height:2;
                    }
            </style>
            <div class="div" style="border:none; border-bottom:2px solid red; margin-top:40px; margin-bottom:40px; width:90%;">
                <p class="p" style="width:95%; text-align:center; font-weight:bolder;">
                    Análise de Padrões e Tendências Climáticas em Sorocaba: Um Estudo Analítico-Histórico com Dados do INMET (1961-2023)
                </p>
            </div>
            <div class="div" style="border:none; width:80%; height:300px; margin-bottom:30px;">
                <p class="text">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    O aumento na frequência de fenômenos climáticos extremos, como secas e enchentes, levanta preocupações sobre a sustentabilidade e segurança de várias comunidades na cidade de Sorocaba. O presente estudo pretende analisar a correlação entre temperatura e precipitação em Sorocaba de 1961 a 2023, de modo a buscar entender como essas variáveis interagem e impactam em tais eventos. O projeto pretende ainda incluir o desenvolvimento de uma plataforma de mineração de dados, que permitirá a visualização e análise dos dados climáticos, que por sua vez poderá contribuir para estratégias de adaptação e mitigação. O projeto poderá 
também oferecer oportunidades de manipulação dos dados, através de modelagem matemática para estudantes de áreas tecnológicas.
                </p>
            </div>
        ';
    }
    ?>