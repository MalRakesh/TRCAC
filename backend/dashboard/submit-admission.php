<?php
// Database connection
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $course = $conn->real_escape_string($_POST['course']);
    $message = $conn->real_escape_string($_POST['message']);
    $applied_at = date('Y-m-d H:i:s');

    // Insert into database
    $sql = "INSERT INTO admissions (name, email, phone, course, message, applied_at)
            VALUES ('$name', '$email', '$phone', '$course', '$message', '$applied_at')";

    if ($conn->query($sql) === TRUE) {
        // Success - show alert and redirect
        echo "<script>alert('Admission application submitted successfully!'); window.location.href='../../pages/admission.html';</script>";
    } else {
        // Error in query
        echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
    }
} else {
    // Invalid request
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>