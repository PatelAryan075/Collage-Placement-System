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
    <title>View Jobs - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Available</span> Jobs</h1>
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
        <h2 style="color:#fff; margin:20px 0;">Job Openings</h2>

        <div class="card">
            <?php
            $jobs = db_query("SELECT * FROM jobs ORDER BY created_at DESC");
            $has_jobs = false;
            while ($job = db_fetch($jobs)) {
                $has_jobs = true;
                $applied = db_count("SELECT COUNT(*) FROM applications WHERE student_id = ? AND job_id = ?", [$student_id, $job['id']]);
                ?>
                <div class="job-card">
                    <h3><?php echo h($job['title']); ?></h3>
                    <div class="company"><?php echo h($job['company']); ?></div>
                    <div class="meta">
                        Location: <?php echo h($job['location']); ?> | 
                        Last Date: <?php echo h($job['last_date']); ?>
                    </div>
                    <p style="margin:10px 0; color:#555;"><?php echo h(substr($job['description'], 0, 200)); ?>...</p>
                    <div style="margin-top:10px;">
                        <strong>Eligibility:</strong> <?php echo h($job['eligibility']); ?>
                    </div>
                    <div style="margin-top:15px;">
                        <?php if ($applied > 0): ?>
                            <span class="btn btn-success" style="padding:5px 15px;">Already Applied</span>
                        <?php else: ?>
                            <a href="apply_job.php?id=<?php echo $job['id']; ?>" class="btn btn-primary" style="padding:5px 15px;">Apply Now</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
            if (!$has_jobs) {
                echo "<p style='color:#888; text-align:center; padding:40px;'>No jobs available at the moment.</p>";
            }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 College Placement System. All rights reserved.</p>
    </footer>
</body>
</html>
