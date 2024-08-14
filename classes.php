<?php
require 'db.php';

if (isset($_POST['add_class'])) {
    $name = $_POST['name'];
    $stmt = $pdo->prepare("INSERT INTO classes (name, created_at) VALUES (?, NOW())");
    $stmt->execute([$name]);
    header('Location: classes.php');
    exit;
}

if (isset($_POST['update_class'])) {
    $class_id = $_POST['class_id'];
    $name = $_POST['name'];
    $stmt = $pdo->prepare("UPDATE classes SET name = ? WHERE class_id = ?");
    $stmt->execute([$name, $class_id]);
    header('Location: classes.php');
    exit;
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $class_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM classes WHERE class_id = ?");
    $stmt->execute([$class_id]);
    header('Location: classes.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM classes ORDER BY created_at DESC");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4">Manage Classes</h1>

    <?php if (isset($_GET['edit']) && $class): ?>
        <h3>Edit Class</h3>
        <form action="classes.php" method="POST">
            <input type="hidden" name="class_id" value="<?= htmlspecialchars($class['class_id']) ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Class Name</label>
                <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($class['name']) ?>" required>
            </div>
            <button type="submit" name="update_class" class="btn btn-primary">Update Class</button>
        </form>
    <?php else: ?>
        <h3>Add New Class</h3>
        <form action="classes.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Class Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <button type="submit" name="add_class" class="btn btn-primary">Add Class</button>
        </form>
    <?php endif; ?>

    <h3 class="mt-5">All Classes</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $class): ?>
                <tr>
                    <td><?= htmlspecialchars($class['class_id']) ?></td>
                    <td><?= htmlspecialchars($class['name']) ?></td>
                    <td><?= htmlspecialchars($class['created_at']) ?></td>
                    <td>
                        <a href="classes.php?edit=<?= $class['class_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="classes.php?delete=<?= $class['class_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
