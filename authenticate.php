<?php 
function authenticate($sessionKey){
    include 'dbconnect.php';
    if ($db->connect_error)
    {
        die("Can't connect");
    }
    else {
	$sessionKey = mysqli_real_escape_string($db,$sessionKey);
        $session = $db->query("SELECT s.*,v.Admin FROM SessionKeys AS s, Volunteer AS v WHERE SessionKey = '$sessionKey' AND v.ID = s.UserID");
        $sessionrow = $session->fetch_assoc();
        if($session->num_rows == 1 && strtotime($sessionrow["Time"]) > time() - 3600 && $sessionrow["SessionKey"] != null){ //checks if session key valid and session last use <1hr ago
            $userID = $sessionrow["UserID"];
            $db->query("UPDATE SessionKeys SET Time = CURRENT_TIMESTAMP WHERE userID = '$userID'"); //updates session last used time
            //$isadmin = $db->query("SELECT Admin FROM Volunteer WHERE id = $userID");
            //$isadminrow = $isadmin->fetch_assoc();
            $arr = array('userID' => $userID, 'sessionKey' => $sessionKey, 'admin' => $sessionrow["Admin"], 'error' => 'none');
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
