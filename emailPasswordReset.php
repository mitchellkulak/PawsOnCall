<?php
  $message="";
  if(isset($_GET["email"])){
    include 'dbconnect.php'; //creates DB connection $db and sets $domain
    if ($db->connect_error){
        die("Can't connect");
    }else{
        $email = mysqli_real_escape_string($db,urldecode($_GET["email"]));
        $user = $db->query("SELECT * FROM Volunteer WHERE Email = '$email'");
        if($user->num_rows == 1){
          $user = $user->fetch_assoc();
          $userID = $user["ID"];
          $session = $db->query("SELECT * FROM SessionKeys WHERE UserID = $userID");
          do{
    		$sessionKey = generateRandomString();
    		$keyMatch = $db->query("SELECT * FROM SessionKeys WHERE SessionKey = '$sessionKey'");
    	  }while($keyMatch->num_rows > 0); //creates new session key repeatedly, until a unique key is created
    	  $db->query("UPDATE SessionKeys SET SessionKey = '$sessionKey' WHERE userID = $userID"); //sets session key in database, time is updated automatically
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
     $db->close();
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
<html>
<?php echo $message;?>
  <form action="emailPasswordReset.php">
    Email <input type="email" name="email">
    <input type="submit" value="Submit">
  </form>
</html>
