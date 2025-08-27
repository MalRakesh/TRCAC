<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HOD Dashboard - TRCAC</title>
    <link rel="stylesheet" href="../../styles/style.css" />
    <link rel="stylesheet" href="../../styles/light.css" id="theme-style" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body class="dashboard hod">

    <!-- Header -->
    <header class="site-header">
        <div class="logo">
            <img src="../../assets/images/logo.png" alt="TRCAC Logo">
        </div>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Attendance</a></li>
                <li><a href="#">Department</a></li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-bell"></i></a>
                    <ul class="dropdown-content">
                        <li><a href="#">Requests</a></li>
                        <li><a href="#">Notices</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <button class="hamburger"><i class="fas fa-bars"></i></button>
        <button class="toggle-theme"><i class="fas fa-moon"></i></button>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <section class="welcome-section">
            <h2>Welcome, HOD!</h2>
            <p>Review department performance and check attendance here.</p>
        </section>

        <!-- Reports -->
        <section class="report-section">
            <h3>Department Reports</h3>
            <div class="report-card">
                <h4>Attendance Summary</h4>
                <p>Total Students: 120</p>
                <p>Average Attendance: 92%</p>
            </div>
            <div class="report-card">
                <h4>Recent Events</h4>
                <ul>
                    <li>Technical Seminar - 12 Mar 2025</li>
                    <li>Guest Lecture - 20 Apr 2025</li>
                </ul>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-bottom">
            &copy; 2025 TRCAC. All rights reserved.
        </div>
    </footer>

    <!-- Scroll Top Button -->
    <button class="scroll-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="../../scripts/main.js"></script>
</body>

</html>