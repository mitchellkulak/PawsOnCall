<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$host = "localhost";
$username = "pawswhelp";
$password = "Ireallylikepuppies1!";
$db_name = "pawswhelpdb";
$db = mysqli_connect("$host","$username","$password","$db_name");
if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $ar = json_decode(file_get_contents('php://input'), true);
    $user_name = mysqli_real_escape_string($db,$ar[0]);
    $hashed_password = mysqli_real_escape_string($db,$ar[1]);
    $users = $db->query("SELECT email, id, password FROM Volunteer WHERE email = '$user_name' AND password = '$hashed_password'"); //checks for user in database
	if($users->num_rows == 1){ //continues if and only if 1 matching user is returned
		$userrow = $users->fetch_assoc();//pulls a row from the SQL return value
		$userID = $userrow["id"];//select the ID element from the SQL row
		$session = $db->query("SELECT userID, SessionKey, Time FROM SessionKeys WHERE userID = '$userID'"); //finds the users session key
		$sessionrow = $session->fetch_assoc();
		$sessionKey = $sessionrow["SessionKey"];
		if(strtotime($sessionrow["Time"]) < time() - 3600){ //checks session key age, renews if older than 1hr
			do{
				$sessionKey = generateRandomString();
				$keyMatch = $db->query("SELECT * FROM SessionKeys WHERE SessionKey = '$sessionKey'");
			}while($keyMatch->num_rows > 0); //creates new session key repeatedly, until a unique key is created
			$db->query("UPDATE SessionKeys SET SessionKey = '$sessionKey' WHERE userID = '$userID'"); //sets session key in database, time is updated automatically
		}
		$arr = array($userID,$sessionKey); 
		echo json_encode($arr); //RETURN USER AND SESSION ID **NEEDS EDITING**
    }else{
	$error = 'auth error';
    	echo json_encode($error); //RETURN ERROR VALUE **NEEDS EDITING**
    }
}
$db->close();
function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
