<?php
  if($_GET["email"] != "")
  {
    include 'dbconnect.php';
    if ($db->connect_error){
        die("Can't connect");
    }
    else {
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
    			$db->query("UPDATE SessionKeys SET SessionKey = '$sessionKey' WHERE userID = '$userID'"); //sets session key in database, time is updated automatically
          $msg = "Please visit <a href='server.246valley.com/phpdev/passwordReset?session=".$sessionKey."'>here</a> to reset your password. This link is good for 1 hour.";
          echo $msg;
          $msg = wordwrap($msg,70);
          mail($email,"Paws On Call Password Reset",$msg);
        }else{
          echo "Check email Address";
        }
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
  $db-close();
?>
<html>
  <form action="emailPasswordReset.php">
    Email <input type="email" name="email">
    <input type="submit" value="Submit">
  </form>
</html>
