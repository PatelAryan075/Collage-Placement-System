<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $course = $_POST['course'] ?? '';
    $year = $_POST['year'] ?? '';

    $check = db_count("SELECT COUNT(*) FROM students WHERE email = ?", [$email]);
    if ($check > 0) {
        $error = 'Email already registered!';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        db_exec(
            "INSERT INTO students (name, email, password, phone, course, year) VALUES (?, ?, ?, ?, ?, ?)",
            [$name, $email, $hash, $phone, $course, $year]
        );
        $success = 'Registration successful! You can now login.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card" style="max-width:500px;">
            <h2><span>Student</span> Registration</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo h($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo h($success); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" required>
                </div>
                <div class="form-group">
                    <label>Course</label>
                    <select name="course" required>
                        <option value="">Select Course</option>
                        <option value="BE">BE</option>
                        <option value="ME">ME</option>
                        <option value="B.Tech">B.Tech</option>
                        <option value="M.Tech">M.Tech</option>
                        <option value="BCA">BCA</option>
                        <option value="MCA">MCA</option>
                        <option value="BBA">BBA</option>
                        <option value="MBA">MBA</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Year</label>
                    <select name="year" required>
                        <option value="">Select Year</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                        <option value="Final Year">Final Year</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Register</button>
            </form>
            <div class="login-links">
                <p>Already have an account? <a href="login.php">Login here</a></p>
                <p style="margin-top:10px;"><a href="../index.php">Back to Home</a></p>
            </div>
        </div>
    </div>
</body>
</html>
