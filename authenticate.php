<?php 
function authenticate($sessionKey){
    include 'dbconnect.php';
    if (mysqli_connect_error($db))
    {
        die("Can't connect");
    }
    else {
	$sessionKey = mysqli_real_escape_string($db,$sessionKey);
        $session = mysqli_query($db,"SELECT s.*,v.Admin FROM SessionKeys AS s, Volunteer AS v WHERE SessionKey = '$sessionKey' AND v.ID = s.UserID");
        $sessionrow = mysqli_fetch_assoc($session);
        if(mysqli_num_rows($session) == 1 && strtotime($sessionrow["Time"]) > time() - 3600 && $sessionrow["SessionKey"] != null){ //checks if session key valid and session last use <1hr ago
            $userID = $sessionrow["UserID"];
            $time = new DateTime(time());
            $timestamp = date('YYYY-MM-DD HH:MM:SS',$time);
            mysqli_query($db,"UPDATE SessionKeys SET Time = '$timestamp' WHERE userID = '$userID'"); //updates session last used time
            //$isadmin = mysqli_query($db,"SELECT Admin FROM Volunteer WHERE id = $userID");
            //$isadminrow = $isadminmysqli_fetch_assoc();
            $arr = array('userID' => $userID, 'sessionKey' => $sessionKey, 'admin' => $sessionrow["Admin"], 'error' => 'none');
	    return json_encode($arr);
        }
        else{
	    $error = array('error' => 'auth error');
            return json_encode($error);
        }
    }
    mysqli_close($db);
}
?>
