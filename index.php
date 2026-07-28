<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Placement System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><span>College</span> Placement System</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="student/login.php">Student Login</a>
                <a href="admin/login.php">Admin Login</a>
            </nav>
        </div>
    </header>

    <div class="container" style="flex:1;">
        <div class="card" style="text-align:center; margin-top:50px;">
            <h2>Welcome to College Placement System</h2>
            <p style="font-size:18px; color:#666; margin:20px 0;">
                A platform connecting students with top companies for campus placements.
            </p>
            <div style="display:flex; justify-content:center; gap:20px; margin-top:30px; flex-wrap:wrap;">
                <a href="student/login.php" class="btn btn-primary" style="padding:15px 40px; font-size:16px;">Student Login</a>
                <a href="student/register.php" class="btn btn-success" style="padding:15px 40px; font-size:16px;">Student Register</a>
                <a href="admin/login.php" class="btn btn-warning" style="padding:15px 40px; font-size:16px;">Admin Login</a>
            </div>
        </div>

        <div class="stats-grid" style="margin-top:40px;">
            <div class="stat-card">
                <h3>100+</h3>
                <p>Partner Companies</p>
            </div>
            <div class="stat-card">
                <h3>500+</h3>
                <p>Students Placed</p>
            </div>
            <div class="stat-card">
                <h3>200+</h3>
                <p>Job Openings</p>
            </div>
            <div class="stat-card">
                <h3>95%</h3>
                <p>Placement Rate</p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 College Placement System. All rights reserved.</p>
    </footer>
</body>
</html>
