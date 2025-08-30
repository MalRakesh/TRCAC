<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../../login.html");
    exit;
}

include '../../../backend/db.php';
$email = $_SESSION['email'];

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM student_profiles WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Profile not found. Contact admin.");
}

$profile = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $address = $conn->real_escape_string($_POST['address']);
    $guardian_name = $conn->real_escape_string($_POST['guardian_name']);
    $father_name = $conn->real_escape_string($_POST['father_name']);
    $mother_name = $conn->real_escape_string($_POST['mother_name']);
    $class_10_percentage = floatval($_POST['class_10_percentage']);
    $class_12_percentage = floatval($_POST['class_12_percentage']);
    $enrollment_year = intval($_POST['enrollment_year']);
    $course_code = $conn->real_escape_string($_POST['course_code']);
    $year_level = $conn->real_escape_string($_POST['year_level']);

    // Handle profile image upload
    $profile_image = $profile['profile_image']; // Keep old image by default
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $uploadDir = '../../../uploads/profiles/';
        $fileName = time() . '_' . basename($_FILES['profile_image']['name']);
        $targetPath = $uploadDir . $fileName;
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        if (in_array($fileType, $allowedTypes) && $_FILES['profile_image']['size'] <= 5000000) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
                if ($profile['profile_image'] != 'default.jpg') {
                    unlink($uploadDir . $profile['profile_image']); // Delete old image
                }
                $profile_image = $fileName;
            } else {
                echo "<script>alert('Failed to upload image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type or size too large.');</script>";
        }
    }

    // Update query
    $sql = "UPDATE student_profiles SET 
        name = ?, phone = ?, dob = ?, address = ?, guardian_name = ?, 
        father_name = ?, mother_name = ?, class_10_percentage = ?, 
        class_12_percentage = ?, enrollment_year = ?, course_code = ?, 
        year_level = ?, profile_image = ? 
        WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssiiisss", 
        $name, $phone, $dob, $address, $guardian_name,
        $father_name, $mother_name, $class_10_percentage, $class_12_percentage,
        $enrollment_year, $course_code, $year_level, $profile_image, $email
    );

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='student-dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile - TRCAC</title>
    <link rel="stylesheet" href="../../styles/style.css" />
    <link rel="stylesheet" href="../../styles/light.css" id="theme-style" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        .profile-edit-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: var(--background-color);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-edit-container h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--text-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #fff;
            color: var(--text-color);
        }

        .profile-image-preview {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .profile-image-preview img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 1rem;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background-color: var(--accent-color);
        }

        .back-link {
            display: block;
            text-align: center;
            margin: 1rem 0;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="dashboard student">
    <header class="site-header">
        <div class="logo">
            <img src="../../assets/images/logo.png" alt="TRCAC Logo">
        </div>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="student-dashboard.php">← Back to Dashboard</a></li>
            </ul>
        </nav>
        <button class="hamburger"><i class="fas fa-bars"></i></button>
        <button class="toggle-theme"><i class="fas fa-moon"></i></button>
    </header>

    <main class="profile-edit-container">
        <h2>Edit Your Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <!-- Profile Image -->
            <div class="profile-image-preview">
                <img src="../../../uploads/profiles/<?= $profile['profile_image'] ?: 'default.jpg' ?>" alt="Profile Photo">
                <label for="profile_image">Change Photo</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*">
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($profile['name']) ?>" required>
            </div>

            <!-- Email (Readonly) -->
            <div class="form-group">
                <label>Email</label>
                <input type="text" value="<?= $email ?>" readonly style="background-color: #f0f0f0;">
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($profile['phone']) ?>">
            </div>

            <!-- DOB -->
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?= $profile['dob'] ?>">
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" rows="3"><?= htmlspecialchars($profile['address']) ?></textarea>
            </div>

            <!-- Guardian Name -->
            <div class="form-group">
                <label for="guardian_name">Guardian Name</label>
                <input type="text" name="guardian_name" id="guardian_name" value="<?= htmlspecialchars($profile['guardian_name']) ?>">
            </div>

            <!-- Father Name -->
            <div class="form-group">
                <label for="father_name">Father's Name</label>
                <input type="text" name="father_name" id="father_name" value="<?= htmlspecialchars($profile['father_name']) ?>">
            </div>

            <!-- Mother Name -->
            <div class="form-group">
                <label for="mother_name">Mother's Name</label>
                <input type="text" name="mother_name" id="mother_name" value="<?= htmlspecialchars($profile['mother_name']) ?>">
            </div>

            <!-- Class 10 Percentage -->
            <div class="form-group">
                <label for="class_10_percentage">Class 10 Percentage</label>
                <input type="number" step="0.01" name="class_10_percentage" id="class_10_percentage" value="<?= $profile['class_10_percentage'] ?>">
            </div>

            <!-- Class 12 Percentage -->
            <div class="form-group">
                <label for="class_12_percentage">Class 12 Percentage</label>
                <input type="number" step="0.01" name="class_12_percentage" id="class_12_percentage" value="<?= $profile['class_12_percentage'] ?>">
            </div>

            <!-- Enrollment Year -->
            <div class="form-group">
                <label for="enrollment_year">Enrollment Year</label>
                <input type="number" name="enrollment_year" id="enrollment_year" value="<?= $profile['enrollment_year'] ?>" min="2020" max="2030">
            </div>

            <!-- Course Code -->
            <div class="form-group">
                <label for="course_code">Course</label>
                <select name="course_code" id="course_code" required>
                    <option value="BSIT" <?= $profile['course_code'] == 'BSIT' ? 'selected' : '' ?>>B.Sc. (IT)</option>
                    <option value="BSCS" <?= $profile['course_code'] == 'BSCS' ? 'selected' : '' ?>>B.Sc. (CS)</option>
                    <option value="BA" <?= $profile['course_code'] == 'BA' ? 'selected' : '' ?>>Bachelor of Arts</option>
                    <option value="BCOM" <?= $profile['course_code'] == 'BCOM' ? 'selected' : '' ?>>Bachelor of Commerce</option>
                    <option value="BMS" <?= $profile['course_code'] == 'BMS' ? 'selected' : '' ?>>BMS</option>
                    <option value="BAF" <?= $profile['course_code'] == 'BAF' ? 'selected' : '' ?>>BAF</option>
                    <option value="BAMMC" <?= $profile['course_code'] == 'BAMMC' ? 'selected' : '' ?>>BAMMC</option>
                    <option value="11A" <?= $profile['course_code'] == '11A' ? 'selected' : '' ?>>FYJC Arts</option>
                    <option value="12A" <?= $profile['course_code'] == '12A' ? 'selected' : '' ?>>SYJC Arts</option>
                    <option value="11C" <?= $profile['course_code'] == '11C' ? 'selected' : '' ?>>FYJC Commerce</option>
                    <option value="12C" <?= $profile['course_code'] == '12C' ? 'selected' : '' ?>>SYJC Commerce</option>
                </select>
            </div>

            <!-- Year Level -->
            <div class="form-group">
                <label for="year_level">Year Level</label>
                <select name="year_level" id="year_level">
                    <option value="FY" <?= $profile['year_level'] == 'FY' ? 'selected' : '' ?>>First Year</option>
                    <option value="SY" <?= $profile['year_level'] == 'SY' ? 'selected' : '' ?>>Second Year</option>
                    <option value="TY" <?= $profile['year_level'] == 'TY' ? 'selected' : '' ?>>Third Year</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">Save Changes</button>
        </form>

        <a href="student-dashboard.php" class="back-link">← Back to Dashboard</a>
    </main>

    <footer class="site-footer">
        <div class="footer-bottom">&copy; 2025 TRCAC. All rights reserved.</div>
    </footer>

    <button class="scroll-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="../../scripts/main.js"></script>
</body>
</html>