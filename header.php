<?php
  session_start();
  require './classes/Database.php';
  require './classes/Validations.php';
  // Check if user is already logged in or not.
  // If logged in then redirect to welcome page, else load home page.
  if (!isset($_SESSION["loggedIn"])) {
    header('Location: ./index.php');
  }
  $database = new Database();
  $validation = new Validations();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INNORAFT IPL</title>
    <link rel="icon" href="./assets/img/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="./assets/css/style.css">
  </head>
  <body>

    <!-- Main container -->
    <div class="main-container">

      <!-- Navbar container -->
      <div class="navbar">
        <div class="page-wrapper">
          <div class="nav">
            <div class="nav-logo">
              <a href="./welcome.php"><img src="./assets/img/logo.svg" alt="">INNORAFT IPL</a>
            </div>
            <ul class="nav-ul">
              <li><a href="./welcome.php"><?php if (isset($_SESSION["username"])) { echo $_SESSION["username"];}?></a></li>
              <li><a href="./welcome.php">Home</a></li>
              <?php if ($_SESSION["userRole"] == "admin") {?>
              <li><a href="./addplayer.php">Add Player</a></li>
              <?php 
                }
                else { 
              ?>
              <li><a href="./viewplayer.php">View Player</a></li>
              <li><a href="./viewteam.php">Your Team</a></li>
              <?php } ?>
              <li><a href="./signout.php">Sign Out</a></li>
            </ul>
          </div>
        </div>
      </div>
