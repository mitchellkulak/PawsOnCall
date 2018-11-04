if(strtotime($sessionrow["Time"]) < time() - 3600){ //checks session key age, renews if older than 1hr
			do{
				$sessionKey = generateRandomString();
				$keyMatch = $db->query("SELECT * FROM SessionKeys WHERE SessionKey = '$sessionKey'");
			}while($keyMatch->num_rows > 0); //creates new session key repeatedly, until a unique key is created
			$db->query("UPDATE SessionKeys SET SessionKey = '$sessionKey' WHERE userID = '$userID'"); //sets session key in database, time is updated automatically
		}
		$arr = array('userID' => $userID,'sessionKey' => $sessionKey, 'error' => 'none'); 
		echo json_encode($arr); //RETURN USER AND SESSION ID **NEEDS EDITING**
    }else{
	$error = array('error' => 'auth error');
    	echo json_encode($error); //RETURN ERROR VALUE **NEEDS EDITING**
    }

<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'authenticate.php';
$input = json_decode(authenticate(urldecode($_GET['session'])), true);
if ($input['error'] == 'auth error') {
    $error = array('error' => 'auth error');
    echo json_encode($error);
}
else {
$host = "localhost";
$username = "pawswhelp";
$password = "Ireallylikepuppies1!";
$db_name = "pawswhelpdb";
$theMasterArray = array();
$db = mysqli_connect("$host","$username","$password","$db_name");

if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $ar = json_decode(file_get_contents('php://input'), true);
    $dog_id =  2;//mysqli_real_escape_string($db,$ar['ID']);
    $dog_name = "testName";//mysqli_real_escape_string($db,$ar['ID']);
    $vol_id = "testVolID";//mysqli_real_escape_string($db,$ar['ID']);
    $collar_color = "blue";//mysqli_real_escape_string($db,$ar['ID']);
    $sex = "M";//mysqli_real_escape_string($db,$ar['ID']);
    $birth_date = 'current_timestamp';//mysqli_real_escape_string($db,$ar['ID']);
    $adopt_date = 'current_timestamp';//mysqli_real_escape_string($db,$ar['ID']);
    $death_date = 'current_timestamp';//mysqli_real_escape_string($db,$ar['ID']);
    $breed = "Poogle";//mysqli_real_escape_string($db,$ar['ID']);
    $litter_id = 4;//mysqli_real_escape_string($db,$ar['ID']);
    $session = $db->query(
    "SELECT * 
    FROM Dogs 
    WHERE ID = '$dog_id'"
    );
    $sessionrow = $session->fetch_assoc();
    if($session->num_rows == 0){ //checks if session key valid and session last use <1hr ago
        if ($db->query(
        "INSERT INTO Dogs 
        VALUES($dog_id, $dog_name, $vol_id, $collar_color, $sex, 
        $birth_date, $adopt_date, $death_date, $breed, $litter_id)") === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $db->error;
        }
        );
    }
    else{
    $error = array('error' => 'auth error');
        return json_encode($error);
    }
    $db->close();
}
?>