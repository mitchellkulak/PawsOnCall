<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'dbconnect.php';
if (mysqli_connect_error($db))
{
    die("Can't connect");
}
else {
    $ar = json_decode(file_get_contents('php://input'), true);
    $user_name = mysqli_real_escape_string($db,$ar['user_name']);
    $hashed_password = mysqli_real_escape_string($db,$ar['hashed_password']);
    $users = mysqli_query($db,"SELECT email, id, password, admin FROM Volunteer WHERE email = '$user_name' AND password = '$hashed_password'"); //checks for user in database
	if(mysqli_num_rows($users) == 1){ //continues if and only if 1 matching user is returned
		$userrow = mysqli_fetch_assoc($users);//pulls a row from the SQL return value
		$userID = $userrow["id"];//select the ID element from the SQL row
		$admin = $userrow["admin"];
		$session = mysqli_query($db,"SELECT userID, SessionKey, Time FROM SessionKeys WHERE userID = '$userID'"); //finds the users session key
		$sessionrow = mysqli_fetch_assoc($session);
		$sessionKey = $sessionrow["SessionKey"];
		if(strtotime($sessionrow["Time"]) < time() - 3600){ //checks session key age, renews if older than 1hr
			do{
				$sessionKey = generateRandomString();
				$keyMatch = mysqli_query($db,"SELECT * FROM SessionKeys WHERE SessionKey = '$sessionKey'");
			}while(mysqli_num_rows($keyMatch) > 0); //creates new session key repeatedly, until a unique key is created
            $timestamp = date('Y-m-d H:i:s',time());
			mysqli_query($db,"UPDATE SessionKeys SET SessionKey = '$sessionKey', Time = '$timestamp' WHERE userID = '$userID'"); //sets session key in database, time is updated automatically
		}
		$arr = array('userID' => $userID,'sessionKey' => $sessionKey,'admin' => $admin, 'error' => 'none'); 
		echo json_encode($arr); //RETURN USER AND SESSION ID **NEEDS EDITING**
    }else{
	$error = array('error' => 'auth error');
    	echo json_encode($error); //RETURN ERROR VALUE **NEEDS EDITING**
    }
}
mysqli_close($db);
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
