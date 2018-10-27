<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
$host = "localhost";
$username = "pawswhelp";
$password = "Ireallylikepuppies!";
$db_name = "pawswhelpdb";

$db = mysqli_connect("$host","$username","$password","$db_name");
if ($db->connect_error)
{
    die("Can't connect");
}
else {
    $i = 0
    $theMasterArray = array();
    $dog_data = $db->query(
    "SELECT * 
    FROM Dogs"
    );
    while ($result = $dog-data->fetch_assoc()){
        $theMasterArray[i] = $result;
        $i++;
    }
    echo json_encode($theMasterArray);
        $db->close();
}
?>