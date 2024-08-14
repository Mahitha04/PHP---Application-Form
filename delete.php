<?php
require 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid student ID.");
}

$stmt = $pdo->prepare("SELECT * FROM student WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($student['image'] && file_exists("uploads/" . $student['image'])) {
        unlink("uploads/" . $student['image']);
    }

    $stmt = $pdo->prepare("DELETE FROM student WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4">Delete Student</h1>

    <p>Are you sure you want to delete the student <strong><?= htmlspecialchars($student['name']) ?></strong>?</p>
    
    <form method="post">
        <button type="submit" class="btn btn-danger">Delete</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
