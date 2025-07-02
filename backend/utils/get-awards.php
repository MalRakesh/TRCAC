<?php
include '../db.php';

$sql = "SELECT * FROM awards ORDER BY year DESC";
$result = $conn->query($sql);

$awards = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = $row['image'] ?: 'default.jpg'; // fallback image
    $awards[] = $row;
}

echo json_encode($awards);
?>