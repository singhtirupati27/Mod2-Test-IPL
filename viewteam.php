<?php
  require './header.php';
  $team = $database->showUserTeam($_SESSION["userId"]);
?>

<div class="team-container">
  <div class="page-wrapper team-wrap">
    <div class="team-content-wrap">
      <?php
        // Check whether players array is empty or not.
        if (!empty($team)) {
          // Iterating array to get team players data.
          foreach ($team as $value) {
      ?>
      <div class="team-info">
        <h2>Name: <?php echo $value["employee_name"]; ?></h2>
        <h3>Type: <?php echo $value["type"]; ?></h3>
      </div>
      <?php
          }
        }
        else {
      ?>
      <div class="players-box">
        <h2>No players found in your team.</h2>
      </div>
      <?php } ?>
    </div>
  </div>
</div>


<?php
  require './footer.php';
?>