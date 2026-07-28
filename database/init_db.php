<?php
require_once __DIR__ . '/../config/db.php';

$db = getDB();

$db->exec("
CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("
CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    phone TEXT,
    course TEXT,
    year TEXT,
    resume_path TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("
CREATE TABLE IF NOT EXISTS jobs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    company TEXT NOT NULL,
    location TEXT,
    eligibility TEXT,
    last_date TEXT,
    created_by INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE CASCADE
)");

$db->exec("
CREATE TABLE IF NOT EXISTS applications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    job_id INTEGER NOT NULL,
    status TEXT DEFAULT 'applied',
    applied_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
)");

$db->exec("
CREATE TABLE IF NOT EXISTS placement_results (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    job_id INTEGER NOT NULL,
    result TEXT DEFAULT 'not_placed',
    package TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
)");

$stmt = db_query("SELECT id, password FROM admins WHERE email = 'admin@college.com'");
$existing = db_fetch($stmt);
if ($existing) {
    if (!str_starts_with($existing['password'], '$2y$')) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        db_exec("UPDATE admins SET password = ? WHERE id = ?", [$hash, $existing['id']]);
    }
} else {
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    db_exec("INSERT INTO admins (name, email, password) VALUES ('Admin', 'admin@college.com', ?)", [$hash]);
}

echo "Database initialized successfully!\n";
echo "Admin login: admin@college.com / admin123\n";
?>
