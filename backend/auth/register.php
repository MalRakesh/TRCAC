<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = trim($conn->real_escape_string($_POST['name']));
    $email = trim($conn->real_escape_string($_POST['email']));
    $password = $_POST['password'];
    $manualRole = $conn->real_escape_string($_POST['role']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>
            alert('All fields are required.');
            window.location.href='../../frontend/pages/register.html';
        </script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Invalid email format.');
            window.location.href='../../frontend/pages/register.html';
        </script>";
        exit;
    }

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

    if (!in_array($role, ['student', 'teacher', 'hod', 'admin'])) {
        echo "<script>
            alert('Invalid role detected.');
            window.location.href='../../frontend/pages/register.html';
        </script>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if (!$hashed_password) {
        die("Password hashing failed.");
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$check) {
        die("Prepare failed: " . $conn->error);
    }
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
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if (!$stmt->execute()) {
        die("Error inserting user: " . $stmt->error);
    }

    $user_id = $stmt->insert_id;
    $stmt->close();

    // ✅ Insert into respective profile table (only name & email)
    try {
        if ($role === 'student') {
            $profile_sql = "INSERT IGNORE INTO student_profiles (user_id, email, name) VALUES (?, ?, ?)";
        } elseif ($role === 'teacher') {
            $profile_sql = "INSERT IGNORE INTO teacher_profiles (user_id, email, name) VALUES (?, ?, ?)";
        } elseif ($role === 'hod') {
            $profile_sql = "INSERT IGNORE INTO hod_profiles (user_id, email, name) VALUES (?, ?, ?)";
        } elseif ($role === 'admin') {
            $profile_sql = "INSERT IGNORE INTO admin_profiles (user_id, email, name) VALUES (?, ?, ?)";
        } else {
            throw new Exception("Invalid role: $role");
        }

        $profile_stmt = $conn->prepare($profile_sql);
        if (!$profile_stmt) {
            error_log("Profile prepare failed: " . $conn->error);
            throw new Exception($conn->error);
        }

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