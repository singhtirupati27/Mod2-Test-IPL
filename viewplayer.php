<?php
  require './header.php';
  $players = $database->fetchPlayerData();
  // Check whether save button has been clicked or not.
  if (isset($_POST["save-player"])) {
    // Check if player has not chosen more than 11 players.
    if (count($_POST["player"]) < 11) {
      $totalPoints = 0;
      // Iterating over all chosen player to get its point values.
      foreach ($_POST["player"] as $value) {
        $playerPoint = $database->getPlayerDataById($value);
        $totalPoints += $playerPoint["point"];
      }
      // Check if total points is less than 100 then, add selected team members
      // to user database.
      if ($totalPoints <= 100) {
        if($database->addTeam($_POST["player"], $_SESSION["userId"])) {
          $msg = "Your team has been added successfully.";
        }
        else {
          $msg = "Sorry, your team could not be added.";
        }
      }
      else {
        $msg = "You are allowed to use only 100 points.";
      }
    }
    else {
      $msg = "You can choose maximum of 11 players only.";
    }
  }
?>
<div class="team-container">
  <div class="page-wrapper team-wrap">
    <div class="team-content-wrap">
      <div class="error-msg">
        <span><?php if(isset($msg)) {echo $msg;} ?></span>
      </div>
      <!-- Form container -->
      <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
        <?php
          // Check whether players array is empty or not.
          if (!empty($players)) {
            foreach ($players as $value) {
        ?>
        <div class="form-input">
          <input type="checkbox" name="player[]" id="<?php echo $value["employee_id"] ?>" value="<?php echo $value["employee_id"] ?>">
          <label for="<?php echo $value["employee_id"] ?>"><?php echo $value["employee_name"] ?></label>
          <span><h3>Type: <?php echo $value["type"] ?></h3></span>
          <span><h3>Point: <?php echo $value["point"] ?></h3></span>
        </div>
        <?php
          }
        ?>
        <div class="form-input">
          <input type="submit" name="save-player" id="submit-btn" value="Save">
        </div>
      </form>
      </div>
      <?php
        }
        else {
      ?>
      <div class="players-box">
        <h2>No players found</h2>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<?php
  require './footer.php';
?>