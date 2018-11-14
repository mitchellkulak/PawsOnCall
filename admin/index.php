<?php
	session_start();
	$_SESSION["session"] = $_GET["session"];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PAWS motherhood Database</title>
	
    <link rel="stylesheet" href="../bulma.css">
	<link rel="stylesheet" href="../pawscustom.css">
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
	
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<body  onload="adminShowHide()"></body>
<!-- Navbar, logo, logout button -->
<nav class="navbar" role="navigation" aria-label="main navigation">
	<div id="navbarDesktop " class="navbar-brand">
		<a href="searchresult.html">
			<img src="../images/pawslogo.png" alt="PAWS Logo" >
		</a>
		<a class="navbar-item" href="../mother.html">Mom</a>
		<a class="navbar-item" href="../puppies.html">Puppies</a>
		<a class="navbar-item" href="../misc.html">Misc</a>
		<a class="navbar-item" id="adminLink" onclick="redirectToAdmin()">Admin</a>
	</div>
	<div class="buttons">
		<a class="button is-primary logout" onclick="logout()">
		Log out
		</a>
	</div>
</nav>
<!-- Navbar, logo, logout button -->

<!-- Links to admin pages -->
<article class="tile notification is-primary is-vertical admin">
  <a class="button is-link admin" href="user.php">User</a><br>
  <a class="button is-link admin" href="dog.php">Dog</a><br>
  <a class="button is-link admin"href="litter.php">Litter</a><br>
</article>
</body>
</html>
