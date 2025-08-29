<?php
session_start(); // ✅ Ek baar yahan
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.html");
    exit;
}

$role = $_SESSION['role'];
$allowed = ['admin', 'teacher', 'student', 'hod'];

if (!in_array($role, $allowed)) {
    die("Access Denied");
}

$dashboard_file = "$role-dashboard.php";

if (file_exists($dashboard_file)) {
    include $dashboard_file; 
} else {
    die("Error: Dashboard file for '$role' not found.");
}
?>