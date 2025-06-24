<?php
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $date = $conn->real_escape_string($_POST['date']);
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "INSERT INTO events (title, date, description) VALUES ('$title', '$date', '$description')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Event added successfully'); window.location.href='../../pages/events.html';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
    }
}
?>