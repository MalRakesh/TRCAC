<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../../login.html");
    exit;
}

// Check if email is set
if (!isset($_SESSION['email'])) {
    die("Error: Session not set. Please login again.");
}

include '../../../backend/db.php';
$email = $_SESSION['email'];

// Fetch student name
$stmt = $conn->prepare("SELECT name FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: User not found in database.");
}

$user = $result->fetch_assoc();
$name = htmlspecialchars($user['name']); // Prevent XSS
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Dashboard - TRCAC</title>
    <link rel="stylesheet" href="../../styles/style.css" />
    <link rel="stylesheet" href="../../styles/light.css" id="theme-style" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        .dashboard-main {
            padding: 2rem;
            max-width: 1200px;
            margin: auto;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), #0c2a4d);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .welcome-section h2 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
        }

        .welcome-section p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background-color: var(--background-color);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .stat-card h4 {
            font-size: 1.5rem;
            color: var(--text-color);
            margin: 0;
        }

        .stat-card p {
            font-size: 0.9rem;
            color: var(--text-color);
            opacity: 0.8;
            margin: 0.3rem 0 0;
        }

        .course-section h3 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
            text-align: center;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .course-card {
            background-color: var(--background-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .course-card iframe {
            width: 100%;
            height: 200px;
            border: none;
            border-radius: 8px 8px 0 0;
        }

        .course-content {
            padding: 1rem;
        }

        .course-content h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        .course-content p {
            font-size: 0.95rem;
            color: var(--text-color);
            margin: 0;
        }

        .btn-enroll {
            display: inline-block;
            margin-top: 0.8rem;
            background-color: var(--secondary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s ease;
        }

        .btn-enroll:hover {
            background-color: var(--accent-color);
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 1.5rem;
        }

        .nav-links li a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links li a:hover,
        .nav-links li a.active {
            color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .dashboard-main {
                padding: 1rem;
            }
            .welcome-section {
                padding: 1.5rem;
            }
            .nav-links {
                flex-direction: column;
                align-items: center;
            }
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
                <li><a href="#" class="active">Profile</a></li>
                <li><a href="#courses">Courses</a></li>
                <li><a href="#attendance">Attendance</a></li>
                <li><a href="#results">Results</a></li>
                <li><a href="#assignments">Assignments</a></li>
            </ul>
        </nav>
        <button class="hamburger"><i class="fas fa-bars"></i></button>
        <button class="toggle-theme"><i class="fas fa-moon"></i></button>
    </header>

    <main class="dashboard-main">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <h2>Welcome, <?= $name ?>!</h2>
            <p>Access your courses, track attendance, and stay updated with college events.</p>
        </section>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-book"></i>
                <h4>8</h4>
                <p>Courses Enrolled</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-check"></i>
                <h4>94%</h4>
                <p>Attendance</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-graduation-cap"></i>
                <h4>A+</h4>
                <p>Average Grade</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-tasks"></i>
                <h4>6</h4>
                <p>Pending Assignments</p>
            </div>
        </div>

        <!-- Courses Section -->
        <section id="courses" class="course-section">
            <h3>Recommended Courses</h3>
            <div class="course-grid">
                <div class="course-card">
                    <iframe src="https://www.youtube.com/embed/tgbNymZ7vqY?rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe>
                    <div class="course-content">
                        <h4>HTML & CSS Fundamentals</h4>
                        <p>Learn basics of web development from scratch.</p>
                        <a href="https://www.youtube.com/watch?v=tgbNymZ7vqY" target="_blank" class="btn-enroll">Watch Now</a>
                    </div>
                </div>
                <div class="course-card">
                    <iframe src="https://www.youtube.com/embed/ER9SsR7VqLw?rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe>
                    <div class="course-content">
                        <h4>JavaScript Full Course</h4>
                        <p>Master JavaScript with hands-on examples.</p>
                        <a href="https://www.youtube.com/watch?v=ER9SsR7VqLw" target="_blank" class="btn-enroll">Watch Now</a>
                    </div>
                </div>
                <div class="course-card">
                    <iframe src="https://www.geeksforgeeks.org/data-structures/" frameborder="0"></iframe>
                    <div class="course-content">
                        <h4>Data Structures & Algorithms</h4>
                        <p>Prepare for placements with DSA practice.</p>
                        <a href="https://www.geeksforgeeks.org/data-structures/" target="_blank" class="btn-enroll">Read Now</a>
                    </div>
                </div>
                <div class="course-card">
                    <iframe src="https://codesandbox.io/embed/react-template-forked-7zjgg?fontsize=14&hidenavigation=1&theme=dark" frameborder="0" sandbox="allow-modals allow-forms allow-popups allow-scripts allow-same-origin"></iframe>
                    <div class="course-content">
                        <h4>Coding Playground</h4>
                        <p>Practice coding in HTML, CSS, JS, React.</p>
                        <a href="https://codesandbox.io/s/react-template-forked-7zjgg" target="_blank" class="btn-enroll">Try Now</a>
                    </div>
                </div>
            </div>
        </section>
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