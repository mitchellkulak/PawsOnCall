<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'authenticate.php';
include 'dbconnect.php';
$input = json_decode(authenticate(urldecode($_GET['session'])), true);
if ($input['error'] == 'auth error') {
    $error = array('error' => 'auth error');
    echo json_encode($error);
}
else{
    
$MasterArray = array();

if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $i = 0;
    $volunteer_name = mysqli_real_escape_string($db,urldecode($_GET['search']));
    $volunteerName_data = $db->query(
    "SELECT ID, Name
    FROM Volunteer 
    WHERE name LIKE '%$volunteer_Name%'"
    );
    while ($result = $volunteerName_data->fetch_assoc()){
        $MasterArray[$i] = $result;
        $i++;
    }
    echo json_encode($MasterArray);
        $db->close();
}
}
?>