<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);
    $role = $conn->real_escape_string($_POST['role']);

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful'); window.location.href='../pages/login.html';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='../pages/register.html';</script>";
    }
}
?>