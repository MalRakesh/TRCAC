<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);
    $manualRole = $conn->real_escape_string($_POST['role']);

    // Validate email domain
    if (!str_ends_with($email, '@trcac.org.in')) {
        echo "<script>
            alert('Please use your TRCAC-provided email (e.g., xyz@trcac.org.in) to register.');
            window.location.href='../../frontend/pages/register.html';
        </script>";
        exit;
    }

    // Auto-detect role from email
    $role = $manualRole;

    if (preg_match('/^admin|^system\.admin|^office@/', $email)) {
        $role = 'admin';
    } elseif (preg_match('/^hod\./', $email) || $email === 'hod@trcac.org.in') {
        $role = 'hod';
    } elseif (preg_match('/^[a-zA-Z]+\.[a-zA-Z]+@/', $email)) {
        $role = 'teacher';
    } elseif (preg_match('/^\d{4}-/', $email)) {
        $role = 'student';
    }

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        echo "<script>
            alert('Email already registered. Please login.');
            window.location.href = '../../frontend/pages/login.html';
        </script>";
        exit;
    }

    // Insert user
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Registration successful! You can now login as $role.');
            window.location.href = '../../frontend/pages/login.html';
        </script>";
    } else {
        echo "<script>
            alert('Error: " . $conn->error . "');
            window.location.href = '../../frontend/pages/register.html';
        </script>";
    }
}
?>