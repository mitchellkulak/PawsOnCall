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
$litter_note = mysqli_real_escape_string($db,$ar['litterID']);

if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $litter_id = mysqli_real_escape_string($db,urldecode($_GET['litterID']));
    $i = 0;
    $theMasterArray = array();
    $dog_data = $db->query(
    "INSERT 
    INTO LitterUpdates
    (Note) 
    VALUES ($litter_note)
    WHERE id = $litter_id"
    );
    while ($result = $dog_data->fetch_assoc()){
        $theMasterArray[$i] = $result;
        $i++;
    }
    echo json_encode($theMasterArray);
        $db->close();
}
}
?>
