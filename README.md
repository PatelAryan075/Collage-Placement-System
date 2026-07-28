# College Placement System

A web-based campus placement management system built with **PHP** and **SQLite** that connects students with companies for campus recruitment drives.

## Features

### Student Module
- Student registration and login
- View available job openings
- Apply for jobs with one click
- Upload resume
- Track application status and placement results

### Admin Module
- Admin dashboard with statistics
- Post new job openings
- Manage existing jobs
- View and manage student applications
- Update placement results (selected/rejected)

## Tech Stack

- **Backend:** PHP
- **Database:** SQLite
- **Frontend:** HTML, CSS

## Project Structure

```
College Placement System/
├── admin/                  # Admin panel files
│   ├── dashboard.php
│   ├── login.php
│   ├── post_job.php
│   ├── manage_jobs.php
│   ├── view_applications.php
│   └── placement_results.php
├── student/                # Student panel files
│   ├── dashboard.php
│   ├── login.php
│   ├── register.php
│   ├── view_jobs.php
│   ├── apply_job.php
│   ├── upload_resume.php
│   └── view_results.php
├── config/                 # Database configuration
│   ├── db.php
│   └── db_connect.php
├── database/               # Database files
│   └── init_db.php
├── css/                    # Stylesheets
│   └── style.css
├── uploads/                # Resume uploads
├── index.php               # Home page
├── logout.php              # Logout script
├── run.bat                 # Server startup (Windows)
└── server.bat              # Server startup (Windows)
```

## How to Run

### Prerequisites
- PHP 7.4 or higher
- XAMPP/WAMP/LAMP or any PHP server

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/College-Placement-System.git
   ```

2. **Place in web server root**
   - Move the folder to `htdocs` (XAMPP) or `www` (WAMP)

3. **Initialize the database**
   ```bash
   php database/init_db.php
   ```

4. **Start the server**
   - Double-click `run.bat` or `server.bat`
   - Or use: `php -S localhost:8000`

5. **Access the application**
   - Open browser: `http://localhost:8000`

## Default Admin Credentials

| Field    | Value                |
|----------|----------------------|
| Email    | admin@college.com    |
| Password | admin123             |

## Database Schema

| Table              | Description                              |
|--------------------|------------------------------------------|
| `admins`           | Admin user accounts                      |
| `students`         | Student profiles with course/year info   |
| `jobs`             | Job postings with company details        |
| `applications`     | Student job applications                 |
| `placement_results`| Final placement outcomes                 |

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

For questions or support, please open an issue on GitHub.
