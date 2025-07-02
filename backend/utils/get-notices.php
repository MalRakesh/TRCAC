<?php
include '../db.php';

$sql = "SELECT * FROM notices ORDER BY posted_at DESC";
$result = $conn->query($sql);

$notices = [];
while ($row = $result->fetch_assoc()) {
    $notices[] = $row;
}

echo json_encode($notices);
?>