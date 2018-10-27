<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
    $i = 0;
    $theMasterArray = array();
    $dog_name = wendy;
    $dog_data = $db->query(
    "SELECT d.id AS DogId, d.Name AS DogName, v.Name AS VolunteerName, d.Breed 
    FROM Volunteer AS v, Dogs AS d 
    WHERE d.name LIKE "%Wendy%" AND d.Sex = "F"
    );
    while ($result = $dog_data->fetch_assoc()){
        $theMasterArray[$i] = $result;
        $i++;
    }
    echo json_encode($theMasterArray);
        $db->close();
}
?>