<?php
include '../authenticate.php';
$session = "XamUpyJniQzJntrCLbFB";//$_GET['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    echo "<script>window.location.replace('../login.php');</script>";
}else{
  include '../dbconnect.php';
  $dogID = mysqli_real_escape_string($db,$_POST["loadID"]);
  if ($db->connect_error){
      die("Can't connect");
  }
  elseif(isset($_POST["Save"])) {
    $name = mysqli_real_escape_string($db,$_POST["name"]);
    $sex = mysqli_real_escape_string($db,$_POST["sex"]);
    $volunteerID = mysqli_real_escape_string($db,$_POST["volunteerID"]);
    $birthdate = mysqli_real_escape_string($db,$_POST["birthdate"]);
    $adoptiondate = mysqli_real_escape_string($db,$_POST["adoptiondate"]);
    $deathdate = mysqli_real_escape_string($db,$_POST["deathdate"]);
    $breed = mysqli_real_escape_string($db,$_POST["breed"]);
    $litterID = mysqli_real_escape_string($db,$_POST["litterID"]);
    $stillborn = mysqli_real_escape_string($db,$_POST["stillborn"]);

    if($dogID != 0){
      $SQL = "UPDATE Dogs SET
        Name = '$name',
        Sex = '$sex',
        VolunteerID = '$volunteerID',
        Birthdate = '$birthdate',
        Adoptiondate = '$adoptiondate',
        Deathdate = '$deathdate',
        Breed = '$breed',
        LitterID = $litterID,
        Stillborn = $stillborn
      WHERE id = $dogID";
    }else{
      $SQL = "INSERT INTO Dogs Values(null,'$name','$volunteerID','$sex','$birthdate','$adoptiondate','$deathdate','$breed',$litterID,$stillborn)";
    }
    if($db->query($SQL)){
      echo "Record Updated/Added Successfully";
    }else{
      echo mysqli_error($db);
    }

  }elseif(isset($_POST["Delete"])){
    if($dogID != 0){
      die("Cannot Delete");
    }else{
      $SQL = "DELETE FROM Dogs WHERE ID = $dogID";
    }
    if($db->query($SQL)){
      echo "Record Deleted";
    }else{
      echo mysqli_error($db);
    }    
  }
}
$db->close();
?>
<a href="index.php">Return to admin page</a>
