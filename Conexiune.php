<?php
$host = 'localhost';
$db = 'licenta';
$user = 'root';
$pass = '';

$conn= new mysqli($host,$user, $pass, $db);
if($conn->connect_error){
    die("Database connection failed" . $conn->connect_error);
}
?> 








