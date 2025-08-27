<?php
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = date('Y-m-d');
    $statuses = $_POST['status'];

    foreach ($statuses as $roll => $status) {
        // For demo, we're using static names. You can fetch from users table.
        $studentName = "Student " . $roll;

        $stmt = $conn->prepare("INSERT INTO attendance (student_name, roll_number, date, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $studentName, $roll, $date, $status);
        $stmt->execute();
    }

    echo "<script>alert('Attendance submitted successfully'); window.location.href='teacher-dashboard.html';</script>";
}
?>