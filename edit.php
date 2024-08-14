<?php
require 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid student ID.");
}

$stmt = $pdo->prepare("
    SELECT * FROM student WHERE id = ?
");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

$errors = [];
$name = $student['name'];
$email = $student['email'];
$address = $student['address'];
$class_id = $student['class_id'];
$image = $student['image'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['image']['name'];
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowed)) {
            $errors[] = "Invalid image format. Only JPG and PNG are allowed.";
        } else {
            if ($image && file_exists("uploads/" . $image)) {
                unlink("uploads/" . $image);
            }
            $image = uniqid('', true) . "." . $fileExt;
            move_uploaded_file($fileTmpName, "uploads/" . $image);
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?
        ");
        $stmt->execute([$name, $email, $address, $class_id, $image, $id]);

        header("Location: index.php");
        exit();
    }
}

$query = $pdo->query("SELECT * FROM classes");
$classes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #d1E7DD;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 50px auto;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="my-4 text-center">Edit Student</h1>

    <form action="edit.php?id=<?= $student['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Student Name</label>
            <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($student['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" class="form-control" id="address" rows="3" required><?= htmlspecialchars($student['address']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <select name="class_id" class="form-control" id="class_id" required>
                <option value="">Select Class</option>
                <option value="LKG" <?= $student['class_id'] == 'LKG' ? 'selected' : '' ?>>LKG</option>
                <option value="UKG" <?= $student['class_id'] == 'UKG' ? 'selected' : '' ?>>UKG</option>
                <option value="1st Class" <?= $student['class_id'] == '1st Class' ? 'selected' : '' ?>>1st Class</option>
                <option value="2nd Class" <?= $student['class_id'] == '2nd Class' ? 'selected' : '' ?>>2nd Class</option>
                <option value="3rd Class" <?= $student['class_id'] == '3rd Class' ? 'selected' : '' ?>>3rd Class</option>
                <option value="4th Class" <?= $student['class_id'] == '4th Class' ? 'selected' : '' ?>>4th Class</option>
                <option value="5th Class" <?= $student['class_id'] == '5th Class' ? 'selected' : '' ?>>5th Class</option>
                <option value="6th Class" <?= $student['class_id'] == '6th Class' ? 'selected' : '' ?>>6th Class</option>
                <option value="7th Class" <?= $student['class_id'] == '7th Class' ? 'selected' : '' ?>>7th Class</option>
                <option value="8th Class" <?= $student['class_id'] == '8th Class' ? 'selected' : '' ?>>8th Class</option>
                <option value="9th Class" <?= $student['class_id'] == '9th Class' ? 'selected' : '' ?>>9th Class</option>
                <option value="10th Class" <?= $student['class_id'] == '10th Class' ? 'selected' : '' ?>>10th Class</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control" id="image">
            <small class="form-text text-muted">Leave blank to keep the current image.</small>
        </div>
        <div class="text-center">
            <button type="submit" name="submit" class="btn btn-primary">Update Student</button>
            <a href="index.php" class="btn btn-primary">Back to List</a>
        </div>
    </form>
</div>
</body>
</html>

