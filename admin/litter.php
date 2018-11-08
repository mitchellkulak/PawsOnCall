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
    $dogrow = array('VolunteerID' => "", 'MotherID' => "", 'FatherID' => "", 'StartWhelp' => "", 'StartWean' => "", 'EndWean' => "");
    if($_GET['loadID'] != ""){
      $litterID = mysqli_real_escape_string($db,$_GET['loadID']);
      $litter = $db->query("SELECT * FROM Litter WHERE id = $litterID");
      $litterrow = $litter->fetch_assoc();
    }
    $litters = $db->query("SELECT Dogs.Name, Litter.ID, Litter.StartWhelp FROM Litter, Dogs WHERE Litter.MotherID = Dogs.ID ORDER BY Dogs.Name");
    $motherdogs = $db->query("SELECT ID, Name FROM Dogs WHERE Sex = 'F'");
    $fatherdogs = $db->query("SELECT ID, Name FROM Dogs WHERE Sex = 'M'");
    $users = $db->query("SELECT ID, Name FROM Volunteer");
  }
}
$db->close();
?>
<html>
  <form action="litter.php">
    <select name='loadID'>
      <option value="0">New Litter</option>
      <?php while($sublitter = $litters->fetch_assoc()){echo "<option value=".$sublitter["ID"];if($sublitter["ID"]==$litterID){echo " selected";} echo ">".$sublitter["StartWhelp"]." ".$sublitter["Name"]."</option>";}?>
    </select><br>
    <input type="submit" value="Load">
  </form>
  <form action="litterAction.php" method="post">
    <input type="text" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $litterID?>">
    Volunteer:
    <select name="volunteerID">
      <option value="0">select</option>
      <?php while($subuser = $users->fetch_assoc()){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $volunteerID){echo " selected";}echo ">".$subuser["Name"]."</option>";}?>
    </select><br>
    Mother:
    <select name="motherID">
      <option value="0">select</option>
      <?php while($subuser = $motherdogs->fetch_assoc()){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $motherID){echo " selected";}echo ">".$subuser["Name"]."</option>";}?>
    </select><br>
    Father:
    <select name="fatherID">
      <option value="0">select</option>
      <?php while($subuser = $fatherdogs->fetch_assoc()){echo "<option value=".$subuser["ID"];if($subuser["ID"] == $fatherID){echo " selected";}echo ">".$subuser["Name"]."</option>";}?>
    </select><br>
    Start Whelp: <input type="datetime-local" name="startWhelp" value="<?php echo $litterrow['StartWhelp']?>"><br>
    End Whelp: <input type="datetime-local" name="endWhelp" value="<?php echo $litterrow['EndWhelp']?>"><br>
    Start Wean: <input type="datetime-local" name="startWean" value="<?php echo $litterrow['StartWean']?>"><br>
    End Wean: <input type="datetime-local" name="endWean" value="<?php echo $litterrow['EndWean']?>"><br>
    <input type="submit" value="Save">
  </form>
  <a href="admin.html">Return to admin page</a>
<html>
