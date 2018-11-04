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

$ar = json_decode(file_get_contents('php://input'), true);
$momDog_note = mysqli_real_escape_string($db,$ar['Note']);
$dog_id =  mysqli_real_escape_string($db,$ar['DogID']);
$time = mysqli_real_escape_string($db,$ar['Time']);

if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $dog_data = $db->query(
    "INSERT 
    INTO LitterUpdates
    (Note) 
    VALUES ($mom_note)
    WHERE id = $dog_id"
    );
    $db->close();
}
}
?>