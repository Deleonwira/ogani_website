<?php
include '../../database/db_connect.php';

$message = "";


if (!isset($_GET['id'])) {
    die("Category ID is missing.");
}

$id = intval($_GET['id']);


$sql = "SELECT * FROM categories WHERE category_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Category not found.");
}

$category = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    $image = $_POST['image'];

    if (empty($category_name) || empty($image)) {
        $message = "<div class='alert error'>⚠️ Please fill all fields.</div>";
    } else {
        $update = $conn->prepare("UPDATE categories SET category_name = ?, image = ? WHERE category_id = ?");
        if (!$update) {
            die("SQL Error: " . $conn->error);
        }
        $update->bind_param("ssi", $category_name, $image, $id);

        if ($update->execute()) {
            $message = "<div class='alert success'>✅ Category updated successfully!</div>";
            // Update data yang ditampilkan tanpa reload manual
            $category['category_name'] = $category_name;
            $category['image'] = $image;
        } else {
            $message = "<div class='alert error'>❌ Failed to update category.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<div class="container">
    <h2>Edit Category</h2>
    <?= $message; ?>

    <form action="" method="POST" class="form-card">
        <label>Category Name</label>
        <input type="text" name="category_name" value="<?= htmlspecialchars($category['category_name']); ?>" required>

        <label>Image URL</label>
        <input type="url" name="image" value="<?= htmlspecialchars($category['image']); ?>" required>

        <div class="form-actions">
            <button type="submit" class="btn btn-add">Update</button>
            <a href="manage_categories.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
