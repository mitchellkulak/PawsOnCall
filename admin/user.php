<?php
session_start();
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include '../authenticate.php';
$session = $_SESSION['session'];
$auth = json_decode(authenticate(urldecode($session)), true);


if ($auth['error'] == 'auth error' || !$auth['admin']) {
    $error = array('error' => 'auth error');
    echo json_encode($error);
    echo "<script>window.location.replace('../login.html');</script>";
}else{
  include '../dbconnect.php';
  if (mysqli_connect_error($db)){
      die("Can't connect");
  }
  else {
    $userrow = array('Name' => "", 'Email' => "", 'Phone' => "", 'Address' => "", 'City' => "", 'State' => "", 'ZIP' => "");
    if($_GET['loadID'] != ""){
      $userID = mysqli_real_escape_string($db,$_GET['loadID']);
      $user = mysqli_query($db,"SELECT * FROM Volunteer WHERE id = $userID");
      $userrow = mysqli_fetch_assoc($user);
    }
    $users = mysqli_query($db,"SELECT ID, Name FROM Volunteer");
  }
}
mysqli_close($db);
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PAWS Motherhood Database</title>
  <link rel="stylesheet" href="../bulma.css">
	<link rel="stylesheet" href="../pawscustom.css">
  <style>
    .asterisk_input:after {
content:" *"; 
color: #e32;
 }
    select {
  width: 300px;
  max-width: 100%;
  /* So it doesn't overflow from it's parent */
}
    option {
  /* wrap text in compatible browsers */
  -moz-white-space: pre-wrap;
  -o-white-space: pre-wrap;
  white-space: pre-wrap;
  /* hide text that can't wrap with an ellipsis */
  overflow: hidden;
  text-overflow: ellipsis;
  /* add border after every option */
  border-bottom: 1px solid #DDD;
}
  </style>
	
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="../scripts.js"></script>
	
	<!-- favicon stuff-->
	<link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
	<link rel="manifest" href="../site.webmanifest">
	<link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	<!-- favicon stuff-->

<script>
document.addEventListener('DOMContentLoaded', function () {

  // Get all "navbar-burger" elements
  var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Check if there are any nav burgers
  if ($navbarBurgers.length > 0) {

    // Add a click event on each of them
    $navbarBurgers.forEach(function ($el) {
      $el.addEventListener('click', function () {

        // Get the target from the "data-target" attribute
        var target = $el.dataset.target;
        var $target = document.getElementById(target);

        // Toggle the class on both the "navbar-burger" and the "navbar-menu"
        $el.classList.toggle('is-active');
        $target.classList.toggle('is-active');

      });
    });
  }

});
</script>
</head>

<!-- Navbar, logo, logout button -->
<nav class="navbar ">
  <div class="navbar-brand">
    <a href="../mother.html">
			<img src="../images/pawslogo.png" alt="PAWS Logo" >
		</a>

    <div class="navbar-burger burger" data-target="navMenubd-example">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>

  <div id="navMenubd-example" class="navbar-menu">
    <div class="navbar-start">
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link  is-active">
          Menu
        </a>
        <div class="navbar-dropdown ">
          <a class="navbar-item " href="../mother.html">
            Mom
          </a>
          <a class="navbar-item " href="../puppies.html">
            Puppies
          </a>
          <a class="navbar-item " href="../misc.html">
            Misc
          </a>
          <a class="navbar-item " id="adminLink" onclick="redirectToAdmin()">
            Admin
          </a>
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="field is-grouped">
          <p class="control">
            <a class="button is-primary" onclick="logout()">
              <span>Logout</span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</nav>
<!-- Navbar, logo, logout button -->

<article class="tile notification is-primary is-vertical admin">

  <!--select user-->
  <form action="user.php">
    <select class="dropbtn admin" name='loadID'>
      <option value="0">New User</option>
      <?php while($subuser = mysqli_fetch_assoc($users)){echo "<option value=".$subuser["ID"];if($subuser["ID"]==$userID){echo " selected";} echo ">".$subuser["Name"]."</option>";}?>
    </select>

    <input class="button is-link admin" type="submit" value="Load">
  </form>

  <form action="userAction.php" method="post">
    
    <!--Name section-->
    <input type="text" name="loadID" style="visibility: hidden; display: none;" value="<?php echo $userID?>">
    <label class="label admin asterisk_input">Name: <i>enter as Lastname, firstname</i></label>
    <input class="input admin" required type="text" name="name" value="<?php echo $userrow['Name']?>"><br>

    <!--email-->
    <label class="label admin asterisk_input">Email: </label>
    <input class="input admin" required type="email" name="email" value="<?php echo $userrow['Email']?>"><br>

    <!--phone-->
    <label class="label admin asterisk_input">Phone: <i>Enter as ###-###-####</i></label>
    <input class="input admin" type="text" required pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="phone" value="<?php echo $userrow['Phone']?>"><br>


    <!--address-->
    <label class="label admin">Address: </label>
    <input class="input admin" type="text" name="address" value="<?php echo $userrow['Address']?>"><br>

    <!--city-->
    <label class="label admin">City:</label>
    <input class="input admin" type="text" name="city" value="<?php echo $userrow['City']?>"><br>

    <!--state-->
    <label class="label admin asterisk_input">State: </label>
    <select class="dropbtn admin" name="state" id="state">
      <option value="AL">Alabama</option>
      <option value="AK">Alaska</option>
      <option value="AZ">Arizona</option>
      <option value="AR">Arkansas</option>
      <option value="CA">California</option>
      <option value="CO">Colorado</option>
      <option value="CT">Connecticut</option>
      <option value="DE">Delaware</option>
      <option value="DC">District Of Columbia</option>
      <option value="FL">Florida</option>
      <option value="GA">Georgia</option>
      <option value="HI">Hawaii</option>
      <option value="ID">Idaho</option>
      <option value="IL">Illinois</option>
      <option value="IN">Indiana</option>
      <option value="IA">Iowa</option>
      <option value="KS">Kansas</option>
      <option value="KY">Kentucky</option>
      <option value="LA">Louisiana</option>
      <option value="ME">Maine</option>
      <option value="MD">Maryland</option>
      <option value="MA">Massachusetts</option>
      <option value="MI">Michigan</option>
      <option value="MN">Minnesota</option>
      <option value="MS">Mississippi</option>
      <option value="MO">Missouri</option>
      <option value="MT">Montana</option>
      <option value="NE">Nebraska</option>
      <option value="NV">Nevada</option>
      <option value="NH">New Hampshire</option>
      <option value="NJ">New Jersey</option>
      <option value="NM">New Mexico</option>
      <option value="NY">New York</option>
      <option value="NC">North Carolina</option>
      <option value="ND">North Dakota</option>
      <option value="OH">Ohio</option>
      <option value="OK">Oklahoma</option>
      <option value="OR">Oregon</option>
      <option value="PA">Pennsylvania</option>
      <option value="RI">Rhode Island</option>
      <option value="SC">South Carolina</option>
      <option value="SD">South Dakota</option>
      <option value="TN">Tennessee</option>
      <option value="TX">Texas</option>
      <option value="UT">Utah</option> 
      <option value="VT">Vermont</option>
      <option value="VA">Virginia</option>
      <option value="WA">Washington</option>
      <option value="WV">West Virginia</option>
      <option value="WI">Wisconsin</option>
      <option value="WY">Wyoming</option>
    </select>	

    <!--zip-->
    <label class="label admin asterisk_input">ZIP: <i>Enter as #####</i> </label>
    <input class="input admin" type="text" required pattern="[0-9]{5}" name="zip" value="<?php echo $userrow['ZIP']?>"><br>
    
    <!--admin buttons-->
    <label class="label admin">Admin:</label>
    <input type="radio" name="admin" value="1" <?php if($userrow["Admin"] == 1){echo "checked";}?>>Yes<br>
    <input type="radio" name="admin" value="0" <?php if($userrow["Admin"] == 0 || $userID == 0){echo "checked";}?>> No<br>
    <input class="button is-link admin" type="submit" name="Save" value="Save">
    <input class="button is-link admin " type="submit" name="Delete" value="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
  </form>

  <a href="index.php">Return to admin page</a>
</article>
</body>
</html>
<script>document.getElementById("state").value = "<?php echo $userrow['State']?>"</script>
