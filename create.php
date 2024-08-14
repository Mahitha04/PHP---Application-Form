<?php
require 'db.php';

$errors = [];
$name = '';
$email = '';
$address = '';
$class_id = '';
$image = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    
    $image_path = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed_ext)) {
            $unique_name = uniqid() . '.' . $file_ext;
            $upload_path = 'uploads/' . $unique_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_path = $upload_path;
            } else {
                echo "Failed to upload the image.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
        }
    }

    $sql = "INSERT INTO student (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$name, $email, $address, $class_id, $image_path])) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error: Unable to create student.";
    }
}

$sql = "SELECT * FROM classes";
$stmt = $pdo->query($sql);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: f8f9fa;
        }
        .container {
            background-color: #D1E7DD;
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
    <h1 class="my-4 text-center">Create Student</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="create.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name*</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($address) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <select name="class_id" class="form-control" id="class_id" required>
                <option value="">Select Class</option>
                <option value="LKG">LKG</option>
                <option value="UKG">UKG</option>
                <option value="1st Class">1st Class</option>
                <option value="2nd Class">2nd Class</option>
                <option value="3rd Class">3rd Class</option>
                <option value="4th Class">4th Class</option>
                <option value="5th Class">5th Class</option>
                <option value="6th Class">6th Class</option>
                <option value="7th Class">7th Class</option>
                <option value="8th Class">8th Class</option>
                <option value="9th Class">9th Class</option>
                <option value="10th Class">10th Class</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Upload Image(Only jpg, png)</label>
            <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="index.php" class="btn btn-primary">Back to List</a>
    </form>
</div>
</body>
</html>