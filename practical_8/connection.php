<?php
// connection.php
// Database connection (mysqli) and small helper functions shared by all pages.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host     = "localhost";
$user     = "root";
$password = "";               // default XAMPP MySQL password is empty
$database = "student_crud";

// Throw exceptions on MySQL errors so they can be handled with try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Database connection failed. Make sure MySQL is running in XAMPP "
        . "and the database 'student_crud' has been created.");
}

// Escape output for safe display in HTML
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Create (or reuse) the CSRF token stored in the session
function csrfToken()
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

// Check a submitted CSRF token against the session token
function verifyCsrf($token)
{
    return isset($_SESSION["csrf_token"])
        && is_string($token)
        && hash_equals($_SESSION["csrf_token"], $token);
}