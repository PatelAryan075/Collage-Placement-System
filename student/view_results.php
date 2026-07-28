<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>My</span> Results</h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="view_jobs.php">View Jobs</a>
                <a href="upload_resume.php">Upload Resume</a>
                <a href="view_results.php">My Results</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="flex:1;">
        <h2 style="color:#fff; margin:20px 0;">Placement Results</h2>

        <div class="card">
            <?php
            $results = db_query("
                SELECT pr.*, j.title, j.company 
                FROM placement_results pr 
                JOIN jobs j ON pr.job_id = j.id 
                WHERE pr.student_id = ? 
                ORDER BY pr.created_at DESC
            ", [$student_id]);

            $has_results = false;
            $rows = db_fetch_all($results);
            if (count($rows) > 0) {
                $has_results = true;
                ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Result</th>
                                <th>Package</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?php echo h($row['title']); ?></td>
                                    <td><?php echo h($row['company']); ?></td>
                                    <td>
                                        <?php if ($row['result'] == 'placed'): ?>
                                            <span class="btn btn-success" style="padding:3px 10px; font-size:12px;">Placed</span>
                                        <?php else: ?>
                                            <span class="btn btn-danger" style="padding:3px 10px; font-size:12px;">Not Placed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['package'] ? h($row['package']) : '-'; ?></td>
                                    <td><?php echo h($row['created_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            if (!$has_results) {
                echo "<p style='color:#888; text-align:center; padding:40px;'>No results published yet. Check back later.</p>";
            }
            ?>
        </div>

        <div class="card">
            <h2>My Applications Status</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Applied On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $applications = db_query("
                            SELECT a.*, j.title, j.company 
                            FROM applications a 
                            JOIN jobs j ON a.job_id = j.id 
                            WHERE a.student_id = ? 
                            ORDER BY a.applied_at DESC
                        ", [$student_id]);
                        $has_apps = false;
                        while ($app = db_fetch($applications)) {
                            $has_apps = true;
                            $badge = $app['status'] == 'applied' ? 'btn-primary' : ($app['status'] == 'shortlisted' ? 'btn-warning' : ($app['status'] == 'selected' ? 'btn-success' : 'btn-danger'));
                            echo "<tr>
                                <td>" . h($app['title']) . "</td>
                                <td>" . h($app['company']) . "</td>
                                <td><span class='btn {$badge}' style='padding:3px 10px; font-size:12px;'>" . h($app['status']) . "</span></td>
                                <td>" . h($app['applied_at']) . "</td>
                            </tr>";
                        }
                        if (!$has_apps) {
                            echo "<tr><td colspan='4' style='text-align:center;'>No applications submitted.</td></tr>";
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
