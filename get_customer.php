<?php
  error_reporting(E_ALL);
  header('Content-type: application/json');
  require_once 'dbConn.class.php';

  $conn = dbConn::getInstance();
  $custID = $_GET['custID'];

  if(!isset($custID)){
    echo 'Cannot query without pkey custID';
  }

  $result = $conn->query("SELECT * FROM customers WHERE custID = '$custID'");
  $customer = $result->fetch_assoc();

  if(!$result){
    echo 'No customer exists with specified ID.';
  }

  echo mysqli_error($conn);

  echo json_encode($customer);
?>
