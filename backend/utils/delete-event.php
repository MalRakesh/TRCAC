<?php
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM events WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../pages/dashboard/admin-dashboard.html");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>