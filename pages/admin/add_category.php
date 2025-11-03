<?php
include '../../database/db_connect.php'; 

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    $image = $_POST['image'];

   
    if (empty($category_name) || empty($image)) {
        $message = "<div class='alert error'>⚠️ Please fill all fields.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (category_name, image) VALUES (?, ?)");
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param("ss", $category_name, $image);

        if ($stmt->execute()) {
            $message = "<div class='alert success'>✅ Category added successfully!</div>";
        } else {
            $message = "<div class='alert error'>❌ Failed to add category.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<div class="container">
    <h2>Add New Category</h2>
    <?= $message; ?>

    <form action="" method="POST" class="form-card">
        <label>Category Name</label>
        <input type="text" name="category_name" placeholder="e.g., Fruits" required>

        <label>Image URL</label>
        <input type="url" name="image" placeholder="https://example.com/image.jpg" required>

        <div class="form-actions">
            <button type="submit" class="btn btn-add">Save</button>
            <a href="manage_categories.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
