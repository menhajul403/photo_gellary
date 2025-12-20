<?php
// ================= DB CONNECTION =================
$conn = new mysqli("localhost", "root", "", "test");

if ($conn->connect_error) {
    die("DB Connection Failed");
}

// ================= FORM SUBMIT =================
$message = "";

if (isset($_POST['submit'])) {

    $title = $_POST['title'];

    // image info
    $imageName = $_FILES['image']['name'];
    $tmpName   = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];

    $uploadDir = "upload/";
    $path = $uploadDir . time() . "_" . basename($imageName);

    // create folder if not exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // image validation
    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $message = "❌ Only JPG, JPEG, PNG allowed";
    } elseif ($imageSize > 2 * 1024 * 1024) {
        $message = "❌ Image size must be under 2MB";
    } else {

        if (move_uploaded_file($tmpName, $path)) {

            // ✅ Prepared Statement (Secure)
            $stmt = $conn->prepare(
                "INSERT INTO image (title, path) VALUES (?, ?)"
            );
            $stmt->bind_param("ss", $title, $path);

            if ($stmt->execute()) {
                $message = "✅ Image uploaded successfully";
            } else {
                $message = "❌ Database insert failed";
            }

        } else {
            $message = "❌ Image upload failed";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Image</title>
</head>
<body>

<h2>Create Image</h2>

<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Image</label><br>
    <input type="file" name="image" required><br><br>

    <button type="submit" name="submit">Upload</button>
</form>

</body>
</html>
