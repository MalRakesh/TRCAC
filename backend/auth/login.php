<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']); // For demo only. Use password_hash in production.

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        header("Location: ../pages/dashboard/" . $row['role'] . "-dashboard.html");
    } else {
        echo "<script>alert('Invalid credentials'); window.location.href='../pages/login.html';</script>";
    }
}
?>