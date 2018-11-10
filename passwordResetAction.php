<?php
  include 'authenticate.php';
  $session = $_GET['session'];
  $auth = json_decode(authenticate(urldecode($session)), true);
  if ($auth['error'] == 'auth error') {
      $error = array('error' => 'auth error');
      echo json_encode($error);
      echo "<script>window.location.replace('login.php');</script>";
  }else{
     include 'dbconnect.php';
     $password = mysqli_real_escape_string($db,$_POST["password"]);
     $verpassword = mysqli_real_escape_string($db,$_POST["verpassword"]);
     echo $password;
     echo $verpassword;
     $db->close();  
  }
?>
<script>include 'scripts.js';</script>
<html>
	<form action="passwordResetAction.php" method="post">
		Enter Password:<input onchange="Rewritepassword(this);" type="password" name="password" id="password">
		Reenter Password:<input onchange="Rewriteverpassword(this);" style="password" name="verpassword" id="verpassword">
		<input type="submit" value="Reset">
	</form>
</html>
<script>
	function Rewritepassword(data)
	{
   		alert(data);
   		document.getElementById("password").value = sha1(data);
	}
        function Rewriteverpassword(data)
        {
                alert(data);
                document.getElementById("verpassword").value = sha1(data);
        }
</script>
