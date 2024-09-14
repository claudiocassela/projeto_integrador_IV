<div class="div">
    <p class="p" id="b">Seja bem vindo(a) <?php echo $_SESSION["id"]; ?></p>
</div>
    <?php
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
            echo '<div class="div" style="width:80%; border:1px solid red; background-color:'.$color.';"><p class="p">mês ('.$z.') ==>   preciptação pluviométrica ==> '.$rsum.'('.$y.')['.$xrsum.'mm&sup3;] // temperatura ==> '.$tsum.'('.$t.')['.$xtsum.'&ordm;]</p></div>'; 
        }
    }
    ?>

