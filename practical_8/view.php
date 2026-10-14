<?php
// view.php  -  READ operation
require_once "connection.php";
require_once "encryption.php";

$messages = [
    "added"    => ["success", "Student record added successfully."],
    "updated"  => ["success", "Student record updated successfully."],
    "deleted"  => ["success", "Student record deleted successfully."],
    "notfound" => ["error",   "The requested record was not found."],
    "invalid"  => ["error",   "Invalid request."],
];

$msgKey = $_GET["msg"] ?? "";
$flash  = $messages[$msgKey] ?? null;

$students = [];
$loadError = "";

try {
    $result = $conn->query(
        "SELECT id, enrollment_no, student_name, email, department, semester
         FROM students
         ORDER BY id DESC"
    );
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
} catch (mysqli_sql_exception $ex) {
    $loadError = "Could not load student records.";
}

// Decrypt a stored value for display
function showDecrypted($value)
{
    $plain = decryptData($value);
    return ($plain === false) ? "[Decryption failed]" : $plain;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container wide">
    <h1>Student Records</h1>
    <p class="subtitle">Data is decrypted only for display. The database stores ciphertext.</p>

    <?php if ($flash): ?>
        <div class="message <?php echo e($flash[0]); ?>"><?php echo e($flash[1]); ?></div>
    <?php endif; ?>

    <?php if ($loadError !== ""): ?>
        <div class="message error"><?php echo e($loadError); ?></div>
    <?php endif; ?>

    <div class="actions top">
        <a class="btn-link" href="insert.php">+ Add New Student</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Enrollment No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="7" class="empty">No student records found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo e($student["id"]); ?></td>
                        <td><?php echo e(showDecrypted($student["enrollment_no"])); ?></td>
                        <td><?php echo e(showDecrypted($student["student_name"])); ?></td>
                        <td><?php echo e(showDecrypted($student["email"])); ?></td>
                        <td><?php echo e($student["department"]); ?></td>
                        <td><?php echo e($student["semester"]); ?></td>
                        <td class="row-actions">
                            <a class="edit" href="update.php?id=<?php echo (int) $student["id"]; ?>">Edit</a>
                            <form method="post" action="delete.php"
                                  onsubmit="return confirm('Delete this student record?');">
                                <input type="hidden" name="csrf_token"
                                       value="<?php echo e(csrfToken()); ?>">
                                <input type="hidden" name="id"
                                       value="<?php echo (int) $student["id"]; ?>">
                                <button type="submit" class="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>