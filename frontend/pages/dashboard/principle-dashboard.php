<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'principal') {
    header("Location: ../../login.html");
    exit;
}

include '../../../backend/db.php';

$name = $_SESSION['name'];

// Get counts from database
$stats = [
    'students' => $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'],
    'teachers' => $conn->query("SELECT COUNT(*) as total FROM users WHERE role='teacher'")->fetch_assoc()['total'],
    'hods' => $conn->query("SELECT COUNT(*) as total FROM users WHERE role='hod'")->fetch_assoc()['total'],
    'events' => $conn->query("SELECT COUNT(*) as total FROM events")->fetch_assoc()['total'],
    'notices' => $conn->query("SELECT COUNT(*) as total FROM notices")->fetch_assoc()['total'],
    'awards' => $conn->query("SELECT COUNT(*) as total FROM awards")->fetch_assoc()['total'],
    'admissions' => $conn->query("SELECT COUNT(*) as total FROM admissions")->fetch_assoc()['total'],
    'enquiries' => $conn->query("SELECT COUNT(*) as total FROM enquiries")->fetch_assoc()['total']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Principal Dashboard - TRCAC</title>
    <link rel="stylesheet" href="../../styles/style.css" />
    <link rel="stylesheet" href="../../styles/light.css" id="theme-style" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        .dashboard-main { padding: 2rem; max-width: 1200px; margin: auto; }
        .welcome-section { text-align: center; margin-bottom: 3rem; padding: 2rem; background-color: var(--primary-color); color: white; border-radius: 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
        .stat-card { background-color: var(--background-color); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; }
        .stat-card i { font-size: 2rem; color: var(--secondary-color); margin-bottom: 0.5rem; }
        .stat-card h4 { font-size: 1.5rem; color: var(--text-color); margin: 0; }
        .stat-card p { font-size: 0.9rem; color: var(--text-color); opacity: 0.8; margin: 0.3rem 0 0; }

        .report-section { margin: 3rem 0; }
        .report-card { background-color: var(--background-color); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 1rem; }
        .report-card h4 { margin: 0 0 0.5rem 0; color: var(--text-color); }

        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.8rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: var(--primary-color); color: white; }

        .btn-view { display: inline-block; background-color: var(--secondary-color); color: white; padding: 0.5rem 1rem; border-radius: 30px; text-decoration: none; font-size: 0.9rem; }
        .btn-view:hover { background-color: var(--accent-color); }

        @media (max-width: 768px) {
            .dashboard-main { padding: 1rem; }
            .welcome-section { padding: 1.5rem; }
        }
    </style>
</head>
<body class="dashboard principal">

    <!-- Header -->
    <header class="site-header">
        <div class="logo">
            <img src="../../assets/images/logo.png" alt="TRCAC Logo">
        </div>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#" class="active">Overview</a></li>
                <li><a href="#reports">Reports</a></li>
                <li><a href="#notices">Notices</a></li>
                <li><a href="#events">Events</a></li>
                <li><a href="#awards">Awards</a></li>
            </ul>
        </nav>
        <button class="hamburger"><i class="fas fa-bars"></i></button>
        <button class="toggle-theme"><i class="fas fa-moon"></i></button>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <h2>Welcome, <?= $name ?>!</h2>
            <p>You are viewing the principal dashboard with full access to all departments.</p>
        </section>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h4><?= $stats['students'] ?></h4>
                <p>Total Students</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <h4><?= $stats['teachers'] ?></h4>
                <p>Teachers</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-tie"></i>
                <h4><?= $stats['hods'] ?></h4>
                <p>HODs</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-alt"></i>
                <h4><?= $stats['events'] ?></h4>
                <p>Events</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-bell"></i>
                <h4><?= $stats['notices'] ?></h4>
                <p>Notices</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-award"></i>
                <h4><?= $stats['awards'] ?></h4>
                <p>Awards</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-file-alt"></i>
                <h4><?= $stats['admissions'] ?></h4>
                <p>Admissions</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-comments"></i>
                <h4><?= $stats['enquiries'] ?></h4>
                <p>Enquiries</p>
            </div>
        </div>

        <!-- Reports Section -->
        <section id="reports" class="report-section">
            <h3>Department Reports</h3>
            <div class="report-card">
                <h4>Junior College (Arts & Commerce)</h4>
                <p>Total Students: <?= $stats['students'] * 0.4 ?></p>
                <p>Teachers: 15</p>
                <p>Average Attendance: 91%</p>
                <a href="#" class="btn-view">View Details</a>
            </div>
            <div class="report-card">
                <h4>Degree College (BA, BCom, BMS, BAF, BAMMC)</h4>
                <p>Total Students: <?= $stats['students'] * 0.3 ?></p>
                <p>Teachers: 20</p>
                <p>Average Attendance: 93%</p>
                <a href="#" class="btn-view">View Details</a>
            </div>
            <div class="report-card">
                <h4>Science Department (B.Sc. IT & CS)</h4>
                <p>Total Students: <?= $stats['students'] * 0.3 ?></p>
                <p>Teachers: 18</p>
                <p>Average Attendance: 95%</p>
                <a href="#" class="btn-view">View Details</a>
            </div>
        </section>

        <!-- Notices -->
        <section id="notices" class="report-section">
            <h3>Latest Notices</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $notices = $conn->query("SELECT title, posted_at FROM notices ORDER BY posted_at DESC LIMIT 5");
                    while ($row = $notices->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['posted_at'] ?></td>
                        <td><a href="../../pages/notices.html" class="btn-view">View</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Events -->
        <section id="events" class="report-section">
            <h3>Upcoming Events</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $events = $conn->query("SELECT title, date, description FROM events WHERE date >= CURDATE() ORDER BY date LIMIT 5");
                    while ($row = $events->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['date'] ?></td>
                        <td><?= substr($row['description'], 0, 100) ?>...</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-bottom">&copy; 2025 TRCAC. All rights reserved.</div>
    </footer>

    <!-- Scroll Top Button -->
    <button class="scroll-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="../../scripts/main.js"></script>
</body>
</html>