<?php
    echo '
    <script>
        function visao(){
                document.fvisao.submit();
            }
        function grafic(){
                document.fgrafic.submit();
            }
        function principal(){
                document.fprincipal.submit();
            }
    </script>
    <form name="fvisao" method="post" action="index.php"><input type="hidden" name="page" value="0"></form>
    <form name="fprincipal" method="post" action="index.php"></form>
    <div class="div" style="margin-top:5px; margin-bottom:5px;">
        <div class="sub_menu" onclick="principal()">
            principal
        </div>
        <div class="sub_menu" onclick="visao()">
            visão geral
        </div>
    </div>
    
    ';
?>