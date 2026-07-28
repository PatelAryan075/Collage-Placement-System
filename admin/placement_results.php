<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_result'])) {
    $student_id = intval($_POST['student_id']);
    $job_id = intval($_POST['job_id']);
    $result = $_POST['result'] ?? 'not_placed';
    $package = $_POST['package'] ?? '';

    $check = db_count("SELECT COUNT(*) FROM placement_results WHERE student_id = ? AND job_id = ?", [$student_id, $job_id]);
    if ($check > 0) {
        $message = '<div class="alert alert-danger">Result already exists for this student and job!</div>';
    } else {
        db_exec(
            "INSERT INTO placement_results (student_id, job_id, result, package) VALUES (?, ?, ?, ?)",
            [$student_id, $job_id, $result, $package]
        );
        db_exec("UPDATE applications SET status = 'selected' WHERE student_id = ? AND job_id = ?", [$student_id, $job_id]);
        $message = '<div class="alert alert-success">Placement result published successfully!</div>';
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    db_exec("DELETE FROM placement_results WHERE id = ?", [$id]);
    header('Location: placement_results.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Results - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Placement</span> Results</h1>
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
        <h2 style="color:#fff; margin:20px 0;">Manage Placement Results</h2>

        <div class="card">
            <h2>Add New Result</h2>
            <?php if ($message) echo $message; ?>
            <form method="POST">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                    <div class="form-group">
                        <label>Student</label>
                        <select name="student_id" required>
                            <option value="">Select Student</option>
                            <?php
                            $students = db_query("SELECT * FROM students ORDER BY name");
                            while ($s = db_fetch($students)) {
                                echo "<option value='" . $s['id'] . "'>" . h($s['name']) . " (" . h($s['email']) . ")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Job</label>
                        <select name="job_id" required>
                            <option value="">Select Job</option>
                            <?php
                            $jobs = db_query("SELECT * FROM jobs ORDER BY title");
                            while ($j = db_fetch($jobs)) {
                                echo "<option value='" . $j['id'] . "'>" . h($j['title']) . " - " . h($j['company']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Result</label>
                        <select name="result" required>
                            <option value="placed">Placed</option>
                            <option value="not_placed">Not Placed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Package (e.g., 12 LPA)</label>
                        <input type="text" name="package" placeholder="e.g., 12 LPA">
                    </div>
                </div>
                <button type="submit" name="add_result" class="btn btn-primary" style="width:100%;">Publish Result</button>
            </form>
        </div>

        <div class="card">
            <h2>Published Results</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Job</th>
                            <th>Company</th>
                            <th>Result</th>
                            <th>Package</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $results = db_query("
                            SELECT pr.*, s.name as sname, j.title as jtitle, j.company 
                            FROM placement_results pr 
                            JOIN students s ON pr.student_id = s.id 
                            JOIN jobs j ON pr.job_id = j.id 
                            ORDER BY pr.created_at DESC
                        ");
                        $has_results = false;
                        while ($r = db_fetch($results)) {
                            $has_results = true;
                            $badge = $r['result'] == 'placed' ? 'btn-success' : 'btn-danger';
                            echo "<tr>
                                <td><strong>" . h($r['sname']) . "</strong></td>
                                <td>" . h($r['jtitle']) . "</td>
                                <td>" . h($r['company']) . "</td>
                                <td><span class='btn {$badge}' style='padding:3px 10px; font-size:12px;'>" . h($r['result']) . "</span></td>
                                <td>" . h($r['package']) . "</td>
                                <td>" . h($r['created_at']) . "</td>
                                <td><a href='placement_results.php?delete=" . $r['id'] . "' class='btn btn-danger' style='padding:5px 10px; font-size:12px;' onclick='return confirm(\"Delete this result?\")'>Delete</a></td>
                            </tr>";
                        }
                        if (!$has_results) {
                            echo "<tr><td colspan='7' style='text-align:center;'>No results published yet.</td></tr>";
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
