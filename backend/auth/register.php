<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $manualRole = $conn->real_escape_string($_POST['role']);
    $password = $_POST['password'];

    // Validate email domain
    if (!str_ends_with($email, '@trcac.org.in')) {
        echo "<script>
            alert('Please use your TRCAC-provided email (e.g., 2022-bsit-112@trcac.org.in)');
            window.location.href='../../frontend/pages/register.html';
        </script>";
        exit;
    }

    // Password strength
    if (strlen($password) < 6) {
        echo "<script>
            alert('Password must be at least 6 characters long');
            window.location.href='../../frontend/pages/register.html';
        </script>";
        exit;
    }
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Auto-detect role
    $role = $manualRole;
    if (preg_match('/^\d{4}-/', $email)) {
        $role = 'student';
    } elseif (preg_match('/^[a-zA-Z]+\.[a-zA-Z]+@/', $email)) {
        $role = 'teacher';
    } elseif (preg_match('/^hod\./', $email) || $email === 'hod@trcac.org.in') {
        $role = 'hod';
    } elseif (in_array($email, ['admin@trcac.org.in'])) {
        $role = 'admin';
    }

    // Check if email exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo "<script>
            alert('Email already registered. Please login.');
            window.location.href='../../frontend/pages/login.html';
        </script>";
        exit;
    }

    // Insert user
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        // ✅ Auto-create student profile
        if ($role == 'student') {
            $insert_profile = $conn->prepare("INSERT INTO student_profiles (email, name, profile_image) VALUES (?, ?, 'default.png')");
            $insert_profile->bind_param("ss", $email, $name);
            $insert_profile->execute();
            $insert_profile->close();
        }

        echo "<script>
            alert('Registration successful! You can now login as $role.');
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