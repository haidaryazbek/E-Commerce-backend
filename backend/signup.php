<?php
header('Access-Controll-Allow-Origin:*');
include("connection.php");

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];


$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = $mysqli->prepare('insert into users(username,email,password) 
values(?,?,?)');
$query->bind_param('sss', $username, $email, $hashed_password);
$query->execute();

$response = [];
$response["status"] = "true";

echo json_encode($response);
