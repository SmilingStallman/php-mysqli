<?php

error_reporting(E_ALL);
header('Content-type: application/json');
require_once 'dbConn.class.php';

$conn = dbConn::getInstance();
$name = $_POST['username'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

$email = isset($_POST['email']) ? $_POST['email'] : NULL;

$query = "INSERT INTO users (user_name, pass_hash, email) VALUES ('$name', '$pass', '$email')";

$result = $conn->query($query);

echo !$result ? "fail" : "success";

// echo json_encode($result_data);
