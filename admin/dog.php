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
    $dogrow = array('Name' => "", 'Volunteer' => "", 'Sex' => "", 'Birthdate' => "", 'Deathdate' => "", 'Adoptiondate' => "", 'Breed' => "",'Litter' => "");
    if($_GET['loadID'] != ""){
      $dogID = mysqli_real_escape_string($db,$_GET['loadID']);
      $dog = $db->query("SELECT * FROM Dogs WHERE id = $dogID");
      $dogrow = $dog->fetch_assoc();
    }
    $dogs = $db->query("SELECT ID, Name FROM Dogs");
    $users = $db->query("SELECT ID, Name FROM Volunteer");
    $litters = $db->query("SELECT Dogs.Name, Litter.ID, Litter.StartWhelp FROM Dogs,Litter WHERE Litter.MotherID = Dogs.ID");
  }
}
$db->close();
?>
<html>
  <form action="dog.php">
    <select name='loadID'>
      <option value="0">New Dog</option>
      <?php while($subdog = $dogs->fetch_assoc()){echo "<option value=".$subdog["ID"].">".$subdog["Name"]."</option>";}?>
    </select><br>
    <input type="submit" value="Load">
  </form>
  <form action="dogAction.php" method="post">
    <input type="text" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $dogID?>">
    Name: <input type="text" name="name" value="<?php echo $dogrow['Name']?>"><br>
    Volunteer:
    <select name="volunteerID">
      <option value="0">select</option>
      <?php while($subuser = $users->fetch_assoc()){
        echo "<option value=".$subuser["ID"];
        if($subuser["ID"]==$dogrow["VolunteerID"]){
          echo " selected";}
        echo ">".$subuser["Name"]."</option>";
      }?>
    </select><br>
    Sex: <input type="text" name="sex" value="<?php echo $dogrow['Sex']?>"><br>
    Birthdate: <input type="datetime-local" name="birthdate" value="<?php echo $dogrow['Birthdate']?>"><br>
    Adoption Date: <input type="datetime-local" name="adoptiondate" value="<?php echo $dogrow['Adoptiondate']?>"><br>
    Deathdate: <input type="datetime-local" name="deathdate" value="<?php echo $dogrow['Deathdate']?>"><br>
    Breed: <input type="text" name="breed" value="<?php echo $dogrow['Breed']?>"><br>
    Litter:<select name="litterID">
      <option value=null>None</option>
      <?php while($sublitter = $litters->fetch_assoc()){echo "<option value=".$sublitter["ID"];if($sublitter["ID"]==$dogrow["LitterID"]){echo " selected";} echo ">".$sublitter["Name"]." ".$sublitter["StartWhelp"]."</option>";}?>
    </select><br>
    <input type="submit" value="Save">
  </form>
  <a href="admin.html">Return to admin page</a>
<html>
