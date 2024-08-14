<?php
require 'db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = $_GET['id'];

    $stmt = $pdo->prepare("
        SELECT student.id, student.name, student.email, student.address, student.created_at, student.image, student.class_id, classes.name as class_name
        FROM student
        LEFT JOIN classes ON student.class_id = classes.class_id
        WHERE student.id = ?
    ");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>View Student</title>
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
            </style>

        </head>
        <body>
        <div class="container">
            <h1 class="my-4">View Student</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($student['name']) ?></h5>
                    <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
                    <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
                    <p class="card-text"><strong>Class:</strong> <?= htmlspecialchars($student['class_id'] ?? 'Not Assigned') ?></p>
                    <p class="card-text"><strong>Created At:</strong> <?= htmlspecialchars($student['created_at']) ?></p>
                    <?php if ($student['image']): ?>
                        <p class="card-text"><strong>Image:</strong><br>
                            <img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Student Image" style="max-width: 50%; height: 70px;">
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <a href="index.php" class="btn btn-primary mt-3">Back to List</a>
        </div>
        </body>
        </html>
        <?php
    } else {
        echo "<p class='alert alert-danger'>Student not found.</p>";
    }
} else {
    echo "<p class='alert alert-danger'>Invalid student ID.</p>";
}
?>
