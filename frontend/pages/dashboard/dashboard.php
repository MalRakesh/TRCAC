<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.html");
    exit;
}

$role = $_SESSION['role'];
$allowed = ['admin', 'teacher', 'student', 'hod'];

if (!in_array($role, $allowed)) {
    die("Access Denied");
}

// Define the file path
$dashboard_file = "$role-dashboard.html";

// Check if file exists
if (file_exists($dashboard_file)) {
    include $dashboard_file;
} else {
    die("Error: Dashboard file '$dashboard_file' not found. Please contact admin.");
}
?>