<?php
include 'authenticate.php';
$session = "XamUpyJniQzJntrCLbFB";//$_GET['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    echo "<script>window.location.replace('login.php');</script>";
}else{
  include 'dbconnect.php';
  if ($db->connect_error){
      die("Can't connect");
  }
  else {

    $dogID = mysqli_real_escape_string($db,$_POST["loadID"]);
    $name = mysqli_real_escape_string($db,$_POST["name"]);
    $sex = mysqli_real_escape_string($db,$_POST["sex"]);
    $volunteerID = mysqli_real_escape_string($db,$_POST["volunteerID"]);
    $birthdate = mysqli_real_escape_string($db,$_POST["birthdate"]);
    $adoptiondate = mysqli_real_escape_string($db,$_POST["adoptiondate"]);
    $deathdate = mysqli_real_escape_string($db,$_POST["deathdate"]);
    $breed = mysqli_real_escape_string($db,$_POST["breed"]);
    $litterID = mysqli_real_escape_string($db,$_POST["litterID"]);

    if($dogID != 0){
      $SQL = "UPDATE Dogs SET
        Name = '$name',
        Sex = '$sex',
        VolunteerID = '$volunteerID',
        Birthdate = '$birthdate',
        Adoptiondate = '$adoptiondate',
        Deathdate = '$deathdate',
        Breed = '$breed',
        LitterID = $litterID
      WHERE id = $dogID";
    }else{
      $SQL = "INSERT INTO Dogs Values(null,'$name','$volunteerID','$sex','$birthdate','$adoptiondate','$deathdate','$breed',$litterID)";
    }
    $db->query($SQL);
    $error = mysqli_error($db);
    if($error = ""){
      echo "Record Updated/Added Successfully";
    }else{
      echo $error;
    }

  }
}
$db->close();
?>
<a href="admin.html">Return to admin page</a>
