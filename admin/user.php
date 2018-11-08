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
    $userrow = array('Name' => "", 'Email' => "", 'Phone' => "", 'Address' => "", 'City' => "", 'State' => "", 'ZIP' => "");
    if($_GET['loadID'] != ""){
      $userID = mysqli_real_escape_string($db,$_GET['loadID']);
      $user = $db->query("SELECT * FROM Volunteer WHERE id = $userID");
      $userrow = $user->fetch_assoc();
    }
    $users = $db->query("SELECT ID, Name FROM Volunteer");
  }
}
$db->close();
?>
<html>
  <form action="user.php">
    <select name='loadID'>
      <option value="0">New User</option>
      <?php while($subuser = $users->fetch_assoc()){echo "<option value=".$subuser["ID"].">".$subuser["Name"]."</option>";}?>
    </select>
    <input type="submit" value="Load">
  </form>
  <form action="userAction.php" method="post">
    <input type="text" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $userID?>">
    Name: <input type="text" name="name" value="<?php echo $userrow['Name']?>"><br>
    Email: <input type="email" name="email" value="<?php echo $userrow['Email']?>"><br>
    Phone: <input type="tel" name="phone" value="<?php echo $userrow['Phone']?>"><br>
    Address: <input type="text" name="address" value="<?php echo $userrow['Address']?>"><br>
    City: <input type="text" name="city" value="<?php echo $userrow['City']?>"><br>
    State: <input type="text" name="state" maxlength="2" value="<?php echo $userrow['State']?>"><br>
    ZIP: <input type="text" name="zip" value="<?php echo $userrow['ZIP']?>"><br>
    Admin:
    <input type="radio" name="admin" value="1" <?php if($userrow["Admin"] == 1){echo "checked";}?>>Yes<br>
    <input type="radio" name="admin" value="0" <?php if($userrow["Admin"] == 0 || $userID == 0){echo "checked";}?>> No<br>
    <input type="submit" value="Save">
  </form>
  <a href="admin.html">Return to admin page</a>
<html>
