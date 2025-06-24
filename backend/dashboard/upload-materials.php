<?php
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $course = $_POST['course'];

    // Handle file upload
    $targetDir = "../../uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowedTypes = array('pdf', 'pptx', 'docx', 'xlsx');

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            $sql = "INSERT INTO course_materials (title, description, course, file_path) VALUES ('$title', '$description', '$course', '$fileName')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('File uploaded successfully'); window.location.href='../pages/dashboard/teacher-dashboard.html';</script>";
            } else {
                echo "Error saving to database.";
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid file type.";
    }
}
?>