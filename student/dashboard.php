<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];

$job_count = db_count("SELECT COUNT(*) FROM jobs");
$application_count = db_count("SELECT COUNT(*) FROM applications WHERE student_id = ?", [$student_id]);
$placed = db_count("SELECT COUNT(*) FROM placement_results WHERE student_id = ? AND result = 'placed'", [$student_id]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Welcome</span> <?php echo h($student_name); ?></h1>
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
        <h2 style="color:#fff; margin:20px 0;">Student Dashboard</h2>

        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $job_count; ?></h3>
                <p>Available Jobs</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $application_count; ?></h3>
                <p>My Applications</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $placed; ?></h3>
                <p>Placements</p>
            </div>
        </div>

        <div class="card">
            <h2>Quick Actions</h2>
            <div style="display:flex; gap:15px; flex-wrap:wrap;">
                <a href="view_jobs.php" class="btn btn-primary">View Latest Jobs</a>
                <a href="upload_resume.php" class="btn btn-success">Upload Resume</a>
                <a href="view_results.php" class="btn btn-warning">View Results</a>
            </div>
        </div>

        <div class="card">
            <h2>Recent Job Openings</h2>
            <?php
            $jobs = db_query("SELECT * FROM jobs ORDER BY created_at DESC LIMIT 5");
            $has_jobs = false;
            while ($job = db_fetch($jobs)) {
                $has_jobs = true;
                $applied = db_count("SELECT COUNT(*) FROM applications WHERE student_id = ? AND job_id = ?", [$student_id, $job['id']]);
                ?>
                <div class="job-card">
                    <h3><?php echo h($job['title']); ?></h3>
                    <div class="company"><?php echo h($job['company']); ?></div>
                    <div class="meta">Location: <?php echo h($job['location']); ?> | Last Date: <?php echo h($job['last_date']); ?></div>
                    <?php if ($applied > 0): ?>
                        <span class="btn btn-success" style="padding:5px 15px; font-size:12px;">Applied</span>
                    <?php else: ?>
                        <a href="apply_job.php?id=<?php echo $job['id']; ?>" class="btn btn-primary" style="padding:5px 15px; font-size:12px;">Apply Now</a>
                    <?php endif; ?>
                </div>
                <?php
            }
            if (!$has_jobs) {
                echo "<p style='color:#888;'>No jobs posted yet.</p>";
            }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 College Placement System. All rights reserved.</p>
    </footer>
</body>
</html>
