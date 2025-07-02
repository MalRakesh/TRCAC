<?php
include '../db.php';

$type = isset($_GET['type']) ? $_GET['type'] : 'all';

switch ($type) {
    case 'student':
        $sql = "SELECT * FROM awards WHERE category = 'Student'";
        break;
    case 'faculty':
        $sql = "SELECT * FROM awards WHERE category = 'Faculty'";
        break;
    case 'achievement':
        $sql = "SELECT * FROM awards WHERE category = 'Achievement'";
        break;
    default:
        $sql = "SELECT * FROM awards";
}

$result = $conn->query($sql);

$awards = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = $row['image'] ?: 'default.jpg';
    $awards[] = $row;
}

echo json_encode($awards);
?>