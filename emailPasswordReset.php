<?php
  $message="";
  if(isset($_GET["email"])){
    include 'dbconnect.php'; //creates DB connection $db and sets $domain
    if (mysqli_connect_error($db)){
        die("Can't connect");
    }else{
        $email = mysqli_real_escape_string($db,urldecode($_GET["email"]));
        $user = mysqli_query($db,"SELECT * FROM Volunteer WHERE Email = '$email'");
        if(mysqli_num_rows($user) == 1){
          $user = mysqli_fetch_assoc($user);
          $userID = $user["ID"];
          $session = mysqli_query($db,"SELECT * FROM SessionKeys WHERE UserID = $userID");
          do{
    		$sessionKey = generateRandomString();
    		$keyMatch = mysqli_query($db,"SELECT * FROM SessionKeys WHERE SessionKey = '$sessionKey'");
    	  }while(mysqli_num_rows($keyMatch) > 0); //creates new session key repeatedly, until a unique key is created
        $timer = date('Y-m-d H:i:s',time())
    	  mysqli_query($db,"UPDATE SessionKeys SET SessionKey = '$sessionKey', Time = '$timer' WHERE userID = $userID"); //sets session key in database, time is updated automatically
          $msg = "Please visit <a href='http://".$domain."/PawsOnCall/passwordResetAction.php?session=".$sessionKey."'>here</a> to reset your password. This link is good for 1 hour.";
          echo $msg;
          $msg = wordwrap($msg,70);
          if(sendEmail($email,$user["Name"],"do_not_reply@".$domain,"Do Not Reply","Paws Whelping Journal Password Reset",$msg)){
		$message = "Password Reset Email Sent";
	  }else{
		$message = "There was an error";
	  }
        }else{
          $message = "Check email Address";
        }
     mysqli_close($db);
    }
  }
  function generateRandomString($length = 20) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }
  function sendEmail($emailto,$toname,$emailfrom,$fromname,$subject,$messagebody){
	$headers = 
		'Return-Path: ' . $emailfrom . "\r\n" . 
		'From: ' . $fromname . ' <' . $emailfrom . '>' . "\r\n" . 
		'X-Priority: 3' . "\r\n" . 
		'X-Mailer: PHP ' . phpversion() .  "\r\n" . 
		'Reply-To: ' . $fromname . ' <' . $emailfrom . '>' . "\r\n" .
		'MIME-Version: 1.0' . "\r\n" . 
		'Content-Transfer-Encoding: 8bit' . "\r\n" . 
		'Content-Type: text/plain; charset=UTF-8' . "\r\n";
	$params = '-f ' . $emailfrom;
	$test = mail($emailto, $subject, $messagebody, $headers, $params);
	// $test should be TRUE if the mail function is called correctly
	return $test;
  }
?>
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
                    <p class="subtitle has-text-grey">Please enter your email to reset you password</p>
                    <div class="box">
                        <figure class="avatar">
                            <img src="images\android-chrome-192x192.png">
                        </figure>
                        <?php echo $message;?>
                        <form action="emailPasswordReset.php">
                          Email <input class="input is-large" type="email" name="email">
                          <input class="button is-block is-info is-large is-fullwidth" type="submit" value="Submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
</body>

</html>
