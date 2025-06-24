<?php
include '../db.php';

$sql = "SELECT * FROM enquiries ORDER BY created_at DESC";
$result = $conn->query($sql);

$enquiries = [];
while ($row = $result->fetch_assoc()) {
    $enquiries[] = $row;
}
?>