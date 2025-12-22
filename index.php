<?php 
// db connection 
include('./db.php');

// database theke data niye asar system
$sql = "SELECT * FROM image";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Image</title>
</head>
<body>

<h2>Display Image With Title</h2>

<h3><a href="./create.php">Add Image</a></h3>

<?php
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $title = $row['title'];
        $path  = $row['path'];
        ?>
        
       
   <div style="width:200px; height:300px; display:inline-block; margin-bottom:20px;">
    <img style="width:100%; height:200px; object-fit:cover;" 
         src="<?php echo $path; ?>" 
         alt="<?php echo $title; ?>">
    <p><?php echo $title; ?></p>
</div>
       

        <?php
    }

} else {
    echo "No image found";
}
?>

</body>
</html>
