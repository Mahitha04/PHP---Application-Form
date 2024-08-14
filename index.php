<?php
require 'db.php';

$query = $pdo->query("
    SELECT student.id, student.name, student.email, student.address, student.created_at, student.image, student.class_id, classes.name as class_name
    FROM student
    LEFT JOIN classes ON student.class_id = classes.class_id
    ORDER BY student.created_at DESC
");

$students = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4 text-center">Students List</h1>


    <table class="table table-hover table-bordered tavle-dark border-success">
        <thead class="table-success text-center">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Class</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= htmlspecialchars($student['class_id'] ?? 'Not Assigned') ?></td>
                    <td><img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Image" style="width:50px; height: 50px;"></td>
                    <td><?= htmlspecialchars($student['created_at']) ?></td>
                    <td>
                        <a href="view.php?id=<?= $student['id'] ?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit.php?id=<?= $student['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $student['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-start mb-3">
        <a href="create.php" class="btn btn-primary mb-3">Add New Student</a>
    <div>
</div>
</body>
</html>
