<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];
$message = '';

$stmt = db_query("SELECT * FROM students WHERE id = ?", [$student_id]);
$student = db_fetch($stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resume'])) {
    $target_dir = realpath(__DIR__ . '/../uploads/resumes') . DIRECTORY_SEPARATOR;
    $file_ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
    $file_name = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file_ext;
    $target_file = $target_dir . $file_name;

    $allowed = ['pdf', 'doc', 'docx'];
    if (!in_array($file_ext, $allowed)) {
        $message = '<div class="alert alert-danger">Only PDF, DOC, and DOCX files are allowed!</div>';
    } elseif ($_FILES['resume']['size'] > 5000000) {
        $message = '<div class="alert alert-danger">File size must be less than 5MB!</div>';
    } elseif ($_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
        $message = '<div class="alert alert-danger">Upload failed with error code ' . $_FILES['resume']['error'] . '.</div>';
    } else {
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
            if (!empty($student['resume_path'])) {
                $old_file = realpath(__DIR__ . '/../' . $student['resume_path']);
                if ($old_file && str_starts_with($old_file, $target_dir) && file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            $path = "uploads/resumes/" . $file_name;
            db_exec("UPDATE students SET resume_path = ? WHERE id = ?", [$path, $student_id]);
            $message = '<div class="alert alert-success">Resume uploaded successfully!</div>';
            $stmt = db_query("SELECT * FROM students WHERE id = ?", [$student_id]);
            $student = db_fetch($stmt);
        } else {
            $message = '<div class="alert alert-danger">Failed to upload resume!</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Resume - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>Upload</span> Resume</h1>
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
        <div class="card" style="max-width:600px; margin:40px auto;">
            <h2>Upload Your Resume</h2>
            <?php if ($message) echo $message; ?>

            <?php if (!empty($student['resume_path'])): ?>
                <div class="alert alert-info">
                    Current Resume: <a href="../<?php echo h($student['resume_path']); ?>" target="_blank">View Resume</a>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Select Resume (PDF, DOC, DOCX - Max 5MB)</label>
                    <input type="file" name="resume" required accept=".pdf,.doc,.docx">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Upload Resume</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 College Placement System. All rights reserved.</p>
    </footer>
</body>
</html>
