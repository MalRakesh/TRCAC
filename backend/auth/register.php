<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $manualRole = $conn->real_escape_string($_POST['role']);

    // Validate email domain
    if (!str_ends_with($email, '@trcac.org.in')) {
        echo "<script>
            alert('Please use your TRCAC-provided email (e.g., xyz@trcac.org.in)');
            window.location.href='../../frontend/pages/register.html';
        </script>";
        exit;
    }

    // Auto-detect role from email
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

    // Check if email already exists
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

    // ✅ Insert into users table
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if (!$stmt->execute()) {
        die("Error inserting user: " . $stmt->error);
    }

    $user_id = $stmt->insert_id;
    $stmt->close();

    // ✅ Auto-insert into respective profile table (only name & email)
    try {
        if ($role === 'student') {
            $profile_sql = "INSERT INTO student_profiles (user_id, email, name) VALUES (?, ?, ?)";
        } elseif ($role === 'teacher') {
            $profile_sql = "INSERT INTO teacher_profiles (user_id, email, name) VALUES (?, ?, ?)";
        } elseif ($role === 'hod') {
            $profile_sql = "INSERT INTO hod_profiles (user_id, email, name) VALUES (?, ?, ?)";
        } elseif ($role === 'admin') {
            $profile_sql = "INSERT INTO admin_profiles (user_id, email, name) VALUES (?, ?, ?)";
        } else {
            throw new Exception("Invalid role: $role");
        }

        $profile_stmt = $conn->prepare($profile_sql);
        $profile_stmt->bind_param("iss", $user_id, $email, $name);
        $profile_stmt->execute();
        $profile_stmt->close();

    } catch (Exception $e) {
        error_log("Profile creation failed for $email: " . $e->getMessage());
        // Continue — user can edit later
    }

    echo "<script>
        alert('Registration successful! You can now login as $role.');
        window.location.href = '../../frontend/pages/login.html';
    </script>";
}
?>