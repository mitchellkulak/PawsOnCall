<?php
include '../authenticate.php';
session_start();
$session = $_SESSION['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    //echo "<script>window.location.replace('../login.html');</script>";
}else{
  include '../dbconnect.php';
  $userID = mysqli_real_escape_string($db,$_POST["loadID"]);
  if ($db->connect_error){
      die("Can't connect");
  }
  elseif(isset($_POST["Save"])) {

    $name = mysqli_real_escape_string($db,$_POST["name"]);
    $email = mysqli_real_escape_string($db,$_POST["email"]);
    $phone = mysqli_real_escape_string($db,$_POST["phone"]);
    $address = mysqli_real_escape_string($db,$_POST["address"]);
    $city = mysqli_real_escape_string($db,$_POST["city"]);
    $state = mysqli_real_escape_string($db,$_POST["state"]);
    $zip = mysqli_real_escape_string($db,$_POST["zip"]);
    $admin = mysqli_real_escape_string($db,$_POST["admin"]);

    if($userID != 0){
      $SQL = "UPDATE Volunteer SET
        Name = '$name',
        Email = '$email',
        Phone = '$phone',
        Address = '$address',
        City = '$city',
        State = '$state',
        ZIP = '$zip',
        Admin = '$admin'
      WHERE id = $userID";
    }else{
      $SQL = "INSERT INTO Volunteer Values(null,'$name','$email','$phone','$address','$city','$state','$zip',null,$admin)";
    }
    $error = mysqli_error($db);
    if($db->query($SQL)){
      $message = "Record Added/Updated";
    }else{
      $message = mysqli_error($db);
    }   

  }elseif(isset($_POST["Delete"])){
    if($dogID != 0){
      die("Cannot Delete");
    }else{
      $SQL = "DELETE FROM Volunteer WHERE ID = $userID";
    }
    if($db->query($SQL)){
      $message = "Record Deleted";
    }else{
      $message = mysqli_error($db);
    }    
  }
}
$db->close();
?>

<?php echo $message;?>
<a href="index.php">Return to admin page</a>
