<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path === '/' || $path === '') {
    require __DIR__ . '/index.php';
    return true;
}

if (is_file($file)) {
    return false;
}

http_response_code(404);
echo "<h2>404 - Page Not Found</h2>";
echo "<a href='/'>Go to Home</a>";
return true;
?>
