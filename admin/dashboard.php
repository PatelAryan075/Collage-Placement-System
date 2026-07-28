<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$admin_name = $_SESSION['admin_name'];

$student_count = db_count("SELECT COUNT(*) FROM students");
$job_count = db_count("SELECT COUNT(*) FROM jobs");
$application_count = db_count("SELECT COUNT(*) FROM applications");
$placed_count = db_count("SELECT COUNT(*) FROM placement_results WHERE result = 'placed'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Admin</span> Dashboard</h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="manage_jobs.php">Manage Jobs</a>
                <a href="post_job.php">Post Job</a>
                <a href="view_applications.php">Applications</a>
                <a href="placement_results.php">Results</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="flex:1;">
        <h2 style="color:#fff; margin:20px 0;">Welcome, <?php echo h($admin_name); ?></h2>

        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $student_count; ?></h3>
                <p>Total Students</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $job_count; ?></h3>
                <p>Total Jobs</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $application_count; ?></h3>
                <p>Applications</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $placed_count; ?></h3>
                <p>Students Placed</p>
            </div>
        </div>

        <div class="card">
            <h2>Quick Actions</h2>
            <div style="display:flex; gap:15px; flex-wrap:wrap;">
                <a href="post_job.php" class="btn btn-primary">Post New Job</a>
                <a href="manage_jobs.php" class="btn btn-success">Manage Jobs</a>
                <a href="view_applications.php" class="btn btn-warning">View Applications</a>
                <a href="placement_results.php" class="btn btn-danger">Manage Results</a>
            </div>
        </div>

        <div class="card">
            <h2>Recent Applications</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Job</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Applied On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $applications = db_query("
                            SELECT a.*, s.name as sname, j.title as jtitle, j.company 
                            FROM applications a 
                            JOIN students s ON a.student_id = s.id 
                            JOIN jobs j ON a.job_id = j.id 
                            ORDER BY a.applied_at DESC LIMIT 10
                        ");
                        $has_apps = false;
                        while ($app = db_fetch($applications)) {
                            $has_apps = true;
                            $badge = $app['status'] == 'applied' ? 'btn-primary' : ($app['status'] == 'shortlisted' ? 'btn-warning' : ($app['status'] == 'selected' ? 'btn-success' : 'btn-danger'));
                            echo "<tr>
                                <td>" . h($app['sname']) . "</td>
                                <td>" . h($app['jtitle']) . "</td>
                                <td>" . h($app['company']) . "</td>
                                <td><span class='btn {$badge}' style='padding:3px 10px; font-size:12px;'>" . h($app['status']) . "</span></td>
                                <td>" . h($app['applied_at']) . "</td>
                            </tr>";
                        }
                        if (!$has_apps) {
                            echo "<tr><td colspan='5' style='text-align:center;'>No applications yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 College Placement System. All rights reserved.</p>
    </footer>
</body>
</html>
