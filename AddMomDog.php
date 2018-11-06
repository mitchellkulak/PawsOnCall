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
    $dog_id =  "null";//mysqli_real_escape_string($db,$ar['ID']);
    $dog_name = "testName";//mysqli_real_escape_string($db,$ar['ID']);
    $vol_id = 1;//mysqli_real_escape_string($db,$ar['ID']);
    $collar_color = "blue";//mysqli_real_escape_string($db,$ar['ID']);
    $sex = "M";//mysqli_real_escape_string($db,$ar['ID']);
    $birth_date = 'current_timestamp';//mysqli_real_escape_string($db,$ar['ID']);
    $adopt_date = 'current_timestamp';//mysqli_real_escape_string($db,$ar['ID']);
    $death_date = 'current_timestamp';//mysqli_real_escape_string($db,$ar['ID']);
    $breed = "Poogle";//mysqli_real_escape_string($db,$ar['ID']);
    $litter_id = 1;//mysqli_real_escape_string($db,$ar['ID']);
    $session = $db->query(
    "SELECT * 
    FROM Dogs 
    WHERE ID = '$dog_id'"
    );
    $sessionrow = $session->fetch_assoc();
    if($session->num_rows == 0){ //checks if session key valid and session last use <1hr ago
        if ($db->query(
        "INSERT INTO Dogs 
        VALUES($dog_id, '$dog_name', $vol_id, '$sex', 
        $birth_date, $adopt_date, $death_date, '$breed', $litter_id)") === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $db->error;
        }
    }
    else{
    $error = array('error' => 'auth error');
        return json_encode($error);
    }
    $db->close();
}
}
?>