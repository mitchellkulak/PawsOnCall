<?php
function authenticate($sessionKey){
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
        $session = $db->query("SELECT * FROM SessionKeys WHERE SessionKey = '$sessionKey'");
        $sessionrow = $session->fetch_assoc();
        if($session->num_rows == 1 && strtotime($sessionrow["Time"]) > time() - 3600){ //checks if session key valid and session last use <1hr ago
            $userID = $sessionrow["UserID"];
            $db->query("UPDATE SessionKeys SET SessionKey = '$sessionKey' WHERE userID = '$userID'"); //updates session last used time
            $arr = array($userID,$sessionKey);
	    echo json_encode($arr);
        }
        else{
	    $error = "auth error";
            echo json_encode($error);
        }
    }
    $db->close();
}
?>
