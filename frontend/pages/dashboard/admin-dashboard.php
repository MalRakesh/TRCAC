<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - TRCAC</title>
    <link rel="stylesheet" href="../../styles/style.css">
    <link rel="stylesheet" href="../../styles/light.css" id="theme-style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .admin-dashboard {
            padding: 2rem;
        }

        .tabs {
            display: flex;
            margin-bottom: 2rem;
        }

        .tab {
            padding: 0.7rem 1.5rem;
            background-color: var(--primary-color);
            color: white;
            cursor: pointer;
            border-radius: 5px 5px 0 0;
            margin-right: 0.5rem;
            font-weight: bold;
        }

        .tab.active {
            background-color: var(--secondary-color);
        }

        .tab-content {
            display: none;
            background-color: var(--background-color);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .tab-content.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-delete {
            background-color: #ff6b6b;
            color: white;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #e63946;
        }
    </style>
</head>

<body class="dashboard admin">

    <!-- Header -->
    <header class="site-header">
        <div class="logo">
            <img src="../../assets/images/logo.png" alt="TRCAC Logo">
        </div>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Users</a></li>
                <li><a href="#">Enquiries</a></li>
                <li><a href="#">Events</a></li>
            </ul>
        </nav>
        <button class="hamburger"><i class="fas fa-bars"></i></button>
        <button class="toggle-theme"><i class="fas fa-moon"></i></button>
    </header>

    <!-- Main Content -->
    <main class="admin-dashboard container">
        <h2>Welcome, Admin!</h2>
        <p>Manage events and view student inquiries from this panel.</p>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" onclick="switchTab('enquiries')">Student Enquiries</div>
            <div class="tab" onclick="switchTab('events')">Manage Events</div>
        </div>

        <!-- Tab Contents -->

        <!-- Enquiries Tab -->
        <div id="enquiries" class="tab-content active">
            <h3>Student Enquiries</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                include '../../backend/utils/get-enquiries.php';
                if (!empty($enquiries)) {
                    foreach ($enquiries as $enq) {
                        echo "<tr>
                                <td>{$enq['name']}</td>
                                <td>{$enq['email']}</td>
                                <td>{$enq['subject']}</td>
                                <td>" . substr($enq['message'], 0, 50) . "...</td>
                                <td>{$enq['created_at']}</td>
                                <td>
                                    <form action='delete-enquiry.php' method='POST'>
                                        <input type='hidden' name='id' value='{$enq['id']}'>
                                        <button type='submit' class='btn-delete'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No enquiries found.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        <!-- Events Tab -->
        <div id="events" class="tab-content">
            <h3>Manage Events</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Posted On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                include '../../backend/utils/get-events.php';
                if (!empty($events)) {
                    foreach ($events as $event) {
                        echo "<tr>
                                <td>{$event['title']}</td>
                                <td>{$event['date']}</td>
                                <td>" . substr($event['description'], 0, 50) . "...</td>
                                <td>{$event['created_at']}</td>
                                <td>
                                    <form action='delete-event.php' method='POST'>
                                        <input type='hidden' name='id' value='{$event['id']}'>
                                        <button type='submit' class='btn-delete'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No events found.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
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

    <!-- Tab Switch Script -->
    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            event.currentTarget.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>

</html>