@echo off
title College Placement System - Server
color 0A
setlocal enabledelayedexpansion

:: Get project directory (strip trailing backslash from %~dp0)
set "PROJECT_DIR=%~dp0"
set "PROJECT_DIR=%PROJECT_DIR:~0,-1%"

:: Find PHP
set PHP_CMD=php
where php >nul 2>&1
if !errorlevel! neq 0 (
    if exist "C:\PlacementServer\php\php.exe" set "PHP_CMD=C:\PlacementServer\php\php.exe"
    if exist "C:\xampp\php\php.exe" set "PHP_CMD=C:\xampp\php\php.exe"
    for /d %%d in (C:\wamp64\bin\php\php*) do if exist "%%d\php.exe" set "PHP_CMD=%%d\php.exe"
)

!PHP_CMD! -v >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] PHP not found. Install PHP or add it to PATH.
    pause
    exit /b 1
)

echo ============================================
echo   College Placement System
echo ============================================
echo.
echo Starting server...
echo.

:: Kill any existing PHP server on port 8080
for /f "tokens=5" %%a in ('netstat -ano ^| find ":8080" ^| find "LISTENING"') do (
    taskkill /F /PID %%a >nul 2>&1
)

:: Start PHP development server
start "Placement Server" /B "!PHP_CMD!" -S localhost:8080 -t "!PROJECT_DIR!" "!PROJECT_DIR!\router.php"
timeout /t 2 /nobreak > nul

echo [OK] Server running at http://localhost:8080
echo [OK] Press any key to stop the server.
echo.
pause >nul

:: Shutdown
echo Shutting down...
for /f "tokens=5" %%a in ('netstat -ano ^| find ":8080" ^| find "LISTENING"') do (
    taskkill /F /PID %%a >nul 2>&1
)
echo Done.
