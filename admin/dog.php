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
  <link rel="stylesheet" href="../bulma.css">
	<link rel="stylesheet" href="../pawscustom.css">
	
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="scripts.js"></script>
	
	<!-- favicon stuff-->
	<link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
	<link rel="manifest" href="../site.webmanifest">
	<link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	<!-- favicon stuff-->
</head>

<body>

  <!-- Navbar, logo, logout button -->
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div id="navbarDesktop " class="navbar-brand">
      <a href="searchresult.html">
        <img src="../images/pawslogo.png" alt="PAWS Logo" >
      </a>
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

	<!-- central tile-->
	<article class="tile notification is-primary is-vertical admin">
    <form action="dog.php">
      <select name='loadID'class="dropbtn" >
        <option value="0">New Dog</option>
        <?php while($subdog = $dogs->fetch_assoc()){echo "<option value=".$subdog["ID"].">".$subdog["Name"]."</option>";}?>
      </select>
      <input type="submit" class="button is-link admin " value="Load">
    </form>


    <form action="dogAction.php" method="post">
      <!--pick dog from listed dogs-->
      <input type="text" class="dropdown-content" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $dogID?>">
      
      <!--enter dog name-->
      <label class="label admin">Name: </label>
      <input type="text" class="input admin" name="name" value="<?php echo $dogrow['Name']?>"><br>

      <!--volunteer dropdown-->
      <label class="label admin">Volunteer:</label>
      <select class="dropbtn" name="volunteerID">
        <option value="0">select</option>
        <?php while($subuser = $users->fetch_assoc()){
          echo "<option value=".$subuser["ID"];
          if($subuser["ID"]==$dogrow["VolunteerID"]){
            echo " selected";}
          echo ">".$subuser["Name"]."</option>";
        }?>
      </select><br>
      
      <!--sex input-->
      <label class="label admin">Sex:</label> 
      <input type="text" class="input admin" name="sex" value="<?php echo $dogrow['Sex']?>"><br>
      
      <!--birthday-->
      <label class="label admin"> Birthdate:</label>
      <input type="datetime-local" class="input admin" name="birthdate" value="<?php echo $dogrow['Birthdate']?>"><br>
      
      <!--adoption date-->
      <label class="label admin">Adoption Date:</label>
      <input type="datetime-local" class="input admin" name="adoptiondate" value="<?php echo $dogrow['Adoptiondate']?>"><br>
      
      <!--death date-->
      <label class="label admin">Deathdate:</label>
      <input type="datetime-local" class="input admin" name="deathdate" value="<?php echo $dogrow['Deathdate']?>"><br>
      
      <!--breed-->
      <label class="label admin">Breed:</label>
      <input type="text" class="input admin" name="breed" value="<?php echo $dogrow['Breed']?>"><br>
      
      <!--litter-->
      <label class="label admin">Litter:</label>
      <select class="dropbtn" name="litterID">
        <option value=null>None</option>
        <?php while($sublitter = $litters->fetch_assoc()){echo "<option value=".$sublitter["ID"];if($sublitter["ID"]==$dogrow["LitterID"]){echo " selected";} echo ">".$sublitter["Name"]." ".$sublitter["StartWhelp"]."</option>";}?>
      </select>
      <input class="button is-link admin " type="submit" value="Save">
    </form>

    <a href="admin.html">Return to admin page</a>

  </article>
</body>
<html>
