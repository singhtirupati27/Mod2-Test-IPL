<?php
  require './header.php';
  // Check if form has been submitted or not. If so then validate user input
  // data.
  if (isset($_POST["add-player"])) {
    $validation->validateName($_POST["name"]);
    $validation->validatePoint($_POST["point"]);
    $validation->validateId($_POST["player-id"]);
    $validation->validateName($_POST["type"]);
    // Check if all input fields are correct or not.
    if ($validation->dataValid) {
      if ($database->isPlayerExists($_POST["name"])) {
        // Check if player data has been inserted into database or not.
        if ($database->addPlayer($_POST)) {
          $msg = "Player data added successfully.";
        }
      }
      else {
        $msg = "Player already exists.";
      }
    }
  }
?>

<!-- Register container -->
<div class="register-container">
  <div class="page-wrapper register-content-wrap">
    <div class="register-content">
      <div class="right-container">
        <div class="error-msg">
          <span><?php if (isset($msg)) {echo $msg;} ?></span>
        </div>
        <h2>ADD PLAYERS</h2>
        
        <!-- Form container -->
        <div class="form-container">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-input">
              <label for="player-id">Employee Id</label>
              <input type="text" name="player-id" id="player-id" placeholder="" value="<?php if (isset($_POST["player-id"])) { echo $_POST["player-id"]; } ?>">
              <span class="error" id="checkId"><?php if (isset($validation->errorMsg["idErr"])) { echo $validation->errorMsg["idErr"]; } ?></span>
            </div>

            <div class="form-input">
              <label for="fname">Employee Name</label>
              <input type="text" name="name" id="name" placeholder="Employee Name" onblur="validateName()" value="<?php if (isset($_POST["name"])) { echo $_POST["name"]; } ?>">
              <span class="error" id="checkName"><?php if (isset($validation->errorMsg["nameErr"])) { echo $validation->errorMsg["nameErr"]; } ?></span>
            </div>

            <div class="form-input">
              <label for="email">Type</label>
              <input type="text" name="type" id="type" placeholder="batsman/bowler/all rounder" onblur="validateName()" value="<?php if (isset($_POST["type"])) { echo $_POST["type"]; } ?>">
              <span class="error" id="checkType"><?php if (isset($validation->errorMsg["nameErr"])) { echo $validation->errorMsg["nameErr"]; } ?></span>
            </div>

            <div class="form-input">
              <label for="point">Points</label>
              <input type="text" name="point" id="point" placeholder="Points" onblur="validatePassword()">
              <span class="error" id="checkPoint"><?php if (isset($validation->errorMsg["pointErr"])) { echo $validation->errorMsg["pointErr"]; } ?></span>
            </div>

            <div class="form-input">
              <input type="submit" name="add-player" id="submit-btn" value="SAVE">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<?php
  require './footer.php';
?>
