<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // ✅ bcrypt hash
    $role = $conn->real_escape_string($_POST['role']);

    // Validate email domain
    if (!str_ends_with($email, '@trcac.org.in')) {
        echo "<script>
            alert('Please use your TRCAC-provided email');
            window.location.href='../../frontend/pages/register.html';
        </script>";
        exit;
    }

    // Auto-detect role
    if (preg_match('/^\d{4}-/', $email)) $role = 'student';
    elseif (preg_match('/^[a-zA-Z]+\.[a-zA-Z]+@/', $email)) $role = 'teacher';
    elseif (preg_match('/^hod\./', $email)) $role = 'hod';
    elseif (in_array($email, ['admin@trcac.org.in'])) $role = 'admin';

    // Check if email exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo "<script>
            alert('Email already registered');
            window.location.href='../../frontend/pages/login.html';
        </script>";
        exit;
    }

    // Insert user
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration successful!');
            window.location.href='../../frontend/pages/login.html';
        </script>";
    } else {
        echo "<script>
            alert('Error: " . $conn->error . "');
            window.location.href='../../frontend/pages/register.html';
        </script>";
    }
    $stmt->close();
}
?>