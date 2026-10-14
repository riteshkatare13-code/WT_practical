<?php
// update.php  -  UPDATE operation
require_once "connection.php";
require_once "encryption.php";

$departments = [
    "Computer Science and Engineering",
    "Cyber Security",
    "Information Technology",
    "Electronics and Telecommunication",
    "Mechanical Engineering",
    "Civil Engineering",
];

// Get the record ID (from the link on first load, from the form on submit)
$id = filter_var($_POST["id"] ?? $_GET["id"] ?? "", FILTER_VALIDATE_INT);

if ($id === false || $id < 1) {
    header("Location: view.php?msg=invalid");
    exit;
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!verifyCsrf($_POST["csrf_token"] ?? "")) {
        $errors[] = "Invalid form submission. Please try again.";
    }

    $enrollment = trim($_POST["enrollment_no"] ?? "");
    $name       = trim($_POST["student_name"] ?? "");
    $email      = trim($_POST["email"] ?? "");
    $department = trim($_POST["department"] ?? "");
    $semester   = trim($_POST["semester"] ?? "");

    // Validation
    if ($enrollment === "" || $name === "" || $email === ""
        || $department === "" || $semester === "") {
        $errors[] = "All fields are required.";
    }

    if ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($department !== "" && !in_array($department, $departments, true)) {
        $errors[] = "Please select a valid department.";
    }

    $semesterValue = filter_var($semester, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1, "max_range" => 8],
    ]);
    if ($semester !== "" && $semesterValue === false) {
        $errors[] = "Semester must be a number from 1 to 8.";
    }

    if (empty($errors)) {
        // Encrypt the modified values again before saving
        $encEnrollment = encryptData($enrollment);
        $encName       = encryptData($name);
        $encEmail      = encryptData($email);

        if ($encEnrollment === false || $encName === false || $encEmail === false) {
            $errors[] = "Encryption failed. Check that the OpenSSL extension is enabled.";
        } else {
            try {
                $stmt = $conn->prepare(
                    "UPDATE students
                     SET enrollment_no = ?, student_name = ?, email = ?,
                         department = ?, semester = ?
                     WHERE id = ?"
                );
                $stmt->bind_param(
                    "ssssii",
                    $encEnrollment,
                    $encName,
                    $encEmail,
                    $department,
                    $semesterValue,
                    $id
                );
                $stmt->execute();
                $stmt->close();

                header("Location: view.php?msg=updated");
                exit;
            } catch (mysqli_sql_exception $ex) {
                $errors[] = "Could not update the record. Please try again.";
            }
        }
    }
} else {
    // First load: fetch the record and decrypt the values for editing
    try {
        $stmt = $conn->prepare(
            "SELECT enrollment_no, student_name, email, department, semester
             FROM students WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } catch (mysqli_sql_exception $ex) {
        $row = null;
    }

    if (!$row) {
        header("Location: view.php?msg=notfound");
        exit;
    }

    $enrollment = decryptData($row["enrollment_no"]);
    $name       = decryptData($row["student_name"]);
    $email      = decryptData($row["email"]);
    $department = $row["department"];
    $semester   = $row["semester"];

    if ($enrollment === false || $name === false || $email === false) {
        $errors[] = "This record could not be decrypted. "
                  . "Re-enter the values to save them again.";
        $enrollment = $enrollment === false ? "" : $enrollment;
        $name       = $name === false ? "" : $name;
        $email      = $email === false ? "" : $email;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Update Student</h1>
    <p class="subtitle">Values are decrypted for editing and encrypted again when saved.</p>

    <?php if (!empty($errors)): ?>
        <div class="message error">
            <?php foreach ($errors as $error): ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="update.php">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

        <label for="enrollment_no">Enrollment Number</label>
        <input type="text" id="enrollment_no" name="enrollment_no"
               value="<?php echo e($enrollment); ?>" required>

        <label for="student_name">Student Name</label>
        <input type="text" id="student_name" name="student_name"
               value="<?php echo e($name); ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?php echo e($email); ?>" required>

        <label for="department">Department</label>
        <select id="department" name="department" required>
            <option value="">-- Select Department --</option>
            <?php foreach ($departments as $dept): ?>
                <option value="<?php echo e($dept); ?>"
                    <?php echo ($department === $dept) ? "selected" : ""; ?>>
                    <?php echo e($dept); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="semester">Semester</label>
        <input type="number" id="semester" name="semester" min="1" max="8"
               value="<?php echo e($semester); ?>" required>

        <div class="actions">
            <button type="submit">Update Student</button>
            <a class="btn-link secondary" href="view.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>