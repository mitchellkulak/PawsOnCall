<?php
authenticate('XamUpyJniQzJntrCLbFB'); //REMOVE
function authenticate($sessionKey){
    include 'dbconnect.php';
    if ($db->connect_error)
    {
        die("Can't connect");
    }
    else {
        $session = $db->query("SELECT * FROM SessionKeys WHERE SessionKey = '$sessionKey'");
        $sessionrow = $session->fetch_assoc();
        if($session->num_rows == 1 && strtotime($sessionrow["Time"]) > time() - 3600 && $sessionrow["SessionKey"] != null){ //checks if session key valid and session last use <1hr ago
            $userID = $sessionrow["UserID"];
            $db->query("UPDATE SessionKeys SET SessionKey = '$sessionKey' Time = CURRENT_TIMESTAMP WHERE userID = '$userID'"); //updates session last used time
            $isadmin = $db->query("SELECT Admin FROM Volunteer WHERE id = $userID");
            $isadminrow = $isadmin->fetch_assoc();
            $arr = array('userID' => $userID, 'sessionKey' => $sessionKey, 'admin' => $isadminrow["Admin"], 'error' => 'none');
	    return json_encode($arr);
        }
        else{
	    $error = array('error' => 'auth error');
            return json_encode($error);
        }
    }
    $db->close();
}
?>
