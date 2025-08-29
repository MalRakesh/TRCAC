<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Raw password

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];

            echo "<script>
                alert('Login successful! Welcome, " . htmlspecialchars($row['name']) . "');
                window.location.href = '../../frontend/pages/dashboard/dashboard.php';
            </script>";
        } else {
            echo "<script>
                alert('Invalid credentials');
                window.location.href = '../../frontend/pages/login.html';
            </script>";
        }
    } else {
        echo "<script>
            alert('Invalid credentials');
            window.location.href = '../../frontend/pages/login.html';
        </script>";
    }
    $stmt->close();
}
?>