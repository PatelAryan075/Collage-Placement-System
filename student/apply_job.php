<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = db_query("SELECT * FROM jobs WHERE id = ?", [$job_id]);
$job = db_fetch($stmt);

if (!$job) {
    header('Location: view_jobs.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check = db_count("SELECT COUNT(*) FROM applications WHERE student_id = ? AND job_id = ?", [$student_id, $job_id]);
    if ($check > 0) {
        $message = '<div class="alert alert-danger">You have already applied for this job!</div>';
    } else {
        $stmt = db_query("SELECT * FROM students WHERE id = ?", [$student_id]);
        $student = db_fetch($stmt);
        if (empty($student['resume_path'])) {
            $message = '<div class="alert alert-danger">Please upload your resume before applying!</div>';
        } else {
            db_exec("INSERT INTO applications (student_id, job_id) VALUES (?, ?)", [$student_id, $job_id]);
            $message = '<div class="alert alert-success">Application submitted successfully!</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Apply</span> for Job</h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="view_jobs.php">View Jobs</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container" style="flex:1;">
        <div class="card" style="max-width:700px; margin:40px auto;">
            <h2><?php echo h($job['title']); ?> - <?php echo h($job['company']); ?></h2>
            <p><strong>Location:</strong> <?php echo h($job['location']); ?></p>
            <p><strong>Eligibility:</strong> <?php echo h($job['eligibility']); ?></p>
            <p><strong>Last Date:</strong> <?php echo h($job['last_date']); ?></p>
            <hr style="margin:20px 0;">
            <h3>Job Description</h3>
            <p style="margin:15px 0; line-height:1.8;"><?php echo nl2br(h($job['description'])); ?></p>
            <hr style="margin:20px 0;">

            <?php
            if ($message) echo $message;
            $already = db_count("SELECT COUNT(*) FROM applications WHERE student_id = ? AND job_id = ?", [$student_id, $job_id]);
            if ($already == 0):
            ?>
            <form method="POST">
                <p style="margin-bottom:20px;">By clicking submit, you confirm that you want to apply for this position.</p>
                <button type="submit" class="btn btn-primary">Confirm & Apply</button>
                <a href="view_jobs.php" class="btn btn-danger">Cancel</a>
            </form>
            <?php else: ?>
                <div class="alert alert-info">You have already applied for this job.</div>
                <a href="view_jobs.php" class="btn btn-primary">Back to Jobs</a>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 College Placement System. All rights reserved.</p>
    </footer>
</body>
</html>
