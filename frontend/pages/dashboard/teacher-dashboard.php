<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Dashboard - TRCAC</title>
    <link rel="stylesheet" href="../../styles/style.css" />
    <link rel="stylesheet" href="../../styles/light.css" id="theme-style" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body class="dashboard teacher">

    <!-- Header -->
    <header class="site-header">
        <div class="logo">
            <img src="../../assets/images/logo.png" alt="TRCAC Logo">
        </div>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">My Class</a></li>
                <li><a href="#">Attendance</a></li>
                <li><a href="#">Upload Material</a></li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-bell"></i></a>
                    <ul class="dropdown-content">
                        <li><a href="#">New Requests</a></li>
                        <li><a href="#">Events</a></li>
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
            <h2>Welcome, Teacher!</h2>
            <p>You can mark attendance and manage your students here.</p>
        </section>

        <!-- Attendance Form -->
        <section class="attendance-section">
            <h3>Mark Attendance</h3>
            <form action="../../backend/dashboard/attendance.php" method="POST">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Amit Patel</td>
                            <td>
                                <label><input type="radio" name="status[1]" value="Present"> Present</label>
                                <label><input type="radio" name="status[1]" value="Absent"> Absent</label>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Sneha Sharma</td>
                            <td>
                                <label><input type="radio" name="status[2]" value="Present"> Present</label>
                                <label><input type="radio" name="status[2]" value="Absent"> Absent</label>
                            </td>
                        </tr>
                        <!-- Add more students dynamically -->
                    </tbody>
                </table>
                <button type="submit" class="btn primary-btn">Submit Attendance</button>
                <a href="../../backend/utils/generate-pdf.php" class="btn secondary-btn">Download PDF</a>
            </form>
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