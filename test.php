<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$host = "localhost";
$username = "pawswhelp";
$password = "Ireallylikepuppies!";
$db_name = "pawswhelpdb";
$theMasterArray = array();
$db = mysqli_connect("$host","$username","$password","$db_name");
if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $i = 0;
    $theMasterArray = array();
    $dog_data = $db->query(
    "SELECT * 
    FROM Dogs"
    );
    while ($result = $dog_data->fetch_assoc()){
        $theMasterArray[i] = $result;
        $i++;
    }
    echo json_encode($theMasterArray);
        $db->close();
}
?>