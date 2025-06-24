<?php
$host = 'localhost';
$user = 'root';
$pass = 'RakeshMal@12345';
$db = 'trcac_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>