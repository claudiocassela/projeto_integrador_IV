<div class="div">
    <p class="p" id="b">Seja bem vindo(a) <?php echo $_SESSION["id"]; ?></p>
</div>
    <?php
    include "banner.php";
    if(isset($_POST["page"])){
    switch($_POST["page"]){
        case "0":
        echo '
            <div class="div" style="border:none; border-bottom:1px solid brown; margin-top:10px; margin-bottom:15px;">
                <p class="p" style="width:100%; left:0; text-align:center; font-size:22px;">
                    cenário geral de temperatura e preciptação pluviométrica na cidade de sorocaba/sp
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
                    $sum=mysqli_query($conn,"select PRECIPTACAO as pr,TEMPERATURA as tp from projeto_integrador where year(MEDICAO)='$a' and month(MEDICAO)='$z'");
                    $rsum=0;    $tsum=0; $y=0; $t=0;    $xrsum=0;   $xtsum=0;
                    while($r=mysqli_fetch_array($sum)){
                        if($r["pr"]>0 or $r["tp"]>0){
                        $rsum=$rsum+$r["pr"];    $tsum=$tsum+$r["tp"];    $y++;   $t++;
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
                    echo '
                    <script>
                        function mes'.$i.'(){
                            document.fmes'.$i.'.submit();
                        }
                    </script>
                    <form name="fmes'.$i.'" method="post" action="index.php">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="m" value="'.$z.'">
                        <input type="hidden" name="y" value="'.$a.'">
                    </form>
                    <div id="link" onclick="mes'.$i.'()" class="div" style="width:80%; border:1px solid red; background-color:'.$color.';"><p class="p">mês ('.$z.') ==>   preciptação pluviométrica ==> '.$rsum.'('.$y.')['.$xrsum.'mm&sup3;] // temperatura ==> '.$tsum.'('.$t.')['.$xtsum.'&ordm;]</p></div>
                    '; 
                }
            }
        break;
        case "1":
            if(isset($_POST["m"]) and isset($_POST["y"])){
                        echo '
                            <div class="div" style="border:none; border-bottom:1px solid brown; margin-top:10px; margin-bottom:15px;">
                                <p class="p" style="width:100%; left:0; text-align:center; font-size:22px;">
                                    cenário mensal de temperatura e preciptação pluviométrica na cidade de sorocaba/sp
                                </p>
                            </div>
                        ';
                $m=$_POST["m"]; $y=$_POST["y"];
                echo '<div class="div" style="background-color:beige; border:1px solid beige;"><p class="p"><b>mês('.$m.')/ano ('.$y.')</b></p></div>';
                $sqm=mysqli_query($conn,"select PRECIPTACAO as pr,TEMPERATURA as tp, day(MEDICAO) as d from projeto_integrador where year(MEDICAO)='$y' and month(MEDICAO)='$m'");
                $left=-15; $key=''; $date=0;
                while($l=mysqli_fetch_array($sqm)){                    
                    $i++;   $d=$l["d"]; $pr=$l["pr"];   $tp=$l["tp"];   $top=0;
                    $height=$tp*10;  $top=0;
                    $left=$left+21; $top=380-$height;   $fleft=$left+5;
                    $key.='
                        <div class="bar1" style="left:'.$left.'px; height:'.$height.'px; top:'.$top.'px" title="'.$d.'/'.$m.'/'.$y.' - Temperatura de '.$tp.'&ordm;C"></div>
                        <p class="dia" style="left:'.$fleft.'px;">'.$d.'</p>
                    ';
                    $left=$left+21; $height=$pr*10;  $top=380-$height;
                    $key.='
                        <div class="bar2" style="left:'.$left.'px; height:'.$height.'px; top:'.$top.'px" title="'.$d.'/'.$m.'/'.$y.' - Preciptação Pluviométrica de '.$pr.'mm"></div>
                    ';
                    echo '
                        <script>
                            function graf'.$i.'(){
                                document.fgraf'.$i.'.submit();
                            }
                        </script>
                        <form name="fgraf'.$i.'" action="grafic.php" target="_blank" method="post">
                            <input type="hidden" name="d" value="'.$d.'">
                            <input type="hidden" name="m" value="'.$m.'">
                        </form>
                        <div onclick="graf'.$i.'()" class="div" id="link" style="width:80%; border:1px solid red;"><p class="p">dia ('.$d.') ==>   preciptação pluviométrica ==> ['.$pr.'] // temperatura ==> ['.$tp.'&ordm;]</p></div>
                    ';
                }
            }

            echo '
                <style>
                    .bar2{
                            width:21px;
                            border:1px solid blue;
                            position:absolute;
                            background-color:blue;
                        }
                    .bar1{
                            width:21px;
                            border:1px solid orange;
                            position:absolute;
                            background-color:orange;
                        }
                    .dia{
                            position:absolute;
                            color:green;
                            font-weight:bolder;
                            font-size:20px;
                            top:370px;
                            padding-left:5px;
                            padding-right:5px;
                            border-top:1px solid red;
                            width:20px;
                        }
                </style>
                            <div class="div" style="border:none; border-bottom:1px solid brown; margin-top:100px; margin-bottom:15px;">
                                <p class="p" style="width:100%; left:0; text-align:center; font-size:22px;">
                                    gráfíco de barras - cenário mensal de temperatura e preciptação pluviométrica na cidade de sorocaba/sp
                                </p>
                            </div>
                <div class="div" style="height:400px; margin-top:20px; margin-bottom:100px; border:none;">
                    '.$key.'
                </div>
            ';

        break;
        case "2":

        break;
    }
    
    } else {

    }
    ?>

