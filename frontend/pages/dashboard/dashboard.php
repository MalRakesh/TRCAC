<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.html");
    exit;
}

$role = $_SESSION['role'];
$allowed = ['admin', 'teacher', 'student', 'hod'];

if (!in_array($role, $allowed)) {
    die("Access Denied");
}

// Role-based HTML include
include "$role-dashboard.html";
?>