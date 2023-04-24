<?php

  // Including Dotenv to access env variables.
  require './vendor/autoload.php';
  use Dotenv\Dotenv;
  $dotenv = Dotenv::createImmutable("./");
  $dotenv->load();

  /**
   * Database class hold database data.
   * This class have methods to insert and update data in databse.
   */
  class Database {
    /**
     *  @var string $dbName
     *    Contains database name.
     */
    private string $dbName;

    /**
     *  @var string $dbUsername
     *    Contains database username.
     */
    private string $dbUsername;

    /**
     *  @var string $dbPassword
     *    Stores database user password.
     */
    private string $dbPassword;

    /**
     *  @var object $connectionData
     *    Holds database connection object.
     */
    public object $connectionData;

    /**
     * Constructor to initialize UserDb class with databasename, username and 
     * password.
     */
    public function __construct() {
      $this->dbName = $_ENV['DBNAME'];
      $this->dbUsername = $_ENV['USERNAME'];
      $this->dbPassword = $_ENV['PASSWORD'];
      $this->databaseConnet();
    }

    /**
     * Function to connect database.
     */
    public function databaseConnet() {
      try {
        $this->connectionData = new PDO("mysql:host=localhost;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
        $this->connectionData->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch (PDOException $e) {
        echo "Error while connecting database: " . $e->getMessage();
      }
    }

    /**
     * Function to close database connection.
     */
    public function disconnectDb() {
      $this->connectionData = NULL;
    }

    /**
     * Function to check whether user login email and password exist in the database
     * or not.
     * 
     *  @param string $username
     *    Contains user email used for login.
     * 
     *  @param string $password
     *    Contains user login password
     * 
     *  @return bool
     *    Return TRUE if data exists in database, if not then return FALSE.
     */
    public function checkLogin(string $username, string $password) {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM admin WHERE user_email = :username AND user_password = :password");
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $password);
        $query->execute();
        // Check how many rows are returned
        if ($query->rowCount() == 1) {
          return TRUE;
        }
        return FALSE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get user role information.
     * 
     *  @param string $email
     *    Holds user login email.
     * 
     *  @return mixed
     */
    public function getUserRoleInfo(string $email) {
      try {
        $query = $this->connectionData->prepare("SELECT role FROM admin WHERE user_email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetchColumn();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to check whether username or email exists in the database or
     * not.
     * 
     *  @param string $email
     *    Contains user email used for login.
     * 
     *  @return bool
     *    Return TRUE if data exists in database, if not then return FALSE.
     */
    public function checkUserNameExists(string $email) {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM admin WHERE user_email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        // Check row count.
        if ($query->rowCount() == 1) {
          return TRUE;
        }
        return FALSE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to register new user data into table.
     *  
     *  @param array $user_data
     *    Contains user all data.
     * 
     *  @return bool
     *    It will return TRUE if user data has been insert, FALSE if not.
     */
    public function registerUser(array $user_data) {
      try {
        $query = $this->connectionData->prepare("INSERT INTO admin (user_email, user_password)
         VALUES (:email, :password)");
        $query->bindParam(':email', $user_data["email"]);
        $query->bindParam(':password', $user_data["password"]);
        $query->execute();
        $query1 = $this->connectionData->prepare("INSERT INTO user_info(name, phone)
        VALUES (:name, :phone)");
        $query1->bindParam(':name', $user_data["name"]);
        $query1->bindParam(':phone', $user_data["phone"]);
        $query1->execute();
        return ($query && $query1);
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to check whether phone number exists or not.
     * 
     *  @param string $phone
     *    Holds phone number to check for.
     * 
     *  @return bool
     *    Return true if phone number exists, false if not.
     */
    public function checkUserContactExists(string $phone) {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM user_info WHERE user_phone = :phone");
        $query->bindParam(':phone', $phone);
        $query->execute();
        // Check row count.
        if ($query->rowCount() == 1) {
          return TRUE;
        }
        return FALSE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get username from database.
     * 
     *  @param string $email
     *    Contains email id.
     * 
     *  @return mixed
     *    Return result in array if found any record else false.
     */
    public function getUsername(string $email) {
      try {
        $query = $this->connectionData->prepare("SELECT name FROM user_info
          INNER JOIN admin
          ON user_info.user_id = admin.admin_id
          WHERE user_email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetchColumn();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get user id from database.
     * 
     *  @param string $email
     *    Contains email id.
     * 
     *  @return mixed
     *    Return a column, else false if no records found.
     */
    public function getUserId(string $email) {
      try {
        $query = $this->connectionData->prepare("SELECT admin_id FROM admin WHERE user_email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetchColumn();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to add player information into table.
     *  
     *  @param array $player_data
     *    Contains player all data.
     * 
     *  @return bool
     *    It will return TRUE if user data has been insert, FALSE if not.
     */
    public function addPlayer(array $player_data) {
      try {
        $query = $this->connectionData->prepare("INSERT INTO employee (employee_id, employee_name, type, point)
         VALUES (:id, :name, :type, :point)");
        $query->bindParam(':id', $player_data["player-id"]);
        $query->bindParam(':name', $player_data["name"]);
        $query->bindParam(':type', $player_data["type"]);
        $query->bindParam(':point', $player_data["point"]);
        $query->execute();
        return $query;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to check whether player data already exists or not in database.
     * 
     *  @param string $playerName
     *    Holds player name to be check.
     * 
     *  @return bool
     *    True if player not exists, false if exists or if any error occur.
     */
    public function isPlayerExists($playerName) {
      try {
        $query = $this->connectionData->prepare("SELECT employee_name FROM employee WHERE employee_name = :playerName");
        $query->bindParam(':playerName', $playerName);
        $query->execute();
        // Check row count.
        if ($query->rowCount() == 1) {
          return FALSE;
        }
        return TRUE;
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to fetch all player data from database.
     * 
     *  @return mixed
     *    Return array if data found, if not then return false.
     */
    public function fetchPlayerData() {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM employee");
        $query->execute();
        return ($this->isEmpty($query->fetchAll()));
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to get player all information using player id.
     * 
     *  @param int $playerId
     *    Holds player id.
     * 
     *  @return mixed
     *    Return array if records found, else false if any error occur or record
     *    empty.
     */
    public function getPlayerDataById(int $playerId) {
      try {
        $query = $this->connectionData->prepare("SELECT * FROM employee WHERE employee_id = :playerId");
        $query->bindParam(':playerId', $playerId);
        $query->execute();
        return ($this->isEmpty($query->fetch()));
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to insert user selected team players into database.
     * 
     *  @param int $userId
     *    Holds user id.
     *  
     *  @param int $playerId
     *    Holds player id.
     * 
     *  @return bool
     *    Return true if data inserted, false if not.
     */
    public function addUserTeamPlayers(int $userId, int $playerId) {
      try {
        $query = $this->connectionData->prepare("INSERT INTO user_team (user_id, player_id)
        VALUES (:userId, :playerId)");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':playerId', $playerId);
        return $query->execute();
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to add selected team player into user team.
     * 
     *  @param array $playerData
     *    Holds player information.
     * 
     *  @param int $userId
     *    Holds user id.
     * 
     *  @return bool
     */
    public function addTeam(array $playerData, int $userId) {
      if(!empty($playerData)) {
        // Iterating over all chosen player to get player id and insert into
        // user team table.
        foreach ($playerData as $value) {
          $this->addUserTeamPlayers($userId, $value);
        }
        return TRUE;
      }
      return FALSE;
    }

    /**
     * Function to show user team members.
     * 
     *  @param int $userId
     *    Holds user id.
     * 
     *  @return mixed
     */
    public function showUserTeam(int $userId) {
      try {
        $query = $this->connectionData->prepare("SELECT emp.employee_name, emp.type
          FROM employee emp
          INNER JOIN user_team user
          ON user.player_id = emp.employee_id
          WHERE user_id = :userId");
        $query->bindParam(':userId', $userId);
        $query->execute();
        return $this->isEmpty($query->fetchAll());
      }
      catch (PDOException $e) {
        echo $e;
        return FALSE;
      }
    }

    /**
     * Function to check whether passed data is empty or not. If empty then
     * return false, if not then return data itself.
     * 
     *  @param mixed $data
     *    Holds values need to be checked.
     * 
     *  @return mixed
     *    Return false if empty, if not then return data parameter itself.
     */
    public function isEmpty(mixed $data) {
      if(empty($data)) {
        return FALSE;
      }
      return $data;
    }

  }
?>
