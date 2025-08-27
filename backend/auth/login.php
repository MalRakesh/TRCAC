<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['name'] = $row['name'];

        echo "<script>
            alert('Login successful! Redirecting to " . ucfirst($row['role']) . " Dashboard...');
            window.location.href='../../frontend/pages/dashboard/student-dashboard.php';
        </script>";
    } else {
        echo "<script>
            alert('Invalid credentials. Please try again.');
            window.location.href='../../frontend/pages/login.html';
        </script>";
    }
}
?>