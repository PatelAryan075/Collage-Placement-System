<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $company = $_POST['company'] ?? '';
    $location = $_POST['location'] ?? '';
    $eligibility = $_POST['eligibility'] ?? '';
    $last_date = $_POST['last_date'] ?? '';

    db_exec(
        "INSERT INTO jobs (title, description, company, location, eligibility, last_date, created_by) 
         VALUES (?, ?, ?, ?, ?, ?, ?)",
        [$title, $description, $company, $location, $eligibility, $last_date, $admin_id]
    );
    $message = '<div class="alert alert-success">Job posted successfully!</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Post</span> New Job</h1>
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
        <div class="card" style="max-width:800px; margin:40px auto;">
            <h2>Post a New Job Opening</h2>
            <?php if ($message) echo $message; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company" required>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" required>
                </div>
                <div class="form-group">
                    <label>Job Description</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>Eligibility Criteria</label>
                    <textarea name="eligibility" required></textarea>
                </div>
                <div class="form-group">
                    <label>Last Date to Apply</label>
                    <input type="date" name="last_date" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Post Job</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 College Placement System. All rights reserved.</p>
    </footer>
</body>
</html>
