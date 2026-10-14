<?php
// delete.php  -  DELETE operation
// Decryption is not needed here: the record is identified by its primary key.
require_once "connection.php";

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: view.php?msg=invalid");
    exit;
}

// Validate the CSRF token
if (!verifyCsrf($_POST["csrf_token"] ?? "")) {
    header("Location: view.php?msg=invalid");
    exit;
}

// Validate the numeric ID
$id = filter_var($_POST["id"] ?? "", FILTER_VALIDATE_INT);

if ($id === false || $id < 1) {
    header("Location: view.php?msg=invalid");
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $deleted = $stmt->affected_rows > 0;
    $stmt->close();
} catch (mysqli_sql_exception $ex) {
    $deleted = false;
}

header("Location: view.php?msg=" . ($deleted ? "deleted" : "notfound"));
exit;