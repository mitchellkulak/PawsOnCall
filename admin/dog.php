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
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PAWS Motherhood Database</title>
  <link rel="stylesheet" href="bulma.css">
	<link rel="stylesheet" href="pawscustom.css">
	
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="scripts.js"></script>
	
	<!-- favicon stuff-->
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	<!-- favicon stuff-->
	
</head>

<body>

  <!-- Navbar, logo, logout button -->
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div id="navbarDesktop " class="navbar-brand">
      <img src="images/pawslogo.png" alt="PAWS Logo" >
      <a class="navbar-item" href="mother.html">Mom</a>
      <a class="navbar-item" href="puppies.html">Puppies</a>
      <a class="navbar-item" href="misc.html">Misc</a>
    </div>
    <div class="buttons">
      <a class="button is-primary logout" onclick="logout()">
      Log out
      </a>
    </div>
  </nav>
  <!-- Navbar, logo, logout button -->


    <div class="tile">
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
    </div><!--close tile div-->
</body>
<html>
