<?php
    $men='';
    if(isset($_POST["log"]) and isset($_POST["pas"])){
        $log=$_POST["log"]; $pas=md5($_POST["pas"]);
        $sql=mysqli_query($conn,"select * from login where us='$log' and pw='$pas'");
        if(mysqli_num_rows($sql)>0){
            while($l=mysqli_fetch_array($sql)){
                $_SESSION["id"]=$l["no"];
                echo "<script>location.href='index.php';</script>";
            }
        } else {
            $men="usuário ou senha incorreto(s)!";
        }
    }
?>
    <script>
            function envialog(){
			document.FenviaLog.submit();
			}
    </script>
    <form name="FenviaLog" action="index.php" method="post">
		<div class="dcenter" style="margin-top:150px">
            <img class="ilo" src="cold.png">
			<p class="p" style="margin-top:55px; color:blue;"><b>login:<b></p>
			<input style="margin-top:50px;" name="log">
			<p class="p" style="margin-top:95px; color:blue;"><b>password:<b></p>
			<input style="margin-top:90px;;" type="password" name="pas">
			<button class="btn" style="left:40%; margin-top:130px; width:200px; height:40px; " onclick="envialog()">ACESSAR</button>
            <p class="p" style="margin-top:185px; color:gray;"></p>
			</form>
		</div>
