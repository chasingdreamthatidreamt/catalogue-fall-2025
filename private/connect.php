<?php
$host ='mysql';
$user = 'student';           
$pass = 'student';        
$db   = 'dmit2025'; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
