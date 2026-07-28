<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = db_query("SELECT * FROM admins WHERE email = ?", [$email]);
    $admin = db_fetch($stmt);

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
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
    <title>Admin Login - College Placement System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2><span>Admin</span> Login</h2>
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
                <button type="submit" class="btn btn-warning" style="width:100%;">Login</button>
            </form>
            <div class="login-links">
                <p><a href="../index.php">Back to Home</a></p>
            </div>
        </div>
    </div>
</body>
</html>
