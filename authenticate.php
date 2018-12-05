<?php
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
function authenticate($sessionKey){
    include 'dbconnect.php';
    if (mysqli_connect_error($db))
    {
        die("Can't connect");
    }
    else {
	$sessionKey = mysqli_real_escape_string($db,$sessionKey);
        $session = mysqli_query($db,"SELECT s.*,v.Admin,v.Email FROM SessionKeys AS s, Volunteer AS v WHERE SessionKey = '$sessionKey' AND v.ID = s.UserID");
        $sessionrow = mysqli_fetch_assoc($session);
        if(mysqli_num_rows($session) == 1 && strtotime($sessionrow["Time"]) > time() - 3600 && $sessionrow["SessionKey"] != null){ //checks if session key valid and session last use <1hr ago
            $userID = $sessionrow["UserID"];
            $timestamp = date('Y-m-d H:i:s',time());
            mysqli_query($db,"UPDATE SessionKeys SET Time = '$timestamp' WHERE userID = '$userID'"); //updates session last used time
            //$isadmin = mysqli_query($db,"SELECT Admin FROM Volunteer WHERE id = $userID");
            //$isadminrow = $isadminmysqli_fetch_assoc();
            $arr = array('userID' => $userID, 'sessionKey' => $sessionKey, 'admin' => $sessionrow["Admin"], 'error' => 'none', "email" => $sessionrow["Email"]);
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
