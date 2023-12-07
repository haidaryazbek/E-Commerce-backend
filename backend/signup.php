<?php
header('Access-Controll-Allow-Origin:*');
include("connection.php");

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$user_type = $_POST['$user_type'];


$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = $mysqli->prepare('insert into users(username,email,password,user_type) 
values(?,?,?,?)');
$query->bind_param('ssss', $username, $email, $hashed_password,$user_type);
$query->execute();

$response = [];
$response["status"] = "true";

echo json_encode($response);
