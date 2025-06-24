<?php
include '../db.php';

$sql = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}
?>