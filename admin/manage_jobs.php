<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $job_id = intval($_GET['delete']);
    db_exec("DELETE FROM applications WHERE job_id = ?", [$job_id]);
    db_exec("DELETE FROM placement_results WHERE job_id = ?", [$job_id]);
    db_exec("DELETE FROM jobs WHERE id = ?", [$job_id]);
    header('Location: manage_jobs.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Manage</span> Jobs</h1>
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
        <div style="display:flex; justify-content:space-between; align-items:center; margin:20px 0;">
            <h2 style="color:#fff;">All Job Postings</h2>
            <a href="post_job.php" class="btn btn-primary">+ Post New Job</a>
        </div>

        <div class="card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Last Date</th>
                            <th>Applications</th>
                            <th>Posted On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $jobs = db_query("
                            SELECT j.*, 
                                   (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as app_count 
                            FROM jobs j 
                            ORDER BY j.created_at DESC
                        ");
                        $has_jobs = false;
                        while ($job = db_fetch($jobs)) {
                            $has_jobs = true;
                            echo "<tr>
                                <td><strong>" . h($job['title']) . "</strong></td>
                                <td>" . h($job['company']) . "</td>
                                <td>" . h($job['location']) . "</td>
                                <td>" . h($job['last_date']) . "</td>
                                <td>" . h($job['app_count']) . "</td>
                                <td>" . h($job['created_at']) . "</td>
                                <td>
                                    <a href='view_applications.php?job_id=" . $job['id'] . "' class='btn btn-primary' style='padding:5px 10px; font-size:12px;'>Applications</a>
                                    <a href='manage_jobs.php?delete=" . $job['id'] . "' class='btn btn-danger' style='padding:5px 10px; font-size:12px;' onclick='return confirm(\"Delete this job?\")'>Delete</a>
                                </td>
                            </tr>";
                        }
                        if (!$has_jobs) {
                            echo "<tr><td colspan='7' style='text-align:center;'>No jobs posted yet.</td></tr>";
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
