<?php
  error_reporting(E_ALL);
  require_once 'dbConn.class.php';

  function check_pass($user, $pass){
    $conn = dbConn::getInstance();

    if(!$conn){
      return 'cannot establish db';
    }

    $result = $conn->query("SELECT * FROM users WHERE user_name = '$user'");

    if(!$result){
      return false;
    }

    $hash = $result->fetch_assoc()['pass_hash'];
    return password_verify($pass, $hash) ? true : false;
  }

?>
