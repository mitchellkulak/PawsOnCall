<?php
  include 'authenticate.php';
  $session = $_GET['session'];
  $auth = json_decode(authenticate(urldecode($session)), true);
  if ($auth['error'] == 'auth error') {
      $error = array('error' => 'auth error');
      echo json_encode($error);
      echo "<script>window.location.replace('login.php');</script>";
  }else{
     $userID = $auth["userID"];
     include 'dbconnect.php';
     $password = mysqli_real_escape_string($db,$_POST["password"]);
     $verpassword = mysqli_real_escape_string($db,$_POST["verpassword"]);
     if($password != $verpassword){
	$message = "Passwords Must Match";
     }elseif($password == "da39a3ee5e6b4b0d3255bfef95601890afd80709"){ //SHA1 Hash for a blank password
	$message = "Password Cannot Be Blank";
     }elseif($password!=""){
	$SQL = "UPDATE Volunteer SET Password = '$password' WHERE ID = $userID";
	if($db->query($SQL)){
		$message = "Password Updated Successfully";
	}else{
		$message = mysqli_error($db);
	}
     }else{
	$message = "<br>";
     }
     $db->close();  
  }
?>
<script type="text/javascript" src="scripts.js"></script>
<!DOCTYPE HTML>
<html>
	<?php echo $message;?>
	<form action="passwordResetAction.php?session=<?php echo $session;?>" method="post">
		Enter Password:<input type="password" name="password" id="password" required /><br>
		Reenter Password:<input type="password" name="verpassword" id="verpassword" required /><br>
		<input onclick="RewritePassword();" type="submit" value="Reset">
	</form>
</html>
<script>
	function RewritePassword()
	{
   		document.getElementById("password").value = SHA1(document.getElementById("password").value);
                document.getElementById("verpassword").value = SHA1(document.getElementById("verpassword").value);
        }
</script>
