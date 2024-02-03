<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "assets/Images/";
        $originalFileName = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $originalFileName;

        // File validation
        $allowed_extensions = ['jpeg', 'jpg', 'png'];
        $max_size = 500000; // 500KB - adjust as needed
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        if (!in_array($extension, $allowed_extensions)) {
            $errors[] = "Invalid file type. Allowed types are: " . implode(', ', $allowed_extensions);
        } elseif ($_FILES["image"]["size"] > $max_size) {
            $errors[] = "File is too large. Maximum size is " . ($max_size / 1000) . "KB.";
        }

        // Only move the file if there were no errors
        if (empty($errors) && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $image = $card['image'];
    }
    $_SESSION['image'] = $image;
}
?>
