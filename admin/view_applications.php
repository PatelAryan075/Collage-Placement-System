<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$job_filter = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

if (isset($_GET['update_status']) && isset($_GET['app_id']) && isset($_GET['status'])) {
    $app_id = intval($_GET['app_id']);
    $status = $_GET['status'];
    $allowed_statuses = ['applied', 'shortlisted', 'selected', 'rejected'];
    if (in_array($status, $allowed_statuses)) {
        db_exec("UPDATE applications SET status = ? WHERE id = ?", [$status, $app_id]);
    }
    header("Location: view_applications.php" . ($job_filter ? "?job_id=$job_filter" : ""));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Applications</span> Management</h1>
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
        <h2 style="color:#fff; margin:20px 0;">Student Applications</h2>

        <div class="card">
            <form method="GET" style="margin-bottom:20px;">
                <div style="display:flex; gap:10px; align-items:center;">
                    <label style="font-weight:500;">Filter by Job:</label>
                    <select name="job_id" style="padding:8px; border:1px solid #ddd; border-radius:5px; flex:1;">
                        <option value="">All Jobs</option>
                        <?php
                        $jobs = db_query("SELECT * FROM jobs ORDER BY title");
                        while ($j = db_fetch($jobs)) {
                            $selected = ($job_filter == $j['id']) ? 'selected' : '';
                            echo "<option value='" . $j['id'] . "' $selected>" . h($j['title']) . " - " . h($j['company']) . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary" style="padding:8px 20px;">Filter</button>
                </div>
            </form>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Job</th>
                            <th>Company</th>
                            <th>Resume</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $where = $job_filter ? "WHERE a.job_id = :job_id" : "";
                        $query = "
                            SELECT a.*, s.name as sname, s.email as semail, s.course, s.resume_path,
                                   j.title as jtitle, j.company 
                            FROM applications a 
                            JOIN students s ON a.student_id = s.id 
                            JOIN jobs j ON a.job_id = j.id 
                            $where 
                            ORDER BY a.applied_at DESC
                        ";
                        if ($job_filter) {
                            $applications = db_query($query, ['job_id' => $job_filter]);
                        } else {
                            $applications = db_query($query);
                        }
                        $has_apps = false;
                        while ($app = db_fetch($applications)) {
                            $has_apps = true;
                            $badge = $app['status'] == 'applied' ? 'btn-primary' : ($app['status'] == 'shortlisted' ? 'btn-warning' : ($app['status'] == 'selected' ? 'btn-success' : 'btn-danger'));
                            $resume_link = $app['resume_path'] ? "<a href='../" . h($app['resume_path']) . "' target='_blank' class='btn btn-primary' style='padding:3px 8px; font-size:11px;'>View</a>" : "No Resume";
                            echo "<tr>
                                <td><strong>" . h($app['sname']) . "</strong></td>
                                <td>" . h($app['semail']) . "</td>
                                <td>" . h($app['course']) . "</td>
                                <td>" . h($app['jtitle']) . "</td>
                                <td>" . h($app['company']) . "</td>
                                <td>{$resume_link}</td>
                                <td><span class='btn {$badge}' style='padding:3px 10px; font-size:12px;'>" . h($app['status']) . "</span></td>
                                <td>" . h($app['applied_at']) . "</td>
                                <td style='white-space:nowrap;'>
                                    <a href='view_applications.php?update_status=1&app_id={$app['id']}&status=shortlisted" . ($job_filter ? "&job_id=$job_filter" : "") . "' class='btn btn-warning' style='padding:3px 8px; font-size:11px;'>Shortlist</a>
                                    <a href='view_applications.php?update_status=1&app_id={$app['id']}&status=selected" . ($job_filter ? "&job_id=$job_filter" : "") . "' class='btn btn-success' style='padding:3px 8px; font-size:11px;'>Select</a>
                                    <a href='view_applications.php?update_status=1&app_id={$app['id']}&status=rejected" . ($job_filter ? "&job_id=$job_filter" : "") . "' class='btn btn-danger' style='padding:3px 8px; font-size:11px;'>Reject</a>
                                </td>
                            </tr>";
                        }
                        if (!$has_apps) {
                            echo "<tr><td colspan='9' style='text-align:center;'>No applications found.</td></tr>";
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
