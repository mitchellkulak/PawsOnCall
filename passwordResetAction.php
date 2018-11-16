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
		$message = "Password Updated Successfully, click on the Paws logo to log in";
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
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login to PAWS Whelping database</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <!-- Bulma Version 0.7.2-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css" />
    <link rel="stylesheet" type="text/css" href="login.css">
    <script src="scripts.js"></script>

    <!-- favicon stuff-->
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
    <!-- favicon stuff-->
    
</head>

<body>
    <section class="hero is-success is-fullheight">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <h3 class="title has-text-grey">Password Reset</h3>
                    <p class="subtitle has-text-grey">Please enter your new password</p>
                    <div class="box">
                        <figure class="avatar">
                            <a href="login.html"><img src="images\android-chrome-192x192.png"></a>
                        </figure>
                        	<?php echo $message;?>
                          <form action="passwordResetAction.php?session=<?php echo $session;?>" method="post">
                            Enter Password:<input type="password" name="password" id="password" required /><br>
                            Reenter Password:<input type="password" name="verpassword" id="verpassword" required /><br>
                            <input onclick="RewritePassword();" type="submit" value="Reset">
                          </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
</body>

</html>
<script>
	function RewritePassword()
	{
   		document.getElementById("password").value = SHA1(document.getElementById("password").value);
                document.getElementById("verpassword").value = SHA1(document.getElementById("verpassword").value);
        }
</script>
