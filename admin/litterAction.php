<?php
include '../authenticate.php';
session_start();
$session = $_SESSION['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    echo "<script>window.location.replace('../login.html');</script>";
}else{
  include '../dbconnect.php';
  $litterID = mysqli_real_escape_string($db,$_POST["loadID"]);
  if ($db->connect_error){
      die("Can't connect");
  }elseif(isset($_POST["Save"])) {

    $volunteerID = mysqli_real_escape_string($db,$_POST["volunteerID"]);
    $motherID = mysqli_real_escape_string($db,$_POST["motherID"]);
    $fatherID = mysqli_real_escape_string($db,$_POST["fatherID"]);
    $startWhelp = mysqli_real_escape_string($db,$_POST["startWhelp"]);
    $endWhelp = mysqli_real_escape_string($db,$_POST["endWhelp"]);
    $startWean = mysqli_real_escape_string($db,$_POST["startWean"]);
    $endWean = mysqli_real_escape_string($db,$_POST["endWean"]);
    $startDeworm = mysqli_real_escape_string($db,$_POST["startDeworm"]);
    $endDeworm = mysqli_real_escape_string($db,$_POST["endDeworm"]);

    if($litterID != 0){
      $SQL = "UPDATE Litter SET
        VolunteerID = '$volunteerID',
        MotherID = '$motherID',
        FatherID = '$fatherID',
        StartWhelp = '$startWhelp',
        EndWhelp = '$endWhelp',
        StartWean = '$startWean',
        EndWean = '$endWean',
        StartDeworm = '$startDeworm',
        EndDeworm = '$endDeworm'
      WHERE ID = $litterID";
    }else{
      $SQL = "INSERT INTO Litter Values(null,'$volunteerID','$motherID','$fatherID','$startWhelp','$endWhelp','$startWean','$endWean','$startDeworm','$endDeworm')";
    }
    $error = mysqli_error($db);
    if($db->query($SQL)){
      echo "Record Added/Updated";
    }else{
      echo mysqli_error($db);
    } 

  }elseif(isset($_POST["Delete"])){
    if($dogID != 0){
      die("Cannot Delete");
    }else{
      $SQL = "DELETE FROM Litter WHERE ID = $litterID";
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
