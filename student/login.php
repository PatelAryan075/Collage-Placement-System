<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = db_query("SELECT * FROM students WHERE email = ?", [$email]);
    $student = db_fetch($stmt);

    if ($student && password_verify($password, $student['password'])) {
        session_regenerate_id(true);
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['name'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2><span>Student</span> Login</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo h($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Login</button>
            </form>
            <div class="login-links">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <p style="margin-top:10px;"><a href="../index.php">Back to Home</a></p>
            </div>
        </div>
    </div>
</body>
</html>
