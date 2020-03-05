<?php

class AuthenticateDevice {

  private $dbHost = '';

  private $dbUser = '';

  private $dbPass = '';

  private $uuid = '';

  public function __construct() {
    // Set variables.
    $this->dbHost = getenv('DB_HOST');
    $this->dbUser = getenv('DB_USER');
    $this->dbPass = getenv('DB_PASS');
    $this->dbName = getenv('DB_NAME');
    $this->uuid = $_GET['uuid'];
  }

  private function newConnection() {
    // Connect to the database.
    $conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
    if ($conn->connect_error) {
      throw new Exception($conn->connect_error);
    }
    return $conn;
  }

  public function authenticateDevice() {
    // Try a database connection.
    try {
      $conn = $this->newConnection();
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
    // Build a response.
    $response = new stdClass();
    // Add random fields.
    $response->username = '_' . rand(1, 100000) . '_' . rand(1, 100000);
    $response->setupCode = rand(1, 100000);
    $id = rand();
    $sub = rand();
    // Build query to get uid.
    $query = "SELECT * FROM users_uuid WHERE uuid = UUID_TO_BIN('$this->uuid')";
    // Run query.
    if ($result = $conn->query($query)) {
      while ($row = $result->fetch_row()) {
        $uid = $row[0];
      }
    } else {
      echo "Error: " . $conn->error;
    }

    // Build query to add pending auth.
    $query = "INSERT INTO app_devices_cognito (id, uid, sub, username, authed) VALUES ('$id', '$uid', '$sub', '$response->username', 1)";
    // Run query.
    if ($result != $conn->query($query)) {
      echo "Error: " . $conn->error;
    }

    // Respond.
    return($response);
  }

  public function deauthDevice() {
    // Try a database connection.
    try {
      $conn = $this->newConnection();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    $cognito_uuid = $_GET['cognito_uuid'];
    $query = "DELETE FROM app_devices_cognito WHERE sub = '$cognito_uuid'";
    if ($conn->query($query)) {
      return TRUE;
    } else {
      echo "Error: " . $conn->error;
    }
  }

  public function deauthAllDevices() {
    // Try a database connection.
    try {
      $conn = $this->newConnection();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    // Build query to get uid.
    $query = "SELECT * FROM users_uuid WHERE uuid = UUID_TO_BIN('$this->uuid')";
    // Run query.
    if ($result = $conn->query($query)) {
      while ($row = $result->fetch_row()) {
        $uid = $row[0];
      }
    } else {
      echo "Error: " . $conn->error;
    }
    // Build query to delete all of user's devices.
    $query = "DELETE FROM app_devices_cognito WHERE uid = '$uid'";
    // Run query.
    if ($conn->query($query)) {
      return TRUE;
    } else {
      echo "Error: " . $conn->error;
    }

  }

}

?>
